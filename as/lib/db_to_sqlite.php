<?php
/**
 * Convert Database to Sqlite.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.5
 */
 defined('VALID_INCLUDE') or die();
	$to_db_name = $_POST['to_db_name'];
	$to_db_left = $_POST['to_db_left'];
	if(DATABASE_TYPE == 'sqlite' && $to_db_name == $db_name && $to_db_left == DB_LEFT){
		output_json(array('status'=>1,'status_code'=>_t('Database convert successfully!')));
	}
	$tablelist = $_POST['tablelist'];
	if($to_db_name && $to_db_left && $tablelist){
		$plugin_sql = array();
		$plugin_list = pluginList();
		foreach($plugin_list AS $plugin_config){
			if(file_exists(SITE_HOME.'_plugin/'.$plugin_config['directory'].'/plugin_config.php') && $plugin_config['installed']){
				if($plugin_config['install_sqlite']){
					$plugin_sql[$plugin_config['name']] = SITE_HOME.'_plugin/'.$plugin_config['directory'].'/'.$plugin_config['install_sqlite'];
				}
			}
		}
		$dbname = SITE_HOME.'inc/'.$to_db_name.'.db';
		$GLOBALS['to_db_lib'] = new sqlite_lib(array('name'=>$dbname));
		if(!$GLOBALS['to_db_lib']->stat()){
			$error_db = true;
		}else{
			$sql = file_get_contents('lib/app_sqlite.sql');
			$sql = str_replace('%--%',$to_db_left,$sql);
			$sql = explode(';',$sql);
			foreach($sql as $key=>$val){
				if(trim($val)){
					$error = $GLOBALS['to_db_lib']->query($val);
					if($error){
						$message .= $val.' : '.$error.'<br>';
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
							$error = $GLOBALS['to_db_lib']->query($val);
							if($error){
								$message .= $val.' : '.$error.'<br>';
								break;
							}
						}
					}	
			}
			$db_error = null;
			switch(DATABASE_TYPE){
				case 'sqlite':
					foreach($tablelist as $val){
						$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
						$field_list = array();
						$rows = db_arrays("SELECT * FROM `".$val."`");
						$fields = db_arrays("PRAGMA table_info(\"".$val."\")");
						foreach($fields as $field){
							$field_list[] = $field['name'];
						}
						foreach($rows as $row){
							$comma = "";
							$tabledump = "INSERT INTO \"".$to_val."\" VALUES(";
							foreach($field_list as $fl){
								if(is_string($row[$fl])){
									$str = sqlite_escape_string($row[$fl]);
								}else{
									$str = $row[$fl];
								}	
								$tabledump .= $comma."'".$str."'";
								$comma = ",";
							}
							$tabledump .= ");";
							$error = $GLOBALS['to_db_lib']->query($tabledump);
							if($error){
								$db_error .= $tabledump.' : '.$error.'<br>';
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
						$res = $GLOBALS['db_lib']->query("SELECT * FROM `".$val."`");
						$numfields = $GLOBALS['db_lib']->num_fields($res);
						while ($row = $GLOBALS['db_lib']->fetch_row($res)){
							$comma = "";
							$tabledump = "INSERT INTO \"".$to_val."\" VALUES(";
							for($i = 0; $i < $numfields; $i++){
								if(is_string($row[$i])){
									$str = sqlite_escape_string($row[$i]);
								}else{
									$str = $row[$i];
								}
								$tabledump .= $comma."'".$str."'";
								$comma = ",";
							}
							$tabledump .= ");";
							$error = $GLOBALS['to_db_lib']->query($tabledump);
							if($error){
								$db_error .= $tabledump.' : '.$error.'<br>';
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
						$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
						$res = $GLOBALS['db_lib']->query("SELECT * FROM \"".$val."\"");
						$numfields = $GLOBALS['db_lib']->num_fields($res);
						while ($row = $GLOBALS['db_lib']->fetch_row($res)){
							$comma = "";
							$tabledump = "INSERT INTO \"".$to_val."\" VALUES(";
							for($i = 0; $i < $numfields; $i++){
								if(is_string($row[$i])){
									$str = sqlite_escape_string($row[$i]);
								}else{
									$str = $row[$i];
								}
								$tabledump .= $comma."'".$str."'";
								$comma = ",";
							}
							$tabledump .= ");";
							$error = $GLOBALS['to_db_lib']->query($tabledump);
							if($error){
								$db_error .= $tabledump.' : '.$error.'<br>';
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
			$db_str = "<?php \n";
			$db_str .= '$database_type = \'sqlite\';'."\n";
			$db_str .= '$db_left = \''.$to_db_left.'\';'."\n";
			$db_str .= '$db_name = \''.$to_db_name.'\';'."\n";
			$db_str .= "?>";		
			file_put_contents(SITE_HOME.'inc/db.php',$db_str);
			if(DATABASE_TYPE == 'sqlite' && $to_db_name != $db_name){
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