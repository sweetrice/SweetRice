<?php
/**
 * App plugin.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	define('APP_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	$app_mode = $_GET['app_mode'];
	include_once(APP_DIR.'shareFunction.php');
	$last_modify = time();
	switch($app_mode){
		default:
			$inc = APP_DIR.'inc/home.php';
	}
?>