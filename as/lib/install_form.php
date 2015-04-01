<?php
/**
 * SweetRice install form.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
 ?>
<style>
.row2 dl{clear:both;}
.row2 dl dt{float:left;width:15%;margin:5px 0px;display:inline;}
.row2 dl dd{float:left;width:84%;margin:5px 0px;display:inline;}
</style>
 <span class="message"><?php echo $message;?></span>
<form method="post" action="./?action=ok">
<fieldset><legend><?php _e('Site Name');?></legend>
<input type="text" name="name" value="<?php echo $_POST['name'];?>"></fieldset>
<fieldset><legend><?php _e('Webmaster');?></legend>
<input type="text" name="author" value="<?php echo $_POST['author'];?>"></fieldset>
<fieldset><legend><?php _e('Database Setting');?></legend>
<div class="row2">
<dl><dt>
<?php _e('Database');?>:</dt><dd><select name="database_type" class="database_type">
	<option value="mysql" <?php echo $s_dtype['mysql'];?>>MySQL</option>
	<option value="sqlite" <?php echo $s_dtype['sqlite'];?>>SQLite</option>
	<option value="pgsql" <?php echo $s_dtype['pgsql'];?>>PostgreSQL</option>
</select>
</dd></dl>
<div id="database_setting" class="row2" style="display:<?php echo $_POST['database_type']=='sqlite'?'none':'block';?>">
<dl><dt><?php _e('Database Host');?> : </dt><dd><input type="text" name="db_url" value="<?php echo $_POST['db_url']?$_POST['db_url']:'localhost';?>"> *<?php _e('Usually localhost');?></dd></dl>
<dl><dt><?php _e('Database Port');?> : </dt><dd><input type="text" name="db_port" id="db_port" value="<?php echo $_POST['db_port']?$_POST['db_port']:3306;?>"></dd></dl>
<dl><dt><?php _e('Database Account');?> : </dt><dd><input type="text" name="db_username" value="<?php echo $_POST['db_username'];?>"></dd></dl>
<dl><dt><?php _e('Database Password');?> : </dt><dd><input type="password" name="db_passwd" value="<?php echo $_POST['db_passwd'];?>"></dd></dl>
</div>
</fieldset>
<fieldset><legend><?php _e('Database Name');?></legend>
<input type="text" name="db_name" value="<?php echo $_POST['db_name'];?>"></fieldset>
<fieldset><legend><?php _e('Database Prefix');?></legend>
<input type="text" name="db_left" value="<?php echo $_POST['db_left']?$_POST['db_left']:'v';?>"></fieldset>
<fieldset><legend><?php _e('Administrator');?></legend>
<input type="text" name="admin" value="<?php echo $_POST['admin'];?>"></fieldset>
<fieldset><legend><?php _e('Administrator Password');?></legend>
<input type="password" name="passwd"></fieldset>

<div id="meta_setting" ><?php echo _t('Default').' Meta '._t('Setting');?></div>
<div id="meta" style="display:none;">
<fieldset><legend><?php _e('Title');?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $_POST['title'];?>"> * <span class="tip"><?php _e('Title of page');?></span></fieldset>
<fieldset><legend><?php _e('Meta Setting');?></legend>
<ul>
<li><input type="text" name="keyword" id="keyword" class="input_text meta" value="<?php echo $_POST['keyword']?$_POST['keyword']:_t('Keywords');?>" data="<?php _e('Keywords');?>"> * <span class="tip"><?php _e('Keywords of page');?></span></li>
<li><input type="text" name="description" id="description" class="input_text meta" value="<?php echo $_POST['description']?$_POST['description']:_t('Description');?>" data="<?php _e('Description');?>"> * <span class="tip"><?php _e('Description of page');?></span></li>
</ul>
</fieldset>
</div>
<input type="submit" value="<?php _e('Done');?>"> <input type="button" value="<?php _e('Back');?>" url="./" class="input_submit back">
</form>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.database_type').bind('change',function(){
			var t = _(this).val();
			if(t == 'sqlite'){
				_('#database_setting').hide();
			}else{
				_('#database_setting').show();
				if(t == 'mysql'){
					_('#db_port').val(3306);
				}
				if(t == 'pgsql'){
					_('#db_port').val(5432);
				}
			}
		});
		_('#meta_setting').bind('click',function(){
			_('#meta').toggle();
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