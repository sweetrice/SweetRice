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
<div class="tip"><?php _e('Please select table to optimizer,current database is');?> <b><?php echo DATABASE_TYPE;?></b></div>
<div id="table_list">
<ul>
<?php
	foreach($table_list as $val){
?>
<li><input type="checkbox" name="tablelist[]" class="ck_item" value="<?php echo $val;?>" checked/> <?php echo $val;?></li>
<?php
	}
?>
</ul>
</div>
<div class="mg5"><input type="checkbox" id="checkall" checked/> <input type="button" value="<?php _e('Done');?>" class="input_submit"/></div>

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
				alert('<?php _e('No Record Selected');?>');
				return ;
			}
			_.ajax({
			'type':'post',
			'data':{'tablelist':tablelist.join(',')},
			'url':'./?type=data&mode=db_optimizer&form_mode=yes',
			'success':function(result){
				var dlg = _.dialog({
					content:'<div class="optimizer_result">'+result.status_code+'</div>',
					width:800,
					layer:1
				});
			}
			});
		});
	});
//-->
</script>