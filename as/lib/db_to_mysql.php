<?php
/**
 * Convert Database to Mysql.
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
	
	if(DATABASE_TYPE == 'mysql' && $to_db_name == $db_name && $to_db_left == DB_LEFT && $to_db_url == $db_url && $to_db_port == $db_port){
		alert(_t('Database convert successfully!'),'./');
	}
	if($to_db_name&&$to_db_left&&$tablelist){
			$plugin_sql = array();
			$plugin_list = pluginList();
			foreach($plugin_list AS $plugin_config){
				if(file_exists(SITE_HOME.'_plugin/'.$plugin_config['directory'].'/plugin_config.php') && $plugin_config['installed']){
					if($plugin_config['install_sql']){
						$plugin_sql[$plugin_config['name']] = SITE_HOME.'_plugin/'.$plugin_config['directory'].'/'.$plugin_config['install_sql'];
					}
				}
			}
			$to_conn = mysql_connect($to_db_url.':'.$to_db_port,$to_db_username,$to_db_passwd,true);
			$to_db = mysql_select_db($to_db_name,$to_conn);
			if(!$to_db){
				$message .= _t('Database error!').' <br>';
			}else{
				$sql = file_get_contents('lib/app.sql');
				$sql = str_replace('%--%',$to_db_left,$sql);
				$sql = explode(';',$sql);
				foreach($sql as $key=>$val){
					if(trim($val)){
						if(!mysql_query($val,$to_conn)){
							$message .= $val.' : '.mysql_error($to_conn).'<br>';
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
								if(!mysql_query($val,$to_conn)){
									$message .= $val.' : '.mysql_error($to_conn).'<br>';
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
							$rows = db_arrays("SELECT * FROM `".$val."`");
							$fields = db_arrays("PRAGMA table_info(".$val.")");
							foreach($fields as $field){
								$field_list[] = $field['name'];
							}
							foreach($rows as $row){
								$comma = "";
								$tabledump = "INSERT INTO `".$to_val."` VALUES(";
								foreach($field_list as $fl){
									if(is_string($row[$fl])){
										$str = addslashes($row[$fl]);
									}else{
										$str = $row[$fl];
									}	
									$tabledump .= $comma."'".$str."'";
									$comma = ",";
								}
								$tabledump .= ");";
								if(!mysql_query($tabledump,$to_conn)){
									$db_error .= $tabledump.' : '.mysql_error($to_conn).'<br>';
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
							$res = mysql_query("SELECT * FROM `".$val."`",$conn);
							$numfields = mysql_num_fields($res);
							while ($row = mysql_fetch_row($res)){
								$comma = "";
								$tabledump = "INSERT INTO `".$to_val."` VALUES(";
								for($i = 0; $i < $numfields; $i++){
									if(is_string($row[$i])){
										$str = addslashes($row[$i]);
									}else{
										$str = $row[$i];
									}
									$tabledump .= $comma."'".$str."'";
									$comma = ",";
								}
								$tabledump .= ");";
								if(!mysql_query($tabledump,$to_conn)){
									$db_error .= $tabledump.' : '.mysql_error($to_conn).'<br>';
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
							$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
							$res = pg_query("SELECT * FROM \"".$val."\"");
							$numfields = pg_num_fields($res);
							while ($row = pg_fetch_row($res)){
								$comma = "";
								$tabledump = "INSERT INTO `".$to_val."` VALUES(";
								for($i = 0; $i < $numfields; $i++){
									if(is_string($row[$i])){
										$str = addslashes($row[$i]);
									}else{
										$str = $row[$i];
									}
									$tabledump .= $comma."'".$str."'";
									$comma = ",";
									}
									$tabledump .= ");";
									if(!mysql_query($tabledump,$to_conn)){
										$db_error .= $tabledump.' : '.mysql_error($to_conn).'<br>';
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
			$db_str .= '$database_type = \'mysql\';'."\n";
			$db_str .= '$db_left = \''.$to_db_left.'\';'."\n";
			$db_str .= '$db_url = \''.$to_db_url.'\';'."\n";
			$db_str .= '$db_port = \''.$to_db_port.'\';'."\n";
			$db_str .= '$db_name = \''.$to_db_name.'\';'."\n";
			$db_str .= '$db_username = \''.$to_db_username.'\';'."\n";
			$db_str .= '$db_passwd = \''.$to_db_passwd.'\';'."\n";
			$db_str .= '$sqlite_driver = \''.$sqlite_driver.'\';'."\n";
			$db_str .= "?>";
			file_put_contents(SITE_HOME.'inc/db.php',$db_str);
			mysql_close($to_conn);
			if(DATABASE_TYPE == 'sqlite'){
				$db = null;
				unlink(SITE_HOME.'inc/'.$db_name.'.db');
			}
			alert(_t('Database convert successfully!'),'./');
		}	
	}else{
		$message = _t('Please fill out form below.');
	}
?>