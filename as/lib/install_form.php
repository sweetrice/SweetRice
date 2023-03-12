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
<form method="post" action="./?action=save" id="install_form">
<fieldset><legend><?php _e('Site Name');?></legend>
<input type="text" name="name" value="<?php echo $_POST['name'];?>" class="req"></fieldset>
<fieldset><legend><?php _e('Webmaster');?></legend>
<input type="text" name="author" value="<?php echo $_POST['author'];?>" class="req"></fieldset>
<fieldset><legend><?php _e('Database Setting');?></legend>
<div class="row2">
	<div class="form_split"><?php _e('Database');?>:</div>
	<div class="form_split">
		<select name="database_type" class="database_type">
			<option value="mysql">MySQL</option>
			<option value="sqlite">SQLite</option>
			<option value="pgsql">PostgreSQL</option>
		</select>
	</div>
</div>
<div id="database_setting" class="row2" style="display:<?php echo $_POST['database_type']=='sqlite'?'none':'block';?>">
<div class="form_split"><?php _e('Database Host');?></div>
<div class="div_clear mb10"></div>
<div class="form_split"><input type="text" name="db_url" value="<?php echo $_POST['db_url']?$_POST['db_url']:'localhost';?>"> <span class="tip"><?php _e('Usually localhost');?></span></div>
<div class="div_clear mb10"></div>
<div class="form_split"><?php _e('Database Port');?></div>
<div class="div_clear mb10"></div>
<div class="form_split"><input type="text" name="db_port" id="db_port" value="<?php echo $_POST['db_port']?$_POST['db_port']:3306;?>"></div>
<div class="div_clear mb10"></div>
<div class="form_split"><?php _e('Database Account');?></div>
<div class="div_clear mb10"></div>
<div class="form_split"><input type="text" name="db_username" value="<?php echo $_POST['db_username'];?>"></div>
<div class="div_clear mb10"></div>
<div class="form_split"><?php _e('Database Password');?></div>
<div class="div_clear mb10"></div>
<div class="form_split"><input type="password" name="db_passwd" value="<?php echo $_POST['db_passwd'];?>"></div>
</div>
</fieldset>
<fieldset><legend><?php _e('Database Name');?></legend>
<input type="text" name="db_name" value="<?php echo $_POST['db_name'];?>" class="req"></fieldset>
<fieldset><legend><?php _e('Database Prefix');?></legend>
<input type="text" name="db_left" value="<?php echo $_POST['db_left']?$_POST['db_left']:'v';?>" class="req"></fieldset>
<fieldset><legend><?php _e('Administrator');?></legend>
<input type="text" name="admin" class="req" value="<?php echo $_POST['admin'];?>"></fieldset>
<fieldset><legend><?php _e('Administrator Password');?></legend>
<input type="password" name="passwd" class="req"></fieldset>

<div id="meta_setting" ><?php echo _t('Default').' Meta '._t('Setting');?></div>
<div id="meta" style="display:none;">
<fieldset><legend><?php _e('Title');?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $_POST['title'];?>"> * <span class="tip"><?php _e('Title of page');?></span></fieldset>
<fieldset><legend><?php _e('Meta Setting');?></legend>
<div class="mb10"><input type="text" name="keyword" id="keyword" class="input_text meta" value="<?php echo $_POST['keyword']?$_POST['keyword']:_t('Keywords');?>" data="<?php _e('Keywords');?>"> * <span class="tip"><?php _e('Keywords of page');?></span></div>
<div class="mb10"><input type="text" name="description" id="description" class="input_text meta" value="<?php echo $_POST['description']?$_POST['description']:_t('Description');?>" data="<?php _e('Description');?>"> * <span class="tip"><?php _e('Description of page');?></span></div>
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
		_('#install_form').submit(function(event){
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
				'form':this,
				'success':function(result){
					if (result['status'] == 1) {
						location.href = '<?php echo BASE_URL.DASHBOARD_DIR.'/';?>';
					}else{
						_.ajax_untip(result['status_code']);
					}
				}
			});			
		});
	});
//-->
</script>