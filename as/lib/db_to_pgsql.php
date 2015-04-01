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
		alert(_t('Database convert successfully!'),'./');
	}
	if($to_db_name&&$to_db_left&&$tablelist){
			$plugin_sql = array();
			$plugin_list = pluginList();
			foreach($plugin_list AS $plugin_config){
				if(file_exists(SITE_HOME.'_plugin/'.$plugin_config['directory'].'/plugin_config.php') && $plugin_config['installed']){
					if($plugin_config['install_pgsql']){
						$plugin_sql[$plugin_config['name']] = SITE_HOME.'_plugin/'.$plugin_config['directory'].'/'.$plugin_config['install_pgsql'];
					}
				}
			}
			$to_conn = pg_connect("host=".$to_db_url." port=".$to_db_port." dbname=".$to_db_name." user=".$to_db_username." password=".$to_db_passwd);
			if(!$to_conn){
				$message .= _t('Database error!').' <br>';
			}else{
				$sql = file_get_contents('lib/blog_pgsql.sql');
				$sql = str_replace('%--%',$to_db_left,$sql);
				$sql = explode(';',$sql);
				foreach($sql as $key=>$val){
					if(trim($val)){
						if(!pg_query($to_conn,$val)){
							$message .= $val.' : '.pg_last_error($to_conn).'<br>';
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
								if(!pg_query($to_conn,$val)){
									$message .= $val.' : '.pg_last_error($to_conn).'<br>';
									break;
								}
							}
						}	
				}
				switch(DATABASE_TYPE){
					case 'sqlite':
						foreach($tablelist as $val){
							$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
							$field_list = '';
							$rows = db_arrays("SELECT * FROM \"".$val."\"");
							$fields = db_arrays("PRAGMA table_info(".$val.")");
							foreach($fields as $field){
								$field_list[$field['name']] = $field['type'];
							}
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
								if(!pg_query($to_conn,$tabledump)){
									$db_error .= $tabledump.' : '.pg_last_error($to_conn).'<br>';
									break;
								}
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
							$field_list = '';
							$res = mysql_query("SELECT * FROM `".$val."`");
							while($row_field = mysql_fetch_field($res)){
								$field_list[$row_field->name] = $row_field->type;
							}
							while ($row = mysql_fetch_array($res)){
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
								if(!pg_query($to_conn,$tabledump)){
									$db_error .= $tabledump.' : '.pg_last_error($to_conn).'<br>';
									break;
								}
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
							$field_list = '';
							$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
							$fields = db_arrays("SELECT attnum,attname , typname , atttypmod-4 , attnotnull ,atthasdef ,adsrc AS def FROM pg_attribute, pg_class, pg_type, pg_attrdef WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND pg_class.oid=adrelid AND adnum=attnum AND lower(relname)='$table' UNION SELECT attnum,attname , typname , atttypmod-4 , attnotnull , atthasdef ,'' AS def FROM pg_attribute, pg_class, pg_type WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND lower(relname)='".$val."' order by attnum");
							foreach($fields as $field){
								$field_list[$field['attname']] = $field['typname'];
							}
							$res = pg_query($conn,"SELECT * FROM \"".$val."\"");
							while ($row = pg_fetch_array($res)){
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

								if(!pg_query($to_conn,$tabledump)){
									$db_error .= $tabledump.' : '.pg_last_error($to_conn).'<br>';
									break;
								}
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
			if(extension_loaded('pdo_sqlite')){
				$sqlite_driver = 'pdo_sqlite';
			}else{
				$sqlite_driver = 'sqlite';
			}
			$db_str = "<?php\n";
			$db_str .= '$database_type = \'pgsql\';'."\n";
			$db_str .= '$db_left = \''.$to_db_left.'\';'."\n";
			$db_str .= '$db_url = \''.$to_db_url.'\';'."\n";
			$db_str .= '$db_port = \''.$to_db_port.'\';'."\n";
			$db_str .= '$db_name = \''.$to_db_name.'\';'."\n";
			$db_str .= '$db_username = \''.$to_db_username.'\';'."\n";
			$db_str .= '$db_passwd = \''.$to_db_passwd.'\';'."\n";
			$db_str .= '$sqlite_driver = \''.$sqlite_driver.'\';'."\n";
			$db_str .= "?>";
			file_put_contents(SITE_HOME.'inc/db.php',$db_str);
			pg_close($to_conn);
			if(DATABASE_TYPE=='sqlite'){
				$db = null;
				unlink(SITE_HOME.'inc/'.$db_name.'.db');
			}
			alert(_t('Database convert successfully!'),'./');
		}
	}else{
		$message = _t('Please fill out form below.');
	}
?>