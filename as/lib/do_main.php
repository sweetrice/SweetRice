<?php
/**
 * Dashboard template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	$post_total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` ");
	$post_total_pub = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` WHERE `in_blog` ='1' ");
	$cat_total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_category` ");
	$comment_total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_comment`");
  if(file_exists('../inc/lastest_update.txt')){
		$lastest_update = file_get_contents('../inc/lastest_update.txt');
	}	
	$lang = getLangTypes(INCLUDE_DIR.'lang/');
	$themes = getThemeTypes();
	$lang_types = getLangTypes();
	$inc = 'main.php';
 ?>