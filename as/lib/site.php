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
<label class="setting_label<?php echo $_COOKIE['setting_tab'] != 2?' current':'';?>" for="1"><?php echo SYSTEM_SETTING;?></label><label class="setting_label<?php echo $_COOKIE["setting_tab"] == 2?' current':'';?>" for="2"><?php echo WEB_SETTING;?></label>
<form method="post" action="./?type=setting&mode=save" enctype="multipart/form-data">
<input type="hidden" name="old_passwd" value="<?php echo $global_setting['passwd'];?>"/>
<input type="hidden" name="old_logo" value="<?php echo $global_setting['logo'];?>"/>
<div id="setting_1" <?php echo $_COOKIE['setting_tab'] != 2?'style="display:block;"':'';?>>
<fieldset><legend><?php echo DASHBOARD.' '.LANG;?></legend>
<select name="lang">
	<option value="" ><?php echo DEFAULT_TIP;?></option>
<?php
	foreach($lang as $key=>$val){
?>
<option value="<?php echo $key;?>.php" <?php echo $dashboard_lang[trim($key).'.php'];?> ><?php echo $val;?></option>
<?php
	}
?>
</select></fieldset>
<fieldset><legend><?php echo WEBMASTER;?></legend>
<input type="text" name="author" value="<?php echo $global_setting['author'];?>"></fieldset>
<fieldset><legend><?php echo DASHBOARD_DIRECTORY;?></legend>
<input type="text" name="dashboard_dirs" value="<?php echo DASHBOARD_DIR;?>" <?php echo SITE_HOME == ROOT_DIR?'':'readonly';?>> <?php echo DASHBOARD_DIRECTORY_TIP;?>
</fieldset>
<fieldset><legend><?php echo DATABASE.' '.SETTING;?></legend>
<?php
	if(DATABASE_TYPE=='sqlite'){
?>
<ul>
<li><?php echo DATABASE;?> : Sqlite</li>
</ul>
<?php
	}else{
?>
<ul>
<li><?php echo DATABASE;?> : <?php echo DATABASE_TYPE?DATABASE_TYPE:'mysql';?></li>
<li><?php echo DATABASE_HOST;?> : <?php echo $db_url?$db_url:'localhost';?></li>
<li><?php echo DATA_PORT;?> : <?php echo $db_port;?></li>
<li><?php echo DATA_ACCOUNT;?> : <?php echo $db_username;?></li>
<li><?php echo DATA_PASSWORD;?> : <?php echo $db_passwd;?></li>
</ul>
<?php
	}
?>
</fieldset>
<fieldset><legend><?php echo DATA_NAME;?></legend>
<?php echo $db_name;?></fieldset>
<fieldset><legend><?php echo DATA_PREFIX;?></legend>
<?php echo DB_LEFT;?></fieldset>
<fieldset><legend><?php echo ADMIN_ACCOUNT;?></legend>
<input type="text" name="admin" value="<?php echo $global_setting['admin'];?>"></fieldset>
<fieldset><legend><?php echo ADMIN_PASSWORD;?></legend>
<input type="password" name="passwd"></fieldset>
<fieldset><legend><?php echo ADMIN_EMAIL;?></legend>
<input type="text" name="admin_email" value="<?php echo $global_setting['admin_email'];?>"/>
</fieldset>
<fieldset><legend><?php echo TIME_ZONE;?></legend>
<select name="timeZone">
<option value=""><?php echo CHOOSE_TIME_ZONE;?></option>
<?php
	$tzs = include('timezone.php');
	$s_tzs[$global_setting['timeZone']] = 'selected';
	foreach($tzs as $key=>$val){
?>
<option value="<?php echo trim($key);?>" <?php echo $s_tzs[$key];?>><?php echo $val;?></option>
<?php
	}
?>
</select>
</fieldset>
</div>
<div id="setting_2" <?php echo $_COOKIE['setting_tab'] == 2?'style="display:block;"':'';?>>
<fieldset><legend><?php echo THEME.' '.LANG;?></legend>
<select name="theme_lang">
	<option value=""><?php echo DEFAULT_TIP;?></option>
<?php
	foreach($lang_types as $key=>$val){
?>
<option value="<?php echo $key;?>" <?php echo $s_lang[$key];?>><?php echo $val;?></option>
<?php
	}
?>
</select></fieldset>
<fieldset><legend><?php echo SITE_NAME;?></legend>
<input type="text" name="name" value="<?php echo $global_setting['name'];?>">
</fieldset>
<fieldset><legend>Logo</legend>
<img src="<?php echo $global_setting['logo']?'../'.ATTACHMENT_DIR.$global_setting['logo']:'../images/sweetrice.jpg';?>">
<input type="file" name="logo" class="input_text_tip" > <?php echo MAX_UPLOAD_FILE_TIP,':',UPLOAD_MAX_FILESIZE;?></fieldset>
<fieldset><legend><?php echo THEME;?></legend>
<select name="theme">
	<option value="" selected="selected"><?php echo DEFAULT_TIP;?></option>
<?php
	foreach($themes as $val){
?>
<option value="<?php echo $val;?>" <?php echo $s_theme[trim($val)];?> ><?php echo $val;?></option>
<?php
	}	
