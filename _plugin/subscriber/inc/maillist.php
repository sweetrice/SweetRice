<?php
/**
 * Email list management template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<?php echo $pager['list_put'];?>
<table>
	<tr><td><input type="checkbox" id="checkall"/> <?php echo EMAIL;?></td><td><?php echo DATE_TIP;?></td><td><?php echo ADMIN;?></td></tr>
<?php
	foreach($ml AS $mls ){
		if($bgcolor=='#F1F1F1'){
			$bgcolor = '#F8F8F3';
		}else{
			$bgcolor='#F1F1F1';
		}
?>
<tr onmouseover="this.style.backgroundColor='#E0E8F1';" onmouseout="this.style.backgroundColor='<?php echo $bgcolor;?>';" style="background-color:<?php echo $bgcolor;?>;"><td><input type="checkbox" name="plist[]" value="<?php echo $mls['email'];?>" class="ck_item"/> <?php echo $mls['email'];?></td><td><?php echo date('m/d/Y H:i:s',$mls['date']);?></td><td>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" delkey="<?php echo $mls['email'];?>" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
</td></tr>
<?php
	}
?>
</table>
<input type="button" class="bulk_delete" value="<?php echo BULK.' '.DELETE_TIP;?>">
<?php echo $pager['list_put'];?>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.action_delete').bind('click',function(){
			if (!confirm('<?php echo DELETE_CONFIRM;?>')){
				return ;
			}
			var ptr = this.parentNode.parentNode;
			_.ajax({
				'type':'GET',
				'data':{'email':_(this).attr('delkey')},
				'url':'<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'delete'));?>',
				'success':function(result){
					if (result.status == 1){
						_(ptr).remove();
					}
				}
			});
		});
		_('.bulk_delete').bind('click',function(){
			if (!confirm('<?php echo DELETE_CONFIRM;?>')){
				return ;
			}
			var ids = [];
			var ptr = [];
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					ids.push(_(this).val());
					ptr.push(this.parentNode.parentNode);
				}
			});
			if (ids.length == 0){
				return ;
			}
			_.ajax({
				'type':'GET',
				'data':{'ids':ids.join()},
				'url':'<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'bulk_delete'));?>',
				'success':function(result){
					if (result.status == 1){
						for (var i in ptr )
						{
							_(ptr[i]).remove();
						}
					}
				}
			});
		});
	});
//-->
</script>