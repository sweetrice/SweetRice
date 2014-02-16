<?php
/**
 * Link management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode=='save'){
	$content = $_POST["content"];
	$old_link = array('src="../'.ATTACHMENT_DIR,'data="../'.ATTACHMENT_DIR,'value="../'.ATTACHMENT_DIR);
	$new_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
	$content = str_replace($old_link,$new_link,$content);
	setOption('links',db_escape($content));
	_goto('./?type=link');
 }else{
	$row = getOption('links');
	$old_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
	$new_link = array('src="'.BASE_URL.ATTACHMENT_DIR,'data="'.BASE_URL.ATTACHMENT_DIR,'value="'.BASE_URL.ATTACHMENT_DIR);
	$row['content'] = str_replace($old_link,$new_link,$row['content']);
	$top_word = LINKS.' '.ADMIN;
	$inc = 'link.php';
 }
 ?>