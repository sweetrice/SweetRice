<?php
/**
 * Comment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode=='delete'){
	$id = intval($_POST["id"]);
	$no = $_POST["no"];
	db_query("DELETE FROM `".DB_LEFT."_comment` WHERE `id` = '$id'");	 
	output_json(array('status'=>'1','id'=>$id,'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(COMMENT,$id))));
 }elseif($mode=='view'){
		$id = intval($_GET["id"]);
		$commets = db_array("SELECT * FROM `".DB_LEFT."_comment` WHERE `id` = '$id' ");
		$top_word = REPLY.' '.COMMENT;
		$old_info = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
		$new_info = array('src="'.BASE_URL.ATTACHMENT_DIR,'data="'.BASE_URL.ATTACHMENT_DIR,'value="'.BASE_URL.ATTACHMENT_DIR);
		$info = str_replace($old_info,$new_info,$commets['info']);
		$inc = 'view_comment.php';
 }elseif($mode=='reply'){
		$id = intval($_POST["id"]);
		$old_info = array('src="'.SITE_URL.ATTACHMENT_DIR,'data="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.ATTACHMENT_DIR);
		$new_info = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
		$info = str_replace($old_info,$new_info,$_POST["info"]);
		$info = db_escape($info);
		if($info){
			db_query("UPDATE `".DB_LEFT."_comment` SET `info` = '$info',`reply_date` = '".time()."' WHERE `id` = '$id'");
		}
		_goto('./?type=comment&mode=view&id='.$id);
 }elseif($mode=='bulk'){
	$plist = $_POST["plist"];
	if(count($plist)){
		$ids = implode(',',$plist);
		db_query("DELETE FROM `".DB_LEFT."_comment` WHERE `id` IN ($ids)");
	}
	_goto($_SERVER["HTTP_REFERER"]);
 }else{
	$search = db_escape($_GET["search"]);
	if($search){
		$where = " `info` LIKE '%$search%' ";
	}else{
		$where = " ";
	}
	
	$p_link = './?type=comment'.($search?'&search='.$search:'').'&';
	$page_limit = 30;
	$data = db_fetch(array(
		'table' => "`".DB_LEFT."_comment`",
		'field' => "*",
		'where' => $where,
		'order' => "`date` DESC",
		'pager' => array('p_link'=>$p_link,'page_limit'=>$page_limit),
		'pager_function'=>'_pager'
	));
	$rows = $data['rows'];
	$pager = $data['pager'];
	$top_word = COMMENT.' '.LISTS;
	$inc = 'comment.php';
 }
?>