?>
</select></fieldset>
<fieldset><legend><?php echo TITLE.'('.DEFAULT_TIP.')';?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $global_setting['title'];?>"></fieldset>
<fieldset><legend><?php echo META.' '.SETTING;?></legend>
<ul>
<li><input type="text" name="keyword" class="input_text" value="<?php echo $global_setting['keywords']?$global_setting['keywords']:KEYWORD.'('.DEFAULT_TIP.')';?>" onblur="if (this.value == '') {this.value = '<?php echo KEYWORD.'('.DEFAULT_TIP.')';?>';}" onfocus="if (this.value == '<?php echo KEYWORD.'('.DEFAULT_TIP.')';?>') {this.value = '';}" > <?php echo KEYWORD.'('.DEFAULT_TIP.')';?></li>
<li><input type="text" name="description" class="input_text" value="<?php echo $global_setting['description']?$global_setting['description']:DESCRIPTION.'('.DEFAULT_TIP.')';?>" onblur="if (this.value == '') {this.value = '<?php echo DESCRIPTION.'('.DEFAULT_TIP.')';?>';}" onfocus="if (this.value == '<?php echo DESCRIPTION.'('.DEFAULT_TIP.')';?>') {this.value = '';}"> <?php echo DESCRIPTION.'('.DEFAULT_TIP.')';?></li>
</ul>
</fieldset>
<fieldset><legend><?php echo CACHE;?></legend>
<input type="checkbox" name="cache" value="1" <?php echo CACHE_SETTING?'checked':'';?>/> <?php echo CACHE_TIPS;?>
</fieldset>
<fieldset><legend><?php echo CACHE.' '.EXPIRED;?></legend>
<input type="text" name="cache_expired" value="<?php echo $global_setting['cache_expired'];?>" style="width:50px;"/> <?php echo CACHE_TIPS;?>
</fieldset>
<fieldset><legend><?php echo HEADER_304;?></legend>
<input type="checkbox" name="header_304" value="1" <?php echo $global_setting['header_304']?'checked':'';?>/>  <?php echo HEADER_304_TIP;?>
</fieldset>
<fieldset><legend><?php echo URL_REWRITE_TIP;?></legend>
<input type="checkbox" name="url_rewrite" value="1" <?php echo $global_setting['url_rewrite']?'checked':'';?>/> 
<?php echo NEED_SERVER_SUPPORT;?>
</fieldset>
<fieldset><legend><a href="javascript:void(0);" class="tg_ns"><?php echo NUMS_SETTING;?></a></legend>
<div class="ns_list" id="ns_list">
<dl><dt><?php echo NS_POST_CATEGORIES;?></dt><dd><input type="text" name="nums_setting[postCategories]" value="<?php echo $global_setting['nums_setting']['postCategories'];?>"/></dd></dl>
<dl><dt><?php echo NS_POST_UNCATEGORIES;?></dt><dd><input type="text" name="nums_setting[postUnCategories]" value="<?php echo $global_setting['nums_setting']['postUnCategories'];?>"/></dd></dl>
<dl><dt><?php echo NS_TAGS;?></dt><dd><input type="text" name="nums_setting[tags]" value="<?php echo $global_setting['nums_setting']['tags'];?>"/></dd></dl>
<dl><dt><?php echo NS_POST_CATEGORY;?></dt><dd><input type="text" name="nums_setting[postCategory]" value="<?php echo $global_setting['nums_setting']['postCategory'];?>"/></dd></dl>
<dl><dt><?php echo NS_POST_HOME;?></dt><dd><input type="text" name="nums_setting[postHome]" value="<?php echo $global_setting['nums_setting']['postHome'];?>"/></dd></dl>
<dl><dt><?php echo NS_POST_TAG;?></dt><dd><input type="text" name="nums_setting[postTag]" value="<?php echo $global_setting['nums_setting']['postTag'];?>"/></dd></dl>
<dl><dt><?php echo NS_POST_PINS;?></dt><dd><input type="text" name="nums_setting[postPins]" value="<?php echo $global_setting['nums_setting']['postPins'];?>"/></dd></dl>
<dl><dt><?php echo NS_POST_RELATED;?></dt><dd><input type="text" name="nums_setting[postRelated]" value="<?php echo $global_setting['nums_setting']['postRelated'];?>"/></dd></dl>
<dl><dt><?php echo NS_RSSFEED;?></dt><dd><input type="text" name="nums_setting[postRssfeed]" value="<?php echo $global_setting['nums_setting']['postRssfeed'];?>"/></dd></dl>
<dl><dt><?php echo NS_COMMENT_LIST;?></dt><dd><input type="text" name="nums_setting[commentList]" value="<?php echo $global_setting['nums_setting']['commentList'];?>"/></dd></dl>
<dl><dt><?php echo NS_COMMENT_PINS;?></dt><dd><input type="text" name="nums_setting[commentPins]" value="<?php echo $global_setting['nums_setting']['commentPins'];?>"/></dd></dl>
<div class="div_clear"></div>
</div>
</fieldset>
<fieldset><legend><?php echo SITE_CLOSE_TIP;?></legend>
<p><label id="lbVisual" onmousedown='doEditor("visual","close_tip");'>Visual</label>
<label id="lbHtml" class="current_label" onmousedown='doEditor("html","close_tip");'>HTML</label></p>
<?php include("lib/tinymce.php");?>
<textarea id="close_tip" name="close_tip" class="input_textarea"><?php echo $global_setting['close_tip'];?></textarea>
<br /><?php echo SITE_CLOSE_TIPS;?>
</fieldset>
<fieldset><legend><?php echo SITE_CLOSE;?></legend>
<input type="checkbox" name="close" value="1" <?php echo $global_setting['close']?'checked':'';?>/>
</fieldset>
<fieldset><legend><?php echo TRACK;?></legend>
<input type="checkbox" name="user_track" value="1" <?php echo $global_setting['user_track']?'checked':'';?>/>
</fieldset>
</div>
<input type="submit" class="input_submit" value="<?php echo DONE;?>"> <input type="button" value="<?php echo BACK;?>" onclick='location.href="./"' class="input_submit">
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
		_('.tg_ns').bind('click',function(){
			_('#ns_list').toggle();
		});
	});
//-->
</script>