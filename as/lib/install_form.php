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
<fieldset><legend><?php echo SITE_NAME;?></legend>
<input type="text" name="name" value='<?php echo $_POST["name"];?>'></fieldset>
<fieldset><legend><?php echo WEBMASTER;?></legend>
<input type="text" name="author" value='<?php echo $_POST["author"];?>'></fieldset>
<fieldset><legend><?php echo DATABASE.' '.SETTING;?></legend>
<div class="row2">
<dl><dt>
<?php echo DATABASE;?>:</dt><dd><select name="database_type" class="database_type">
	<option value="mysql" <?php echo $s_dtype['mysql'];?>>Mysql</option>
	<option value="sqlite" <?php echo $s_dtype['sqlite'];?>>Sqlite</option>
	<option value="pgsql" <?php echo $s_dtype['pgsql'];?>>Postgresql</option>
</select>
</dd></dl>
<div id="database_setting" class="row2" style="display:<?php echo $_POST["database_type"]=='sqlite'?'none':'block';?>">
<dl><dt><?php echo DATABASE_HOST;?> : </dt><dd><input type="text" name="db_url" value="<?php echo $_POST["db_url"]?$_POST['db_url']:'localhost';?>"> *<?php echo DATABASE_HOST_TIP;?></dd></dl>
<dl><dt><?php echo DATA_PORT;?> : </dt><dd><input type="text" name="db_port" id="db_port" value="<?php echo $_POST["db_port"]?$_POST["db_port"]:3306;?>"></dd></dl>
<dl><dt><?php echo DATA_ACCOUNT;?> : </dt><dd><input type="text" name="db_username" value="<?php echo $_POST["db_username"];?>"></dd></dl>
<dl><dt><?php echo DATA_PASSWORD;?> : </dt><dd><input type="password" name="db_passwd" value="<?php echo $_POST["db_passwd"];?>"></dd></dl>
</div>
</fieldset>
<fieldset><legend><?php echo DATA_NAME;?></legend>
<input type="text" name="db_name" value="<?php echo $_POST["db_name"];?>"></fieldset>
<fieldset><legend><?php echo DATA_PREFIX;?></legend>
<input type="text" name="db_left" value="<?php echo $_POST["db_left"]?$_POST['db_left']:'v';?>"></fieldset>
<fieldset><legend><?php echo ADMIN_ACCOUNT;?></legend>
<input type="text" name="admin" value="<?php echo $_POST["admin"];?>"></fieldset>
<fieldset><legend><?php echo ADMIN_PASSWORD;?></legend>
<input type="password" name="passwd" value=""></fieldset>

<div id="meta_setting" ><?php echo DEFAULT_TIP.' meta '.SETTING;?></div>
<div id="meta" style="display:none;">
<fieldset><legend><?php echo TITLE;?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $_POST["title"];?>"> *<?php echo TITLE_TIP;?></fieldset>
<fieldset><legend><?php echo META.' '.SETTING;?></legend>
<ul>
<li><input type="text" name="keyword" id="keyword" class="input_text" value="<?php echo $_POST["keyword"]?$_POST["keyword"]:KEYWORD;?>" onblur="if (this.value == '') {this.value = '<?php echo KEYWORD;?>';}" onfocus="if (this.value == '<?php echo KEYWORD;?>') {this.value = '';}" > *<?php echo KEYWORD_TIP;?></li>
<li><input type="text" name="description" id="description" class="input_text" value="<?php echo $_POST["description"]?$_POST["description"]:DESCRIPTION;?>" onblur="if (this.value == '') {this.value = '<?php echo DESCRIPTION;?>';}" onfocus="if (this.value == '<?php echo DESCRIPTION;?>') {this.value = '';}"> *<?php echo DESCRIPTION_TIP;?></li>
</ul>
</fieldset>
</div>
<input type="submit" value="<?php echo DONE;?>"> <input type="button" value="<?php echo BACK;?>" onclick="location.href='./';" class="input_submit">
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
	});
//-->
</script>