<?php
/**
 * Database optimizer.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.6.0
 */
 defined('VALID_INCLUDE') or die();
?>
<div class="tip"><?php _e('Enter SQL and execute it online,please backup database first.');?> <b><?php echo DATABASE_TYPE;?></b></div>
<?php
	if($message){
?>
<div class="tip" id="convert_error"><?php echo $message;?></div>
<?php
	}
?>
<fieldset><legend><?php _e('SQL Content');?></legend>
<textarea id="sql_content" class="input_textarea"><?php echo $sql_content;?></textarea>
</fieldset>
	<input type="button" value="<?php _e('Done');?>" class="input_submit"/>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.input_submit').bind('click',function(){
			if (!_('#sql_content').val()){
				_('#sql_content').addClass('required');
				return ;
			}else{
				_('#sql_content').removeClass('required');
			}
			_.ajax({
			'type':'post',
			'data':{'sql_content':_('#sql_content').val()},
			'url':'./?type=data&mode=sql_execute&form_mode=yes',
			'success':function(result){
				_.dialog({'content':result.status_code});
			}
			});
		});
	});
//-->
</script>