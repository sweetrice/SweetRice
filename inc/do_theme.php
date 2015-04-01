<?php
/**
 * Theme Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$theme = $_POST['theme'];
	if($theme && is_dir(ROOT_DIR.'_themes/'.$theme)){
		setcookie('theme',$theme,time() + 31536000);
		setcookie('theme_update',time());
		output_json(array('status'=>1));
	}else{
		setcookie('theme','',time()-60);
		setcookie('theme_update',time());
		output_json(array('status'=>1));
	}
?>