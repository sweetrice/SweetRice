<?php
/**
 * Database converter.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.5
 */
 defined('VALID_INCLUDE') or die();
?>
<style>
.row2 dl{clear:both;}
.row2 dl dt{float:left;width:15%;margin:5px 0px;display:inline;}
.row2 dl dd{float:left;width:84%;margin:5px 0px;display:inline;}
</style>
<div><?php echo DATABASE_CONVERTER_TIP;?> <b><?php echo DATABASE_TYPE;?></b></div>
<?php
	if($message){
?>
<div id="convert_error"><?php echo $message;?></div>
<?php
	}
?>
<form method="post" action="./?type=data&mode=db_converter&form_mode=yes">
<fieldset><legend><?php echo DATABASE.' '.SETTING;?> - <select name="totype" class="totype">
<?php
		foreach(array('sqlite','mysql','pgsql') as $val){
?>
<option value="<?php echo $val;?>" <?php echo $s_totype[$val];?>><?php echo $val;?></option>
<?php
		}
	?>
	</select></legend>
<div id="database_type" class="row2" style="display:<?php echo $totype=='sqlite'?'none':'';?>;">
<dl><dt><?php echo DATABASE_HOST;?> : </dt><dd><input type="text" name="to_db_url" value='<?php echo $_POST["to_db_url"]?$_POST['to_db_url']:'localhost';?>'> *<?php echo DATABASE_HOST_TIP;?></dd></dl>
<dl><dt><?php echo DATA_PORT;?> : </dt><dd><input type="text" name="to_db_port" id="to_db_port" value="<?php echo $_POST["to_db_port"]?$_POST["to_db_port"]:3306;?>"></dd></dl>
<dl><dt><?php echo DATA_ACCOUNT;?> : </dt><dd><input type="text" name="to_db_username" value="<?php echo $_POST["to_db_username"];?>"></dd></dl>
<dl><dt><?php echo DATA_PASSWORD;?> : </dt><dd><input type="password" name="to_db_passwd" value="<?php echo $_POST["to_db_passwd"];?>"></dd></dl>
</div>
<div class="row2">
<dl><dt><?php echo DATA_NAME;?> : </dt><dd><input type="text" name="to_db_name" value="<?php echo $_POST["to_db_name"];?>"></dd></dl>
<dl><dt><?php echo DATA_PREFIX;?> : </dt><dd><input type="text" name="to_db_left" value="<?php echo $_POST["to_db_left"]?$_POST['to_db_left']:DB_LEFT;?>"></dd></dl>
</div>
</fieldset>
<div id="table_list">
<ul>
<?php
	foreach($table_list as $val){
?>
<li><input type="checkbox" name="tablelist[]" class="ck_item" value="<?php echo $val;?>" checked/> <?php echo $val;?></li>
<?php
	}
?>
<li><input type="checkbox" id="checkall" checked/> <input type="submit" value="<?php echo DONE;?>" class="input_submit"/></li>
</ul>
</div>
</form>

<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.totype').bind('change',function(){
		var t = _(this).val();
		if(t == 'sqlite'){
			_('#database_type').hide();
		}else{
			_('#database_type').show();
			if(t == 'mysql'){
				_('#to_db_port').val(3306);
			}
			if(t == 'pgsql'){
				_('#to_db_port').val(5432);
			}
		}
		});
	});
//-->
</script>