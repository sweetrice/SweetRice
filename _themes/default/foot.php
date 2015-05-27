<?php
/**
 * Template Name:Footer section template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
 	defined('VALID_INCLUDE') or die();
	$s_lang[basename($lang,'.php')] = 'selected';
	$s_theme[$theme] = 'selected';
?>
<div id="div_foot">
<?php _e('Copyright');?> © <a href="<?php echo BASE_URL;?>"><?php echo $global_setting['name'];?></a> Powered By <a href="http://www.basic-cms.org">Basic CMS SweetRice</a> 
<a href="<?php echo show_link_rssfeed();?>"><img src="images/xmlrss.png" alt="<?php echo $global_setting['name'].' '._t('RSSFeed');?>"/></a>
<select class="change_lang">
<?php	foreach(getLangTypes() as $key=>$val):?>
<option value="<?php echo $key;?>" <?php echo $s_lang[$key];?>><?php echo $val;?></option>
<?php endforeach;?>
</select> <select class="change_theme">
<?php foreach(getThemeTypes() as $key=>$val):?>
<option value="<?php echo $key;?>" <?php echo $s_theme[$key];?>><?php echo $val;?></option>
<?php endforeach;?>
</select>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.change_theme').bind('change',function(){
			doTheme(_(this).val());
		});
		_('.change_lang').bind('change',function(){
			doLang(_(this).val());
		});
	});
//-->
</script>
</body>
</html>