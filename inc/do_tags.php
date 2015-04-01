<?php
/**
 * Tags Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$tag = js_unescape($_GET['tag']);
	if(strpos($tag,'+') !== false){
		_301(str_replace('+','%20',$_SERVER['REQUEST_URI']));
	}
	if(empty($tag)){
		_404('tags');
	}
	$tag_posts = getOption('tag_posts');
	if($tag_posts['content']){
		$tag_posts = unserialize($tag_posts['content']);
	}else{
		$tag_posts = array();
	}
	if(!count($tag_posts[$tag])){
		_404('tags');
	}
	$where = " ps.`id` IN(".implode(',',$tag_posts[$tag]).")  AND ps.`in_blog` = '1' AND ip.`item_type` = 'post' ";
	$post_output = $page_theme['post_output'] && file_exists(THEME_DIR.$page_theme['post_output'])?THEME_DIR.$page_theme['post_output']:false;
	$total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` AS ps LEFT JOIN `".DB_LEFT."_item_plugin` as ip ON ps.`id` = ip.`item_id` WHERE ".$where); 
	if($total==0){
		_404('tags');
	}
	$page_limit = $global_setting['nums_setting']['postTag'];
	$m = $_GET['m'];
	if($m=='pins'){
		$pins_num = $global_setting['nums_setting']['postPins'];
		$p = max(1,intval($_GET['p']));
		$moreNum = max(1,intval($_GET['moreNum']));
		$page_start = $p * $page_limit + ($moreNum-1)*$pins_num;
		if($page_start + $pins_num >= $total){
			$plist['isMax'] = 1;
		}else{
			$plist['isMax'] = 0;
		}
		$plist['body'] = '';
		
		$data = getPosts(array(
			'table' => "`".DB_LEFT."_posts` AS ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ps.`id` = ip.`item_id` ",
			'where' => $where." AND ps.`in_blog` = '1' AND ip.`item_type` = 'post' ",
			'order' => "ps.`id` DESC ",
			'limit' => array($page_start,$page_limit),
			'custom_field' => true
		));
		foreach($data['rows'] as $val){
			$plist['body'] .= _posts($val,$post_output);
		}
		exit(json_encode($plist));
	}
	$p_link = show_link_tag($tag);
	$pager = pager($total,$page_limit,$p_link);
	if($pager['outPage']){
		_404('tags');
	}
	$data = getPosts(array(
		'table' => "`".DB_LEFT."_posts` AS ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ps.`id` = ip.`item_id` ",
		'where' => $where." AND ps.`in_blog` = '1' AND ip.`item_type` = 'post' ",
		'order' => "ps.`id` DESC ",
		'pager' => array('p_link'=>show_link_tag($tag),'page_limit'=>$page_limit),
		'custom_field' => true
	));
	$rows = $data['rows'];
	$pager = $data['pager'];
	$no = 0;
	foreach($rows as $row){
		$no += 1;
		if($no < 3 ){
			$post_etc .= $row['name'].',';
		}
	}
	$tag = db_unescape($tag);
	$title = $tag.' - '.$global_setting['name'];
	$description = 	vsprintf(TAG_DESCRIPTION,array(ucfirst($tag),rtrim($post_etc,','),$global_setting['name']));
	$keywords = $tag;
	$inc = THEME_DIR.$page_theme['tags'];
	$last_modify = pushDate(array($rows,array(array('date'=>filemtime($inc)))));
?>