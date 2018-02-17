<?php
/**
 * Media management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
define('MEDIA_DIR',ROOT_DIR.ATTACHMENT_DIR);
define('STRLEN_MEDIA_DIR',strlen(MEDIA_DIR));
$mode = $_GET['mode'];
switch($mode){
	case 'delete':
		$no = $_POST['no'];
		$f = MEDIA_DIR.js_unescape($_POST['file']);
		if(is_dir($f)&&substr($f,0,STRLEN_MEDIA_DIR) == MEDIA_DIR){
			if(@rmdir($f)){
				$do_delete = true;
			}else{
			output_json(array('status'=>'0','id'=>$f,'no'=>$no,'status_code'=>_t('Not exists or not empty.')));
		}
		}elseif(is_file($f)&&substr($f,0,STRLEN_MEDIA_DIR) == MEDIA_DIR){
			if(@unlink($f)){
				$do_delete = true;
			}
		}else{
			output_json(array('status'=>'0','id'=>$f,'no'=>$no,'status_code'=>_t('Not exists or not empty.')));
		}
		if($do_delete){
			output_json(array('status'=>'1','id'=>js_unescape($_POST['file']),'no'=>$no,'status_code'=>vsprintf(_t('%s (%s) has been delete successfully.'),array(_t('Media'),js_unescape($_POST['file'])))));
		}else{
			output_json(array('status'=>'0','id'=>js_unescape($_POST['file']),'no'=>$no,'status_code'=>_t('Failed')));
		}
	break;
	case 'mkdir':
		$parent_dir = file_exists(MEDIA_DIR.$_POST['parent_dir'])?MEDIA_DIR.$_POST['parent_dir']:MEDIA_DIR;
		$referrer = $_POST['referrer'];
		$new_dir = $_POST['new_dir'];
		if(!is_dir($parent_dir.$new_dir)){
			mkdir($parent_dir.$new_dir);
		}
		_goto('./?type=media&referrer='.$referrer.'&dir='.substr($parent_dir.$new_dir.'/',STRLEN_MEDIA_DIR));
	break;
	case 'upload':
		$_POST['dir_name'] = str_replace('../','',$_POST['dir_name']);
		$dest_dir = file_exists(MEDIA_DIR.$_POST['dir_name'])?MEDIA_DIR.$_POST['dir_name']:MEDIA_DIR;		if(is_array($_FILES['upload']['name'])){
			foreach($_FILES['upload']['name'] as $key=>$val){
				$tmp = array(
					'name' => $_FILES['upload']['name'][$key],
					'type' => $_FILES['upload']['type'][$key],
					'tmp_name' => $_FILES['upload']['tmp_name'][$key],
					'error' => $_FILES['upload']['error'][$key],
					'size' => $_FILES['upload']['size'][$key]
				);
				if(substr($tmp['name'],-4) == '.zip' && $_POST['unzip']){
					extractZIP($tmp['tmp_name'],$dest_dir,true);
				}else{
					upload_($tmp,$dest_dir,$tmp['name'],null);
				}
			}
		}else{
			if(substr($_FILES['upload']['name'],-4) == '.zip' && $_POST['unzip']){
				extractZIP($_FILES['upload']['tmp_name'],$dest_dir,true);
			}else{
				upload_($_FILES['upload'],$dest_dir,$_FILES['upload']['name'],null);
			}
		}
		_goto($_SERVER['HTTP_REFERER']);
	break;
	default:
		$_dir = MEDIA_DIR.$_GET['dir'];
		if($_dir && file_exists($_dir) && substr($_dir,0,STRLEN_MEDIA_DIR) == MEDIA_DIR){
			$tmp = explode('/',substr($_dir,0,-1));
			if(count($tmp)){
				$parent = str_replace(end($tmp).'/','',$_dir);
				$parent = substr($parent,STRLEN_MEDIA_DIR);
			}
			$_open_dir = $_dir;
		}else{
			$_open_dir = MEDIA_DIR;
		}
		$open_dir = substr($_open_dir,STRLEN_MEDIA_DIR);
		$keyword = $_GET['keyword'];
		if(is_dir($_open_dir)){
			$tmp_list = array();
			$tmp_data = array();
			$d = dir($_open_dir);
			while (false !== ($entry = $d->read())) {
			 if($entry!='.'&&$entry!='..'){
				 if($keyword){
					 if(strpos($entry,$keyword)!==false){
						$tmp = array('name'=>$entry,'type'=>(is_dir($_open_dir.$entry)?'dir':sr_file_type($_open_dir.$entry)),'date'=>date('M,d,Y',filemtime($_open_dir.$entry)),'link'=>$open_dir.$entry);
						if(!in_array(filemtime($_open_dir.$entry),$tmp_list)){
							$files[filemtime($_open_dir.$entry)]  = $tmp;
							$tmp_list[] = filemtime($_open_dir.$entry);
						}else{
							$tmp_data[filemtime($_open_dir.$entry)][] = $tmp;
						}						
					 }
				 }else{
					$tmp = array('name'=>$entry,'type'=>(is_dir($_open_dir.$entry)?'dir':sr_file_type($_open_dir.$entry)),'date'=>date('M,d,Y',filemtime($_open_dir.$entry)),'link'=>$open_dir.$entry);
					if(!in_array(filemtime($_open_dir.$entry),$tmp_list)){
						$files[filemtime($_open_dir.$entry)]  = $tmp;
						$tmp_list[] = filemtime($_open_dir.$entry);
					}else{
						$tmp_data[filemtime($_open_dir.$entry)][] = $tmp;
					}			
				 }
			 }
			}
			$d->close();
			
		}	
		krsort($files);
		foreach($files as $key=>$val){
			$_files[] = $val;
			foreach($tmp_data[$key] as $v){
				$_files[] = $v;
			}
		}
		$files = $_files;
		$referrer = $_GET['referrer'];
		$total = count($files);
		$page_limit = page_limit(null,15);
		$p_link = './?type=media&referrer='.$referrer.'&dir='.$open_dir.'&'.($keyword?'keyword='.$keyword.'&':'');
		$pager = _pager($total,$page_limit,$p_link);
		define('UPLOAD_MAX_FILESIZE',ini_get('upload_max_filesize'));
		$token = $_SESSION['_form_token_'];;
		include('lib/media.php');
		exit();
}
?>