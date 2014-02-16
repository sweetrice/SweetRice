<?php
/**
 * Attachment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	$search = db_escape($_GET["search"]);
	if($search){
		$sql = " WHERE `file_name` LIKE '%$search%' ";
	}else{
		$sql = " ";
	}
	$total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_attachment`".$sql);
	$p_link = './?type=attachment'.($search?'&search='.$search:'').'&';
	$page_limit = 20;
	$pager = _pager($total,$page_limit,$p_link);
	$rows = db_arrays("SELECT * FROM `".DB_LEFT."_attachment` ".$sql." ORDER BY `date` DESC ".get_limit_sql($pager['page_start'],$page_limit));
	$top_word = ATTACHMENT.' '.LISTS;
	$inc = 'attachment.php';
?>