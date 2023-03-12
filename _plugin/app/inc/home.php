<?php	
/**
 * Template Name:App home page template.
 *
 * @package SweetRice
 * @Default template
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	if(is_array($page_theme) && file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}	
?>
<div id="div_center">
<?php echo $myApp->app_nav();?>
<h1 id="app_demo"><?php _e('App Home');?></h1>
</div>
<?php
	if(is_array($page_theme) && file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>