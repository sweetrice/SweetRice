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
<div class="tip"><?php _e('Please select table to converter,current database is ');?> <b><?php echo DATABASE_TYPE;?></b></div>
<?php
	if($message){
?>
<div id="convert_error"><?php echo $message;?></div>
<?php
	}
?>
<form method="post" action="./?type=data&mode=db_converter&form_mode=yes" id="convert_form">
<fieldset><legend><?php _e('Database Setting');?> - <select name="totype" class="totype">
<?php
		foreach(array('sqlite','mysql','pgsql') as $val){
?>
<option value="<?php echo $val;?>" <?php echo $s_totype[$val];?>><?php echo $val;?></option>
<?php
		}
	?>
	</select></legend>
<div id="database_type" class="row2" style="display:<?php echo $totype=='sqlite'?'none':'';?>;">
<div class="form_split"><span class="w120"><?php _e('Database Host');?></span></div>
<div class="form_split"><input type="text" name="to_db_url" value="<?php echo $_POST['to_db_url']?$_POST['to_db_url']:'localhost';?>"></div>
<div class="form_split"><span class="tip"><?php _e('Usually localhost');?></span></div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w120"><?php _e('Database Port');?></span></div>
<div class="form_split"><input type="text" name="to_db_port" id="to_db_port" value="<?php echo $_POST['to_db_port']?$_POST['to_db_port']:3306;?>"></div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w120"><?php _e('Database Account');?></span></div>
<div class="form_split"><input type="text" name="to_db_username" value="<?php echo $_POST['to_db_username'];?>"></div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w120"><?php _e('Database Password');?></span></div>
<div class="form_split"><input type="password" name="to_db_passwd" value="<?php echo $_POST['to_db_passwd'];?>"></div>
<div class="div_clear mb10"></div>
</div>
<div class="row2">
<div class="form_split"><span class="w120"><?php _e('Database Name');?></span></div>
<div class="form_split"><input type="text" class="req" name="to_db_name" value="<?php echo $_POST['to_db_name'];?>"></div>
<div class="div_clear mb10"></div>
<div class="form_split"><span class="w120"><?php _e('Database Prefix');?></span></div>
<div class="form_split"><input type="text" name="to_db_left" value="<?php echo $_POST['to_db_left']?$_POST['to_db_left']:DB_LEFT;?>"></div>
<div class="div_clear mb10"></div>
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
<li><input type="checkbox" id="checkall" checked/> <input type="submit" value="<?php _e('Done');?>" class="input_submit"/></li>
</ul>
</div>
</form>

<script type="text/javascript">
<!--
	_.ready(function(){
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
		_('#convert_form').submit(function(event){
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
						_.ajax_untip(result['status_code'],2000,function(){
							location.href = './';
						});
					}else{
						_.ajax_untip(result['status_code']);
					}
				}
			});	
		});
	});
//-->
</script>