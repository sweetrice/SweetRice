<?php
/**
 * Attachment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	$search = db_escape($_GET['search']);
	if($search){
		$where = "`file_name` LIKE '%$search%' ";
	}else{
		$where = " ";
	}	
	$data = db_fetch(array(
		'table' => "`".DB_LEFT."_attachment`",
		'field' => "*",
		'where' => $where,
		'order' => "`date` DESC",
		'pager' =>  array('p_link'=>'./?type=attachment'.($search?'&search='.$search:'').'&',
			'page_limit'=>page_limit(null,20),'pager_function' => '_pager')
	));
	$rows = $data['rows'];
	$pager = $data['pager'];
	$top_word = _t('Attachment List');
	$inc = 'attachment.php';
?>