<?php
/**
 * Plugin management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
	$plugin = $_GET['plugin'];
	switch($mode){
		case 'install':
			output_json(plugin_install($plugin));
		break;
		case 'deinstall':
			output_json(plugin_deinstall($plugin));
		break;
		default:
			$top_word = _t('Plugin Admin');
			$inc = 'plugin.php';
	}
 ?>