<?php
/**
 * Sites management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
 $lang_types = getLangTypes();
 $s_lang = array($global_setting['theme_lang'] => 'selected');
?>
<style>
.row2 dl{clear:both;}
.row2 dl dt{float:left;width:15%;margin:5px 0px;display:inline;}
.row2 dl dd{float:left;width:84%;margin:5px 0px;display:inline;}
</style>
<form method="post" action="./?type=sites&mode=save" enctype="multipart/form-data" id="sform">
<fieldset><legend><?php _e('Host');?></legend>
<input type="text" name="host" class="host req" />
</fieldset>
<fieldset><legend><?php _e('Database Setting');?></legend>
<div class="row2">
<div class="form_split">
<?php _e('Database');?>:</div>
<div class="form_split"><select name="site_config[db_type]" class="database_type">
	<option value="mysql">MySQL</option>
	<option value="sqlite">SQLite</option>
	<option value="pgsql">PostgreSQL</option>
</select>
</div></div>
<div id="database_setting" class="row2" style="display:<?php echo $_POST['database_type']=='sqlite'?'none':'block';?>">
<div class="form_split"><?php _e('Database Host');?></div><div class="div_clear mb10"></div>
<div class="form_split"><input type="text" name="site_config[db_url]" value="<?php echo $_POST['db_url']?$_POST['db_url']:'localhost';?>"> <span class="tip"><?php _e('Usually localhost');?></span></div><div class="div_clear mb10"></div>
<div class="form_split"><?php _e('Database Port');?></div><div class="div_clear mb10"></div>
<div class="form_split"><input type="text" name="site_config[db_port]" id="db_port" value="<?php echo $_POST['db_port']?$_POST['db_port']:3306;?>"></div><div class="div_clear mb10"></div>
<div class="form_split"><?php _e('Database Account');?></div>
<div class="div_clear mb10"></div>
<div class="form_split"><input type="text" name="site_config[db_username]" value="<?php echo $_POST['db_username'];?>"></div>
<div class="div_clear mb10"></div>
<div class="form_split"><?php _e('Database Password');?></div>
<div class="div_clear mb10"></div>
<div class="form_split"><input type="password" name="site_config[db_passwd]" value="<?php echo $_POST['db_passwd'];?>"></div>
</div>
</fieldset>
<fieldset><legend><?php _e('Database Name');?></legend>
<input type="text" name="site_config[db_name]" value="<?php echo $_POST['db_name'];?>" class="req"></fieldset>
<fieldset><legend><?php _e('Database Prefix');?></legend>
<input type="text" name="site_config[db_left]" value="<?php echo $_POST['db_left']?$_POST['db_left']:'v';?>" class="req"></fieldset>
<fieldset><legend><?php _e('Administrator');?></legend>
<input type="text" name="admin" value="<?php echo $_POST['admin'];?>" class="req"></fieldset>
<fieldset><legend><?php _e('Administrator Password');?></legend>
<input type="password" name="passwd" class="req"></fieldset>
<fieldset><legend><?php _e('Website Attachment Directory');?></legend>
<div class="form_split">
<input type="radio" name="attachment_type" value="1" checked/>_sites/<span id="host_body"></span><input type="text" name="attachment_dir" value="attachment"></div>
<div class="form_split">
<input type="radio" name="attachment_type" value="2"/><?php echo ATTACHMENT_DIR;?></div>
</fieldset>
<fieldset><legend><?php _e('Theme');?></legend>
<?php
	foreach($themes as $val){
?>
<div class="form_split"><input type="checkbox" name="themes[]" value="<?php echo $val;?>" <?php echo $val=='default'?'checked onclick="return false;" ':'';?>/> <?php echo $val;?> </div>
<?php
	}	
?>
</fieldset>
<fieldset><legend><?php _e('Plugin');?></legend>
<?php
	foreach(pluginList() as $val){
?>
<div class="form_split"><input type="checkbox" name="plugins[]" value="<?php echo $val['directory'];?>"/> <?php echo $val['name'];?> </div>
<?php
	}	
?>
</fieldset>
<input type="submit" class="input_submit" value="<?php _e('Done');?>"> <input type="button" value="<?php _e('Back');?>" url="./?type=sites" class="input_submit back">
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
		_('.host').bind('change',function(){
			_('#host_body').html(_(this).val() + '/');
		});

		_('#sform').bind('submit',function(event){
			_.stopevent(event);
			var req_field;
			_('.req').each(function(){
				if(!_(this).val() && !req_field){
					req_field = this;
				}
			});
			if (req_field) {
				_(req_field).focus();
				return ;
			}	
			_.ajax({
				type:_(this).attr('method'),
				form:'#sform',
				url:_(this).attr('action'),
				success:function(result){
					if (result['status'] == 1)
					{
						location.href = './?type=sites';
					}else{
						_.ajax_untip(result['status_code']);
					}
				}
			});
		});

	});
//-->
</script>