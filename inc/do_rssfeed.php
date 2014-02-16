<?php
/**
 * RSSFeed Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$type = $_GET["type"];
	if($type=='entry'){
		$post = $_GET["post"];
		$sql = "WHERE `in_blog` = '1' ";
		if($post){
			$sql .= " AND `sys_name` = '$post'";
		}else{
			_404('entry');
		}
		$row = db_array("SELECT `id`,`sys_name`,`category`,`name`,`keyword`,`description`,`body`,`views`,`date`,`tags`,`allow_comment` FROM `".DB_LEFT."_posts` ".$sql);
		if(!$row['id']){
			_404('entry');
		}
		if($row['allow_comment']){
			$comments = db_arrays("SELECT `name` ,`website`, `info` ,`date` FROM `".DB_LEFT."_comment` WHERE `post_id` = '".$row['id']."'");
		}
		outputHeader($row['date']);
		include("inc/rssfeed_entry.php");
	}elseif($type=='category'){
		$cat = $_GET["c"];
		if(empty($cat)){
			_404('category');
		}
		$cat_id = intval($categoriesByLink[$cat]);
		if($cat_id==0){
			_404('category');
		}		
		$rows = db_arrays("SELECT `sys_name`,`category`,`name`,`body`,`date` FROM `".DB_LEFT."_posts` WHERE `category` = '$cat_id' AND `in_blog` = '1' ORDER BY `id` DESC ".get_limit_sql(0,$global_setting['nums_setting']['postRssfeed']));
		$last_modify = pushDate(array($rows));
		outputHeader($last_modify);
		include("inc/rssfeed_category.php");
	}else{
		$rows = db_arrays("SELECT `name`,`sys_name`,`category`,`body`,`date` FROM `".DB_LEFT."_posts`  WHERE `in_blog` = '1' ORDER by `id` DESC ".get_limit_sql(0,$global_setting['nums_setting']['postRssfeed']));
		$last_modify = pushDate(array($rows));
		outputHeader($last_modify);
		include("inc/rssfeed.php");	
	}
	exit();		
?>