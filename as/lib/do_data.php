<?php
/**
 * Database management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 switch($mode){
	case 'db_backup':
		$form_mode = $_GET['form_mode'];
		$table_list = db_list();
		if($form_mode == 'yes'){
			$_POST['tablelist'] = explode(',',$_POST['tablelist']);
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
			output_json(array('status'=>1,'status_code'=>_t('Your database has been backup successfully!')));
		}
		$top_word = _t('Data Backup');
		$inc = 'db_backup.php';
	break;
	case 'db_converter':
		$form_mode = $_GET['form_mode'];
		$table_list = db_list();
		if($form_mode == 'yes'){
			$totype = $_POST['totype'];
			switch($totype){
				case 'sqlite':
					include('lib/db_to_sqlite.php');
				break;
				case 'mysql':
					include('lib/db_to_mysql.php');
				break;
				case 'pgsql':
					include('lib/db_to_pgsql.php');
				break;
			}
		}
		if(!$totype){
			$totype = 'sqlite';
		}
		$s_totype[$totype] = 'selected';
		$top_word = _t('Data Converter');
		$inc = 'db_converter.php';
	break;
	case 'db_import':
		$db_file = str_replace('/','',$_GET['db_file']);
		$form_mode = $_GET['form_mode'];
		switch(DATABASE_TYPE){
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
					break;
				}
			}
			$import = true;
		}
		if($form_mode == 'bulk'){
			$plist = $_POST['plist'];
			foreach($plist as $val){
				if(file_exists($db_backup_dir.'/'.$val)){
					unlink($db_backup_dir.'/'.$val);
				}
			}
			output_json(array('status'=>'1','status_code'=>vsprintf(_t('%s (%s) has been delete successfully.'),array(_t('Data Backup'),implode(',',$plist)))));
		}elseif($form_mode == 'save' &&		file_exists($db_backup_dir.'/'.$db_file)){
			download_file($db_backup_dir.'/'.$db_file);
		}
		$top_word = _t('Data Import');
		$inc = 'db_import.php';		
	break;
	case 'upload':
		if($_FILES['dbfile']){
			switch(DATABASE_TYPE){
				case 'pgsql':
					$db_backup_dir = SITE_HOME.'inc/pgsql_backup';
				break;
				case 'sqlite':
					$db_backup_dir = SITE_HOME.'inc/sqlite_backup';
				break;
				default:
					$db_backup_dir = SITE_HOME.'inc/mysql_backup';
			}
			upload_($_FILES['dbfile'],$db_backup_dir,$_FILES['dbfile']['name'],false);
			_goto('./?type=data&mode=db_import');
		}
	break;
	case 'db_optimizer':
		$form_mode = $_GET['form_mode'];
		if($form_mode =='yes'){
			$table_list = explode(',',$_POST['tablelist']);
			switch(DATABASE_TYPE){
				case 'sqlite':
					foreach($table_list as $val){
						db_query('vacuum "'.$val.'"');
						if(!db_error()){
							$message .= $val.'<span>'._t('Successfully').'</span>';
						}else{
							$message .= $val.'<span class="failed">'._t('Failed').':'.db_error().'</span>';
						}
					}
				break;
				case 'pgsql':
					foreach($table_list as $val){
						db_query('vacuum analyze '.$val);
						if(!db_error()){
							$message .= $val.'<span>'._t('Successfully').'</span>';
						}else{
							$message .= $val.'<span class="failed">'._t('Failed').'</span>';
						}
					}
				break;
				case 'mysql':
					foreach($table_list as $val){
						db_query('optimize table '.$val);
						if(!db_error()){
							$message .= $val.'<span>'._t('Successfully').'</span>';
						}else{
							$message .= $val.'<span class="failed">'._t('Failed').'</span>';
						}
					}
				break;
			}
			output_json(array('status'=>1,'status_code'=>$message));
		}
		$top_word = _t('Data Optimizer');
		$table_list = db_list();
		$inc = 'db_optimizer.php';
	break;
	case 'sql_execute':
		$form_mode = $_GET['form_mode'];
		if($form_mode == 'yes'){
			$sql_content = str_replace('%--%',DB_LEFT,$_POST['sql_content']);
			if($sql_content){
				$rows = db_arrays($sql_content);
				$message = db_error();
				output_json(array('status'=>!$message?1:0,'status_code'=>!$message?_t('SQL Execute Success'):$message,'rows'=>$rows));
			}
		}
		$top_word = _t('SQL Execute');
		$inc = 'db_sqlexecute.php';
	break;
	case 'transfer':
		$archive_name = 'SweetRice-transfer.zip';
		switch ($_GET['form_type']) {
			case 'pack':
				if(!extension_loaded('zlib') && !extension_loaded('ZZIPlib')){
					alert(_t('Server do not supports ZIP'));
				}
				if(file_exists(ROOT_DIR.$archive_name)){
					unlink(ROOT_DIR.$archive_name);
				}
				$archive_folder = ROOT_DIR;
				$zip = new ZipArchive; 
				if ($zip -> open(ROOT_DIR.$archive_name, ZipArchive::CREATE) === TRUE) 
				{
					$_dir = preg_replace('/[\/]{2,}/', '/', $archive_folder); 
					$dirs = array($_dir); 
					while (count($dirs)) 
					{ 
						$_dir = current($dirs); 
						$zip -> addEmptyDir(str_replace(ROOT_DIR,'',$_dir));
						$d = dir($_dir); 
						while (false !== ($entry = $d->read())) {
							if ($entry != '.' && $entry != '..') 
							{ 
								if (is_file($_dir.$entry)){ 
									$zip -> addFile($_dir.$entry, str_replace(ROOT_DIR,'',$_dir.$entry));
								}
								elseif (is_dir($_dir.$entry)) {
									$dirs[] = $_dir.$entry.'/'; 
								}
							} 
						} 
						$d->close();
						array_shift($dirs); 
					} 
					$zip -> close(); 
					output_json(array('status'=>1));
				 }else 
				 { 
					 output_json(array('status'=>0,'status_code'=>_t('Can\'t create transfer file')));
				 }
			break;
			case 'pack_delete':
				if(file_exists(ROOT_DIR.$archive_name)){
					unlink(ROOT_DIR.$archive_name);
				}
				output_json(array('status'=>1));
			break;
		}
		switch($_POST['transfer_type']){
			case 'download':
				if(file_exists(ROOT_DIR.$archive_name)){
					output_json(array('status'=>1,'url'=>BASE_URL.$archive_name));
				}else{
					output_json(array('status'=>0));
				}
			break;
			case 'online':
				if(!function_exists('ftp_connect')){
					output_json(array('status'=>0,'status_code'=>_t('Server does not supports FTP')));
				}
				if(file_exists(ROOT_DIR.$archive_name) && $_POST['ftp_server']){
					$_POST['ftp_port'] = !$_POST['ftp_port'] ? 21:$_POST['ftp_port'];
					if($_SERVER['SERVER_PORT'] == 443){
						$conn_id = ftp_ssl_connect($_POST['ftp_server'],$_POST['ftp_port']);
					}else{
						$conn_id = ftp_connect($_POST['ftp_server'],$_POST['ftp_port']);
					}
					 
					$login_result = ftp_login($conn_id, $_POST['ftp_user'], $_POST['ftp_password']); 
					if ((!$conn_id) || (!$login_result)) { 
						output_json(array('status'=>0,'status_code'=>_t('Can not connect FTP server')));
					}
					$upload = ftp_put($conn_id,($_POST['ftp_home'] ? rtrim($_POST['ftp_home'],'/').'/':'').$archive_name, ROOT_DIR.$archive_name, FTP_BINARY); 
					if (!$upload) { 
						output_json(array('status'=>0,'status_code'=>_t('Upload failed')));
					}
					output_json(array('status'=>1));
				}else{
					output_json(array('status'=>0,'status_code'=>_t('Missing website data file or invalid FTP option')));
				}
			break;
			default:
				$inc = 'transfer_website.php';
		}
	break;
	default:
		_goto('./');
 }
 ?>
