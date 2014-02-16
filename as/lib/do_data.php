<?php
/**
 * Database management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 switch($mode){
	case 'db_backup':
		$form_mode = $_GET["form_mode"];
		$table_list = db_list();
		if($form_mode == 'yes'){
			$_POST["tablelist"] = explode(',',$_POST["tablelist"]);
			switch(DATABASE_TYPE){
				case 'sqlite':
					include('lib/sqlite_backup.php');
				break;
				case 'pgsql':
					include('lib/pgsql_backup.php');
				break;
				case 'mysql':
					include('lib/mysql_backup.php');
				break;
			}
			output_json(array('status'=>1,'status_code'=>DATABASE_BAKUP_OK));
		}
		$top_word = DATABACKUP;
		$inc = 'db_backup.php';
	break;
	case 'db_converter':
		$form_mode = $_GET["form_mode"];
		$table_list = db_list();
		if($form_mode == 'yes'){
			$totype = $_POST["totype"];
			switch($totype){
				case 'sqlite':
						include("lib/db_to_sqlite.php");
				break;
				case 'mysql':
						include("lib/db_to_mysql.php");
				break;
				case 'pgsql':
						include("lib/db_to_pgsql.php");
				break;
			}
		}
		if(!$totype){
			$totype = 'sqlite';
		}
		$s_totype[$totype] = 'selected';
		$top_word = DATACONVERTER;
		$inc = 'db_converter.php';
	break;
	case 'db_import':
		$db_file = $_GET["db_file"];
		$form_mode = $_GET["form_mode"];
		switch($database_type){
			case 'pgsql':
				$db_backup_dir = SITE_HOME.'inc/pgsql_backup';
			break;
			case 'sqlite':
				$db_backup_dir = SITE_HOME.'inc/sqlite_backup';
			break;
			default:
			$db_backup_dir = SITE_HOME.'inc/mysql_backup';
		}
		if($db_file && file_exists($db_backup_dir.'/'.$db_file) && $form_mode == 'import'){
			$data = include($db_backup_dir.'/'.$db_file);
			foreach($data as $key=>$val){
				$val = str_replace('%--%',DB_LEFT,$val);
				$query = db_query($val);
				if($query){
					$import_error .= $val.' : '.$query.'<br />';
				}
			}
			$import = true;
		}
		if($form_mode == 'delete'){
			$db_file = js_unescape($_POST["db_file"]);
			$no = $_POST["no"];
			if(file_exists($db_backup_dir.'/'.$db_file)){
				unlink($db_backup_dir.'/'.$db_file);
				output_json(array('status'=>'1','id'=>$db_file,'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(DATABACKUP,$db_file))));
			}else{
				output_json(array('status'=>'0','id'=>$db_file,'no'=>$no,'data'=>'DB_BACKUP_NO_EXISTS'));
			}
		}elseif($form_mode == 'bulk'){
			$plist = $_POST["plist"];
			foreach($plist as $val){
				if(file_exists($db_backup_dir.'/'.$val)){
					unlink($db_backup_dir.'/'.$val);
				}
			}
			_goto($_SERVER["HTTP_REFERER"]);
		}elseif($form_mode=='save' &&		file_exists($db_backup_dir.'/'.$db_file)){
			$data = file_get_contents($db_backup_dir.'/'.$db_file);
			ob_end_clean();
			header('Content-Encoding: none');
			header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
			header('Content-Disposition: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="'.$db_file.'"');
			header('Content-Length: '.strlen($data));
			header('Pragma: no-cache');
			header('Expires: 0');
			die($data);
		}
		$top_word = DATAIMPORT;
		$inc = 'db_import.php';		
	break;
	case 'db_optimizer':
		$form_mode = $_GET["form_mode"];
		if($form_mode =='yes'){
			$table_list = explode(',',$_POST["tablelist"]);
			switch($database_type){
				case 'sqlite':
					foreach($table_list as $val){
						if(!db_query("vacuum ".$val)){
							$message .= $val." - ".OPTIMIZE_SUCCESSFULLY.".<br />";
						}else{
							$message .= $val." - ".OPTIMIZE_FAILED.".<br />";
						}
					}
				break;
				case 'pgsql':
					foreach($table_list as $val){
						if(!db_query("vacuum analyze ".$val)){
							$message .= $val." - ".OPTIMIZE_SUCCESSFULLY.".<br />";
						}else{
							$message .= $val." - ".OPTIMIZE_FAILED.".<br />";
						}
					}
				break;
				case 'mysql':
					foreach($table_list as $val){
						if(!db_query("optimize table ".$val)){
							$message .= $val." - ".OPTIMIZE_SUCCESSFULLY.".<br />";
						}else{
							$message .= $val." - ".OPTIMIZE_FAILED.".<br />";
						}
					}
				break;
			}
			output_json(array('status'=>1,'status_code'=>$message));
		}
		$top_word = DATAOPTIMIZER;
		$table_list = db_list();
		$inc = 'db_optimizer.php';
	break;
	case 'sql_execute':
		$form_mode = $_GET["form_mode"];
		if($form_mode == 'yes'){
			$sql_content = $_POST["sql_content"];
			$message = db_query($sql_content);
			output_json(array('status'=>!$message?1:0,'status_code'=>!$message?SQL_EXECUTE_SUCCESS:$message));
		}
		$top_word = SQL_EXECUTE;
		$inc = 'db_sqlexecute.php';
	break;
	default:
		_goto('./');
 }
 ?>