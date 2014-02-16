<?php
/**
 * Plugin management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	$mode = $_GET["mode"];
	$plugin = $_GET["plugin"];
	if($mode != 'install' && $mode != 'deinstall'){
		$inc = 'plugin.php';
	}else{
		$inc = 'plugins.php';
	}
	$top_word = PLUGIN.' '.ADMIN;
 ?>