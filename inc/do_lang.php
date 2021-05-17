<?php
/**
 * Language Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$lang = $_POST['lang'];
	if(file_exists(LANG_DIR.$lang.'.php')){
		setcookie('lang',$lang,time() + 31536000,BASE_DIR);
		setcookie('lang_update',time(),0,BASE_DIR);
		output_json(array('status'=>1));
	}else{
		output_json(array('status'=>0));
	}
?>