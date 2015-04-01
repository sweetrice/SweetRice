<?php
/**
 * App Plugin Home template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	define('APP_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	include(APP_DIR.'shareFunction.php');
	$app_mode = $_GET['app_mode'];
	$app_actions = $myApp->app_actions();
	$has_action = false;
	if($app_mode && is_array($app_actions[$app_mode])){
		if($app_actions[$app_mode]['file'] && file_exists(APP_DIR.'inc/'.$app_actions[$app_mode]['file'])){
			include(APP_DIR.'inc/'.$app_actions[$app_mode]['file']);
			$has_action = true;
		}elseif(file_exists(APP_DIR.'inc/do_'.$app_mode.'.php')){
			include(APP_DIR.'inc/do_'.$app_mode.'.php');
			$has_action = true;
		}
	}
	if(!$has_action){
		include(APP_DIR.'inc/do_main.php');
	}
	if($app_inc){
		$plugin_page[] = APP_DIR.'inc/nav.php';	
		$plugin_page[] = APP_DIR.'inc/'.$app_inc;
	}		
?>