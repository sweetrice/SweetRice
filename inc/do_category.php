<?php
/**
 * Category Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$cat = $_GET['c'];
	if(empty($cat)){
		_404('category');
	}
	$cat_id = intval($categoriesByLink[strtolower($cat)]);
	if($cat_id == 0){
		_404('category');
	}
	$post_output = $page_theme['post_output'] && file_exists(THEME_DIR.$page_theme['post_output'])?THEME_DIR.$page_theme['post_output']:false;
	$total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` AS ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ps.`id` = ip.`item_id` WHERE ps.`category` = '".$cat_id."' AND ps.`in_blog` = '1' AND ip.`item_type` = 'post' ");
	$page_limit = $global_setting['nums_setting']['postCategory'];
	$m = $_GET['m'];
	if($m == 'pins'){
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
			'table' => " `".DB_LEFT."_posts` as ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ip.`item_id` = ps.`id`",
			'field' => "ps.*",
			'where' => " ip.`plugin` = '' AND ip.`item_type` = 'post' ",
			'category_ids'=>$cat_id,
			'limit' => array($page_start,$pins_num),'post_type'=>'show')
		);
		foreach($data['rows'] as $val){
			$plist['body'] .= _posts($val,$post_output);
		}
		exit(json_encode($plist));
	}
	if($cat != $categories[$cat_id]['link']){
		$canonical = BASE_URL.show_link_cat($categories[$cat_id]['link'],'');
	}
	$row_cat = db_array("SELECT `parent_id` FROM `".DB_LEFT."_category` WHERE `id` = '".$cat_id."'");
	$data = getPosts(array(
		'table' => " `".DB_LEFT."_posts` as ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ip.`item_id` = ps.`id`",
		'field' => "ps.*",
		'where' => " ip.`plugin` = '' AND ip.`item_type` = 'post' ",
		'category_ids'=>$cat_id,
		'pager' => array('p_link'=>show_link_cat($categories[$cat_id]['link']),'page_limit'=>$page_limit),
		'post_type'=>'show'
	));
	if($data['pager']['outPage']){
		_404('category');
	}
	$pager = $data['pager'];
	$rows = $data['rows'];
	$title = $categories[$cat_id]['title'];
	$description = 	$categories[$cat_id]['description'];
	$keywords = $categories[$cat_id]['keyword'];
	$top_word = $categories[$cat_id]['sort_word'];
	$rssfeed = '<link rel="alternate" type="application/rss+xml" title="'.$categories[$cat_id]['name'].' '.CATEGORY_RSSFEED.'" href="'.show_link_cat_xml($categories[$cat_id]['link']).'" />';			
	if($categories[$cat_id]['template']&&$categories[$cat_id]['template']!='default'&&file_exists($categories[$cat_id]['template'])){
		$inc = $categories[$cat_id]['template'];
	}else{
		$inc = THEME_DIR.$page_theme['category'];
	}
	$last_modify = pushDate(array($rows,array(array('date'=>filemtime($inc)))));
?>