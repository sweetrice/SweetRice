<?php
/**
 * Plugin management.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
if($mode == 'install'){
	plugin_install($plugin);
}elseif($mode == 'deinstall'){
	plugin_deinstall($plugin);
}
?>