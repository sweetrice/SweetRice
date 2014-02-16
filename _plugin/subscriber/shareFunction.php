<?php
/**
 * Subscriber plugin shareFunction for SweetRice.
 *
 * @package SweetRice
 * @Plugin Subscriber
 * @since 1.3.3
 */
	defined('VALID_INCLUDE') or die();
	define('MEMBER_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	define('THIS_PLUGIN','Subscriber');
	if(defined('DASHABOARD')){
		$lang = $global_setting['lang'];
	}else{
		$lang = themeLang().'.php';
	}
	if($lang&&file_exists(MEMBER_DIR."lang/".$lang)){
		include_once(MEMBER_DIR."lang/".$lang);
	}else{
		include_once(MEMBER_DIR."lang/en-us.php");
	}
?>