<?php
/**
 * Site management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<div id="setting_body">
<label class="setting_label<?php echo $_COOKIE['setting_tab'] != 2?' current':'';?>" for="1"><?php _e('System Setting');?></label><label class="setting_label<?php echo $_COOKIE['setting_tab'] == 2?' current':'';?>" for="2"><?php _e('Website setting');?></label>
<form method="post" action="./?type=setting&mode=save" enctype="multipart/form-data">
<input type="hidden" name="old_passwd" value="<?php echo $global_setting['passwd'];?>"/>
<input type="hidden" name="old_logo" value="<?php echo $global_setting['logo'];?>"/>
<div id="setting_1" <?php echo $_COOKIE['setting_tab'] != 2?'class="show"':'class="hidden"';?>>
<fieldset><legend><?php echo _t('Dashboard').' '._t('Language');?></legend>
<select name="lang">
	<option value="" ><?php _e('Default');?></option>
<?php
	foreach($lang as $key=>$val){
?>
<option value="<?php echo $key;?>.php" <?php echo $dashboard_lang[trim($key).'.php'];?> ><?php echo $val;?></option>
<?php
	}
?>
</select></fieldset>
<fieldset><legend><?php _e('Webmaster');?></legend>
<input type="text" name="author" value="<?php echo $global_setting['author'];?>"></fieldset>
<fieldset><legend><?php _e('Dashboard Directory');?></legend>
<input type="text" name="dashboard_dirs" value="<?php echo DASHBOARD_DIR;?>" <?php echo SITE_HOME == ROOT_DIR?'':'readonly';?>> 
<span class="tip"><?php _e('Change Dashboard Directory');?></span>
</fieldset>
<fieldset><legend><?php _e('Database Setting');?></legend>
<?php
	if(DATABASE_TYPE == 'sqlite'){
?>
<ul>
<li><?php _e('Database');?> : SQLite</li>
</ul>
<?php
	}else{
?>
<ul>
<li><?php _e('Database');?> : <?php echo DATABASE_TYPE?DATABASE_TYPE:'MySQL';?></li>
<li><?php _e('Database Host');?> : <?php echo $db_url?$db_url:'localhost';?></li>
<li><?php _e('Database Port');?> : <?php echo $db_port;?></li>
<li><?php _e('Database Account');?> : <?php echo $db_username;?></li>
<li><?php _e('Database Password');?> : <?php echo $db_passwd;?></li>
</ul>
<?php
	}
?>
</fieldset>
<fieldset><legend><?php _e('Database Name');?></legend>
<?php echo $db_name;?></fieldset>
<fieldset><legend><?php _e('Database Prefix');?></legend>
<?php echo DB_LEFT;?></fieldset>
<fieldset><legend><?php _e('Administrator Priority');?></legend>
<input type="radio" name="admin_priority" value="0" <?php echo !$global_setting['admin_priority']?'checked':'';?>/> <?php _e('SweetRice first');?> 
<input type="radio" name="admin_priority" value="1" <?php echo $global_setting['admin_priority'] == 1?'checked':'';?>/> <?php _e('SweetRice only');?> 
<input type="radio" name="admin_priority" value="2" <?php echo $global_setting['admin_priority'] == 2?'checked':'';?>/> <?php _e('Member plugn first');?> 
<input type="radio" name="admin_priority" value="3" <?php echo $global_setting['admin_priority'] == 3?'checked':'';?>/> <?php _e('Member plugn only');?>
<div class="tip"><?php _e('Do not change this unless member plugin installed,and must be restore it before deinstall member plugin');?></div>
</fieldset>
<fieldset><legend><?php _e('Administrator');?></legend>
<input type="text" name="admin" value="<?php echo $global_setting['admin'];?>"></fieldset>
<fieldset><legend><?php _e('Administrator Password');?></legend>
<input type="password" name="passwd"></fieldset>
<fieldset><legend><?php _e('Administrator Email');?></legend>
<input type="text" name="admin_email" value="<?php echo $global_setting['admin_email'];?>"/>
</fieldset>
<fieldset><legend><?php _e('Timezone');?></legend>
<select name="timeZone">
<option value=""><?php _e('Choose Timezone');?></option>
<?php
	$tzs = include('timezone.php');
	$s_tzs[$global_setting['timeZone']] = 'selected';
	foreach($tzs as $key=>$val){
?>
<option value="<?php echo trim($key);?>" <?php echo $s_tzs[$key];?>><?php echo trim($key);?></option>
<?php
	}
?>
</select>
</fieldset>
</div>
<div id="setting_2" <?php echo $_COOKIE['setting_tab'] == 2?'class="show"':'class="hidden"';?>>
<fieldset><legend><?php echo _t('Theme').' '._t('Language');?></legend>
<select name="theme_lang">
	<option value=""><?php _e('Default');?></option>
<?php
	foreach($lang_types as $key=>$val){
?>
<option value="<?php echo $key;?>" <?php echo $s_lang[$key];?>><?php echo $val;?></option>
<?php
	}
?>
</select></fieldset>
<fieldset><legend><?php _e('Site Name');?></legend>
<input type="text" name="name" value="<?php echo $global_setting['name'];?>">
</fieldset>
<fieldset><legend>Logo</legend>
<img src="<?php echo $global_setting['logo']?'../'.ATTACHMENT_DIR.$global_setting['logo']:'../images/logo.png';?>">
<input type="file" name="logo" class="input_text_tip" > <span class="tip"><?php echo _t('Max upload file size'),':',UPLOAD_MAX_FILESIZE;?></span></fieldset>
<fieldset><legend><?php _e('Theme');?></legend>
<select name="theme">
	<option value=""><?php _e('Default');?></option>
<?php
	foreach($themes as $val){
?>
<option value="<?php echo $val;?>" <?php echo $s_theme[trim($val)];?> ><?php echo $val;?></option>
<?php
	}	
?>
</select></fieldset>
<fieldset><legend><?php echo _t('Title').'('._t('Default').')';?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $global_setting['title'];?>"></fieldset>
<fieldset><legend><?php _e('Meta Setting');?></legend>
<div class="mb10"><input type="text" name="keyword" class="input_text meta" value="<?php echo $global_setting['keywords']?$global_setting['keywords']:_t('Keywords').'('._t('Default').')';?>" data="<?php echo _t('Keywords').'('._t('Default').')';?>"> <span class="tip"><?php echo _t('Keywords').'('._t('Default').')';?></span></div>
<div class="mb10"><input type="text" name="description" class="input_text meta" value="<?php echo $global_setting['description']?$global_setting['description']:_t('Description').'('._t('Default').')';?>" data="<?php echo _t('Description').'('._t('Default').')';?>"> <span class="tip"><?php echo _t('Description').'('._t('Default').')';?></span></div>
</fieldset>
<fieldset><legend><?php _e('Cache');?></legend>
<input type="checkbox" name="cache" value="1" <?php echo CACHE_SETTING?'checked':'';?>/> <span class="tip"><?php _e('Enable data cache,this will save resource for query database.');?></span>
</fieldset>
<fieldset><legend><?php _e('Cache Expired');?></legend>
<input type="text" name="cache_expired" value="<?php echo $global_setting['cache_expired'];?>" style="width:50px;"/> <span class="tip"><?php _e('Second(s) 0:Never');?></span>
</fieldset>
<fieldset><legend><?php _e('Enable header 304');?></legend>
<input type="checkbox" name="header_304" value="1" <?php echo $global_setting['header_304']?'checked':'';?>/> <span class="tip"><?php _e('Output header 304 if page is not modified,this option will save server time');?></span>
</fieldset>
<fieldset><legend><?php _e('URL rewrite');?></legend>
<input type="checkbox" name="url_rewrite" value="1" <?php echo $global_setting['url_rewrite']?'checked':'';?>/> 
<span class="tip"><?php _e('Need server support');?></span>
</fieldset>
<fieldset><legend><?php _e('Enable Pagebreak');?></legend>
<input type="checkbox" name="pagebreak" value="1" <?php echo $global_setting['pagebreak']?'checked':'';?>/> 
<span class="tip"><?php _e('Enable pagebreak for long page content when insert pagebreak to post content.');?></span>
</fieldset>
<fieldset><legend class="toggle" data="#ns_list"><?php _e('Nums Setting');?></legend>
<div class="ns_list" id="ns_list">
<dl><dt><?php _e('Posts in each categories');?></dt><dd><input type="text" name="nums_setting[postCategories]" value="<?php echo $global_setting['nums_setting']['postCategories'];?>"/></dd></dl>
<dl><dt><?php _e('Posts in uncategories');?></dt><dd><input type="text" name="nums_setting[postUnCategories]" value="<?php echo $global_setting['nums_setting']['postUnCategories'];?>"/></dd></dl>
<dl><dt><?php _e('Max tags in tag cloud list');?></dt><dd><input type="text" name="nums_setting[tags]" value="<?php echo $global_setting['nums_setting']['tags'];?>"/></dd></dl>
<dl><dt><?php _e('Posts in category page');?></dt><dd><input type="text" name="nums_setting[postCategory]" value="<?php echo $global_setting['nums_setting']['postCategory'];?>"/></dd></dl>
<dl><dt><?php _e('Posts in home page');?></dt><dd><input type="text" name="nums_setting[postHome]" value="<?php echo $global_setting['nums_setting']['postHome'];?>"/></dd></dl>
<dl><dt><?php _e('Posts in tag page');?></dt><dd><input type="text" name="nums_setting[postTag]" value="<?php echo $global_setting['nums_setting']['postTag'];?>"/></dd></dl>
<dl><dt><?php _e('Posts in Pins mode');?></dt><dd><input type="text" name="nums_setting[postPins]" value="<?php echo $global_setting['nums_setting']['postPins'];?>"/></dd></dl>
<dl><dt><?php _e('Related Posts');?></dt><dd><input type="text" name="nums_setting[postRelated]" value="<?php echo $global_setting['nums_setting']['postRelated'];?>"/></dd></dl>
<dl><dt><?php _e('Max posts in Rssfeed page');?></dt><dd><input type="text" name="nums_setting[postRssfeed]" value="<?php echo $global_setting['nums_setting']['postRssfeed'];?>"/></dd></dl>
<dl><dt><?php _e('Comments in comment page');?></dt><dd><input type="text" name="nums_setting[commentList]" value="<?php echo $global_setting['nums_setting']['commentList'];?>"/></dd></dl>
<dl><dt><?php _e('Comments in Pins mode');?></dt><dd><input type="text" name="nums_setting[commentPins]" value="<?php echo $global_setting['nums_setting']['commentPins'];?>"/></dd></dl>
<dl><dt><?php _e('Category link per page in sitemap');?></dt><dd><input type="text" name="nums_setting[category_link_per_page]" value="<?php echo $global_setting['nums_setting']['category_link_per_page'];?>"/></dd></dl>
<dl><dt><?php _e('Post link per page in sitemap');?></dt><dd><input type="text" name="nums_setting[post_link_per_page]" value="<?php echo $global_setting['nums_setting']['post_link_per_page'];?>"/></dd></dl>
<dl><dt><?php _e('Custom link per page in sitemap');?></dt><dd><input type="text" name="nums_setting[custom_link_per_page]" value="<?php echo $global_setting['nums_setting']['custom_link_per_page'];?>"/></dd></dl>
<div class="div_clear"></div>
</div>
</fieldset>
<fieldset><legend><?php _e('Site Close Tip');?></legend>
<p><label class="editor_toggle" tid="close_tip" data="visual" id="lbVisual"><?php _e('Visual');?></label>
<label class="editor_toggle current_label" data="html" tid="close_tip" ><?php _e('HTML');?></label></p>
<?php include('lib/tinymce.php');?>
<textarea id="close_tip" name="close_tip" class="input_textarea"><?php echo toggle_attachment($global_setting['close_tip'],'dashboard');?></textarea>
<div class="tip"><?php _e('Show this in homepage when site close.');?></div>
</fieldset>
<fieldset><legend><?php _e('Close Website');?></legend>
<input type="checkbox" name="close" value="1" <?php echo $global_setting['close']?'checked':'';?>/>
</fieldset>
<fieldset><legend><?php _e('Track');?></legend>
<input type="checkbox" name="user_track" value="1" <?php echo $global_setting['user_track']?'checked':'';?>/>
</fieldset>
<?php 
	$savelist_none = true;
	include('lib/custom_field.php');
?>
</div>
<input type="submit" class="input_submit" value="<?php _e('Done');?>"> <input type="button" value="<?php _e('Back');?>" url="./" class="input_submit back">
</form>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.setting_label').bind('click',function(){
			var l = parseInt(_(this).attr('for'));
			_.setCookie({'name':'setting_tab','value':l});
			_('.setting_label').removeClass('current');
			_(this).addClass('current');
			for (var i=1;i<=2 ;i++ ){
				if (i == l){
					_('#setting_'+i).show();
				}else{	
					_('#setting_'+i).hide();
				}
			}
		});
		_('.meta').bind('blur',function(){
			if (!_(this).val()) {
				_(this).val(_(this).attr('data'));
			}
		}).bind('focus',function(){
			if (_(this).val() == _(this).attr('data')) {
				_(this).val('');
			}
		});
	});
//-->
</script>