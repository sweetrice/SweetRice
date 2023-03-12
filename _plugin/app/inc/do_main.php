<?php
/**
 * App main management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	$app_links = $myApp->app_links();
	$links = array();
	foreach($app_links as $key=>$val){
		ksort($val);
		$linkRow = db_array("SELECT `url`,`lid` FROM `".DB_LEFT."_links` WHERE `plugin` = '".THIS_APP."' AND `request` = '".serialize($val)."'");
		$links[$key] = $linkRow;
	}
	$app_inc = 'main.php';
?>