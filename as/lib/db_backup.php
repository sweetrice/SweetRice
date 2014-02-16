<?php
/**
 * Database backup.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.5
 */
 defined('VALID_INCLUDE') or die();
?>
<p><?php echo DATABASE_BACKUP_TIP;?> <b><?php echo DATABASE_TYPE;?></b></p>
<?php
	if($message){
?>
<p id="convert_error"><?php echo $message;?></p>
<?php
	}
?>
<div id="table_list">
<ul>
<?php
	foreach($table_list as $val){
?>
<li><input type="checkbox" name="tablelist[]" class="ck_item" value="<?php echo $val;?>" <?php echo substr($val,0,strlen(DB_LEFT)) == DB_LEFT ?'checked':'';?>/> <?php echo $val;?></li>
<?php
	}
?>
<li><input type="checkbox" id="checkall" checked/> <input type="submit" value="<?php echo DONE;?>" class="input_submit"/></li>
</ul>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.input_submit').bind('click',function(){
			var tablelist = [];
			_('.ck_item').each(function(){
				if (_(this).prop('checked'))
				{
					tablelist.push(_(this).val());
				}
			});
			if (!tablelist.length){
				alert('<?php echo NO_PACTION_SELECTED;?>');
				return ;
			}
			_.ajax({
			'type':'post',
			'data':{'tablelist':tablelist.join(',')},
			'url':'./?type=data&mode=db_backup&form_mode=yes',
			'success':function(result){
				var dlg = _.dialog({
					'content':result.status_code,
					'close':function(){
						if (result.status == 1){
							location.href = './?type=data&mode=db_import';
						}
					}
				});
			}
			});
		});
	});
//-->
</script>