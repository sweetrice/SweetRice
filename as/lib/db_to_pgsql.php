<?php
/**
 * Convert Database to PostgreSql.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.5
 */
 defined('VALID_INCLUDE') or die();
	$to_db_name = $_POST['to_db_name'];
	$to_db_left = $_POST['to_db_left'];
	$to_db_url = $_POST['to_db_url'];
	$to_db_port = $_POST['to_db_port'];
	$to_db_username = $_POST['to_db_username'];
	$to_db_passwd = $_POST['to_db_passwd'];
	$tablelist = $_POST['tablelist'];
	if(DATABASE_TYPE == 'pgsql' && $to_db_name == $db_name && $to_db_left == DB_LEFT && $to_db_url == $db_url && $to_db_port == $db_port){
		output_json(array('status'=>1,'status_code'=>_t('Database convert successfully!')));
	}
	if($to_db_name && $to_db_left && $tablelist){
		$plugin_sql = array();
		$plugin_list = pluginList();
		foreach($plugin_list AS $plugin_config){
			if(file_exists(SITE_HOME.'_plugin/'.$plugin_config['directory'].'/plugin_config.php') && $plugin_config['installed']){
				if($plugin_config['install_pgsql']){
					$plugin_sql[$plugin_config['name']] = SITE_HOME.'_plugin/'.$plugin_config['directory'].'/'.$plugin_config['install_pgsql'];
				}
			}
		}
		$GLOBALS['to_db_lib'] = new pgsql_lib(array('url'=>$to_db_url,'port'=>$to_db_port,'username'=>$to_db_username,'passwd'=>$to_db_passwd,'name'=>$to_db_name));
		if(!$GLOBALS['to_db_lib']->stat()){
			$message .= _t('Database error!').' <br>';
		}else{
			$sql = file_get_contents('lib/app_pgsql.sql');
			$sql = str_replace('%--%',$to_db_left,$sql);
			$sql = explode(';',$sql);
			foreach($sql as $key=>$val){
				if(trim($val)){
					if(!$GLOBALS['to_db_lib']->query($val)){
						$message .= $val.' : '.$GLOBALS['to_db_lib']->error().'<br>';
						break;
					}
				}
			}
			foreach($plugin_sql as $key=>$val){
				$sql = file_get_contents($val);
				$sql = str_replace('%--%',$to_db_left.'_plugin',$sql);
				$sql = explode(';',$sql);
				foreach($sql as $key=>$val){
					if(trim($val)){
						if(!$GLOBALS['to_db_lib']->query($val)){
							$message .= $val.' : '.$GLOBALS['to_db_lib']->error().'<br>';
							break;
						}
					}
				}
			}
			switch(DATABASE_TYPE){
				case 'sqlite':
					foreach($tablelist as $val){
						$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
						$field_list = array();
						$serial_field = null;
						$fields = db_arrays("PRAGMA table_info(".$val.")");
						foreach($fields as $field){
							$field_list[$field['name']] = $field['type'];
							if (!$serial_field) {
								$serial_field = $field['name'];
							}
						}
						$rows = db_arrays("SELECT * FROM \"".$val."\"");
						foreach($rows as $row){
							$comma = "";
							$str_ = $str = '';
							foreach($field_list as $fl=>$tp){
								$str_ .= $comma."\"".$fl."\"";
								if($tp=='INTEGER'||strtolower(substr($tp,0,3))=='int'||strtolower(substr($tp,0,7))=='tinyint'){
									$str .= $comma."'".intval($row[$fl])."'";
								}elseif(is_string($row[$fl])){
									$str .= $comma."'".pg_escape_string($row[$fl])."'";
								}else{
									$str .= $comma."'".$row[$fl]."'";
								}	
								$comma = ",";
							}
							$tabledump = "INSERT INTO \"".$to_val."\" (".$str_.") VALUES(".$str.");";
							if(!$GLOBALS['to_db_lib']->query($tabledump)){
								$db_error .= $tabledump.' : '.$GLOBALS['to_db_lib']->error().'<br>';
								break;
							}
						}
						if (!$GLOBALS['to_db_lib']->query("SELECT setval('".$to_val."_".$serial_field."_seq', (SELECT MAX(".$serial_field.") FROM ".$to_val.")+1);")) {
								$db_error .= $GLOBALS['to_db_lib']->error().'<br>';
						}
					}
					if(!$db_error){
						$do_db = true;
					}else{
						$message .= $db_error;
					}
				break;
				case 'mysql':
					foreach($tablelist as $val){
						$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
						$field_list = array();
						$serial_field = null;
						$res = $GLOBALS['db_lib']->query("SELECT * FROM `".$val."`");
						while($row_field = $GLOBALS['db_lib']->fetch_field($res)){
							$field_list[$row_field->name] = $row_field->type;
							if (!$serial_field) {
								$serial_field = $row_field->name;
							}
						}
						while ($row = $GLOBALS['db_lib']->fetch_array($res)){
							$comma = "";
							$str_ = $str = '';
							foreach($field_list as $fl=>$tp){
								$str_ .= $comma."\"".$fl."\"";
								if($tp=='int'){
									$str .= $comma."'".intval($row[$fl])."'";
								}elseif(is_string($row[$fl])){
									$str .= $comma."'".pg_escape_string($row[$fl])."'";
								}else{
									$str .= $comma."'".$row[$fl]."'";
								}	
								$comma = ",";
							}
							$tabledump = "INSERT INTO \"".$to_val."\" (".$str_.") VALUES(".$str.");";
							if(!$GLOBALS['to_db_lib']->query($tabledump)){
								$db_error .= $tabledump.' : '.$GLOBALS['to_db_lib']->error().'<br>';
								break;
							}
						}
						if (!$GLOBALS['to_db_lib']->query("SELECT setval('".$to_val."_".$serial_field."_seq', (SELECT MAX(".$serial_field.") FROM ".$to_val.")+1);")) {
								$db_error .= $serial_field.$GLOBALS['to_db_lib']->error().'<br>';
						}
					}
					if(!$db_error){
						$do_db = true;
					}else{
						$message .= $db_error;
					}
				break;
				case 'pgsql':
					foreach($tablelist as $val){
						$field_list = array();
						$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
						$fields = db_arrays("SELECT attnum,attname , typname , atttypmod-4 , attnotnull ,atthasdef ,adsrc AS def FROM pg_attribute, pg_class, pg_type, pg_attrdef WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND pg_class.oid=adrelid AND adnum=attnum AND lower(relname)='$val' UNION SELECT attnum,attname , typname , atttypmod-4 , attnotnull , atthasdef ,'' AS def FROM pg_attribute, pg_class, pg_type WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND lower(relname)='".$val."' order by attnum");
						foreach($fields as $field){
							$field_list[$field['attname']] = $field['typname'];
							if(preg_match('/nextval\(\''.$val.'_.+_seq\'::regclass\).*/',$field['def'])){
								$serial_field = $field['attname'];			
							}
						}
						$rows = db_arrays("SELECT * FROM \"".$val."\"");
						foreach ($rows as $row){
							$comma = "";
							$str_ = $str = '';
							foreach($field_list as $fl=>$tp){
								$str_ .= $comma."\"".$fl."\"";
								if(strtolower(substr($tp,0,3))=='int'){
									$str .= $comma."'".intval($row[$fl])."'";
								}elseif(is_string($row[$fl])){
									$str .= $comma."'".pg_escape_string($row[$fl])."'";
								}else{
									$str .= $comma."'".$row[$fl]."'";
								}	
								$comma = ",";
							}
							$tabledump = "INSERT INTO \"".$to_val."\" (".$str_.") VALUES(".$str.");";

							if(!$GLOBALS['to_db_lib']->query($tabledump)){
								$db_error .= $tabledump.' : '.$GLOBALS['to_db_lib']->error().'<br>';
								break;
							}
						}
						if (!$GLOBALS['to_db_lib']->query("SELECT setval('".$to_val."_".$serial_field."_seq', (SELECT MAX(".$serial_field.") FROM ".$to_val.")+1);")) {
								$db_error .= $GLOBALS['to_db_lib']->error().'<br>';		
						}
					}
					if(!$db_error){
						$do_db = true;
					}else{
						$message .= $db_error;
					}
				break;
			}
		}

		if($do_db){
			$db_str = "<?php\n";
			$db_str .= '$database_type = \'pgsql\';'."\n";
			$db_str .= '$db_left = \''.$to_db_left.'\';'."\n";
			$db_str .= '$db_url = \''.$to_db_url.'\';'."\n";
			$db_str .= '$db_port = \''.$to_db_port.'\';'."\n";
			$db_str .= '$db_name = \''.$to_db_name.'\';'."\n";
			$db_str .= '$db_username = \''.$to_db_username.'\';'."\n";
			$db_str .= '$db_passwd = \''.$to_db_passwd.'\';'."\n";
			$db_str .= "?>";
			file_put_contents(SITE_HOME.'inc/db.php',$db_str);
			$GLOBALS['to_db_lib']->close();
			if(DATABASE_TYPE == 'sqlite'){
				$GLOBALS['db_lib']->close();
				unlink(SITE_HOME.'inc/'.$db_name.'.db');
			}
			output_json(array('status'=>1,'status_code'=>_t('Database convert successfully!')));
		}else{
			output_json(array('status'=>0,'status_code'=>$message));
		}
	}else{
		output_json(array('status'=>0,'status_code'=>_t('Please fill out form below.')));
	}
?>