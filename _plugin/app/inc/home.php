<?php	
/**
 * Template Name:App home page template.
 *
 * @package SweetRice
 * @Default template
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	if(file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}	
?>
<div id="div_center">
<?php echo $myApp->app_nav();?>
<h1 id="app_demo"><?php _e('App Home');?></h1>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>