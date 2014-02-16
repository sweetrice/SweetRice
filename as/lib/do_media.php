<?php
/**
 * Media management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
define('MEDIA_DIR',SITE_HOME.ATTACHMENT_DIR);
define('STRLEN_MEDIA_DIR',strlen(MEDIA_DIR));
$mode = $_GET["mode"];
if($mode == 'delete'){
	$no = $_POST["no"];
	$f = MEDIA_DIR.js_unescape($_POST["file"]);
	if(is_dir($f)&&substr($f,0,STRLEN_MEDIA_DIR)==MEDIA_DIR){
		if(@rmdir($f)){
			$do_delete = true;
		}
	}elseif(is_file($f)&&substr($f,0,STRLEN_MEDIA_DIR)==MEDIA_DIR){
		if(@unlink($f)){
			$do_delete = true;
		}
	}else{
		output_json(array('status'=>'0','id'=>$f,'no'=>$no,'data'=>MEDIA_NOEXISTS_TIP));
	}
	if($do_delete){
		output_json(array('status'=>'1','id'=>js_unescape($_POST["file"]),'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(MEDIA,js_unescape($_POST["file"])))));
	}else{
		output_json(array('status'=>'0','id'=>js_unescape($_POST["file"]),'no'=>$no,'data'=>FAILED));
	}
}elseif($mode == 'mkdir'){
	$parent_dir = file_exists(MEDIA_DIR.$_POST["parent_dir"])?MEDIA_DIR.$_POST["parent_dir"]:MEDIA_DIR;
	$referrer = $_POST["referrer"];
	$new_dir = $_POST["new_dir"];
	if(!is_dir($parent_dir.$new_dir)){
		mkdir($parent_dir.$new_dir);
	}
_goto('./?type=media&referrer='.$referrer.'&dir='.substr($parent_dir.$new_dir.'/',STRLEN_MEDIA_DIR));
}elseif($mode == 'upload'){
	$_POST["dir_name"] = str_replace('../','',$_POST["dir_name"]);
	$dest_dir = file_exists(MEDIA_DIR.$_POST["dir_name"])?MEDIA_DIR.$_POST["dir_name"]:MEDIA_DIR;
	upload_($_FILES['upload'],$dest_dir,$_FILES['upload']['name'],null);
	_goto($_SERVER["HTTP_REFERER"]);
}else{
	$_dir = MEDIA_DIR.$_GET["dir"];
	if($_dir&&file_exists($_dir)&&substr($_dir,0,STRLEN_MEDIA_DIR)==MEDIA_DIR){
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
	$keyword = $_GET["keyword"];
	if(is_dir($_open_dir)){
		$d = dir($_open_dir);
		while (false !== ($entry = $d->read())) {
		 if($entry!='.'&&$entry!='..'){
			 if($keyword){
				 if(strpos($entry,$keyword)!==false){
					$tmp = array('name'=>$entry,'type'=>(is_dir($_open_dir.$entry)?'dir':sr_file_type($_open_dir.$entry)),'date'=>date('M,d,Y',filectime($_open_dir.$entry)),'link'=>$open_dir.$entry);
					$files[]  = $tmp;						
				 }
			 }else{
				$tmp = array('name'=>$entry,'type'=>(is_dir($_open_dir.$entry)?'dir':sr_file_type($_open_dir.$entry)),'date'=>date('M,d,Y',filectime($_open_dir.$entry)),'link'=>$open_dir.$entry);
				$files[]  = $tmp;				
			 }
		 }
	}
	$d->close();
		
}
	$referrer = $_GET["referrer"];
	$total = count($files);
	$page_limit = 15;
	$p_link = './?type=media&referrer='.$referrer.'&dir='.$open_dir.'&'.($keyword?'keyword='.$keyword.'&':'');
	$pager = _pager($total,$page_limit,$p_link);				
	include("lib/media.php");
	exit();
}
 ?>