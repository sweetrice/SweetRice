<?php
/**
 * Post Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$post = db_escape($_GET['post']);
	$cateName = $_GET['cateName'];
	$where = " 1=1 ";
	if($post){
		$where .= " AND UPPER(ps.`sys_name`) = UPPER('$post')";
	}else{
		_404('entry');
	}
	$row = getPostsOne(array('field'=>'ps.*','where'=>$where,'custom_field'=>true,'post_type'=>'show'));
	if(!$row['id']||($row['category'] != 0 && $categories[$row['category']]['link'] != $cateName && $global_setting['url_rewrite'])){
		_404('entry');
	}
	if($row['sys_name'] != $post){
		$canonical = BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);
	}
	if($row['allow_comment']){
		$last_comment = db_array("SELECT `date` FROM `".DB_LEFT."_comment` WHERE `post_id` = '".$row['id']."' ORDER BY `date` DESC ".get_limit_sql(0,1));
		$comment_total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_comment` WHERE `post_id` = '".$row['id']."'");
		$comment_link = show_link_comment(($row['sys_name']?$row['sys_name']:$row['date']),null);
	}
	$att_rows = db_arrays("SELECT `file_name`,`downloads`,`id` FROM `".DB_LEFT."_attachment` WHERE `post_id` = '".$row['id']."'");//attachment list
	db_query("UPDATE `".DB_LEFT."_posts` AS ps SET `views` = `views`+1  WHERE ".$where);
	$category = $row['category'];
	$relate_entry = db_arrays("SELECT `id`,`name`,`title`,`date`,`sys_name`,`category` FROM `".DB_LEFT."_posts` WHERE `category` = '".$row['category']."' AND `id` != '".$row['id']."' AND `in_blog` = '1' ORDER BY `views` ASC ".get_limit_sql(0,$global_setting['nums_setting']['postRelated']));
	$title = ($row['title']?$row['title'].' - ':'').$global_setting['name'];
	$description = $row['description'];
	$keywords = $row['keyword'];
	if($global_setting['pagebreak']){
		$post_contents = explode('<!-- pagebreak -->',$row['body']);
		if(count($post_contents) > 1){
			$pager_pagebreak = pager_pagebreak(count($post_contents),$row);
			$title = ($row['title']?$row['title'].' '._t('Pagebreak').' '.$pager_pagebreak['page'].' - ':'').$global_setting['name'];
			$description = pagebreak_description($post_contents[$pager_pagebreak['page'] - 1]);
			$keywords = $row['keyword'];
		}
	}
	$top_word = $row['title'];
	$rssfeed = '<link rel="alternate" type="application/rss+xml" title="'.$row['name'].' '.ENTRY_RSSFEED.'" href="'.show_link_page_xml($row['sys_name']).'" />';
	if($row['template']&&$row['template']!='default'&&file_exists($row['template'])){
		$inc = $row['template'];
	}else{
		$inc = THEME_DIR.$page_theme['entry'];
	}
	$last_modify = max($row['date'],$last_comment['date'],filemtime($inc));
?>