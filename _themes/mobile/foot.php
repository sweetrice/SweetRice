<?php
/**
 * Template Name:Footer section template.
 *
 * @package SweetRice
 * @Wblog template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
?>
<div id="div_foot">
<?php echo COPYRIGHT;?> © <a href="<?php echo BASE_URL;?>"><?php echo $global_setting['name'];?></a> Powered By <a href="http://www.basic-cms.org">Basic CMS SweetRice</a> 
<a href="<?php echo show_link_rssfeed();?>"><img src="images/xmlrss.png" alt="<?php echo $global_setting['name'].' '.RSSFEED;?>"></a>
<select onchange="doLang(this.options[this.selectedIndex].value);">
<?php foreach($lang_types as $key=>$val):?>
<option value="<?php echo $key;?>" <?php echo $s_lang[$key];?>><?php echo $val;?></option>
<?php endforeach;?>
</select> <select onchange="doTheme(this.options[this.selectedIndex].value);">
<?php foreach($theme_types as $key=>$val):?>
<option value="<?php echo $key;?>" <?php echo $s_theme[$key];?>><?php echo $val;?></option>
<?php endforeach;?>
</select>
</div>
</body>
</html>