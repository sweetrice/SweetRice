<?php
/**
 * App form list template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="plugin"/>
<input type="hidden" name="plugin" value="<?php echo THIS_APP;?>"/>
<input type="hidden" name="app_mode" value="form"/>
<input type="text" name="search" value="<?php echo $_GET['search'];?>"/>
<input type="submit" value="<?php _e('Search');?>"/>
</form>
<?php echo $data['pager']['list_put'];?>
<form method="post" id="bulk_form" action="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'form','mode'=>'bulk'));?>">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl;?>"/>
<table>
<thead>
	<tr>
		<th class="data_no"><input type="checkbox" class="checkall"/></th>
		<th class="max50"><?php _e('Name');?></th>
		<th><?php _e('Method');?></th>
		<th><?php _e('Form Action');?></th>
		<th><?php _e('Fields');?></th>
		<th class="td_admin"><?php _e('Admin');?></th>
	</tr>
</thead>
<tbody>
<?php
	foreach($data['rows'] AS $row ){
		$fields = unserialize($row['fields']);
?>
<tr>
	<td><input type="checkbox" name="plist[]" value="<?php echo $row['id'];?>" class="ck_item"/></td>
	<td class="max50" data-label="<?php _e('Name');?>"><a href="<?php echo BASE_URL.pluginHookUrl(THIS_APP,array('app_mode'=>'form','id'=>$row['id']))?>" target="_blank"><?php echo $row['name'];?></a></td>
	<td data-label="<?php _e('Method');?>"><?php echo $row['method'];?></td>
	<td data-label="<?php _e('Form Action');?>"><?php echo $row['action'];?></td>
	<td data-label="<?php _e('Fields');?>"><?php foreach($fields as $val):
echo $val['tip'].'['.$val['type'].($val['option']?' '._t('Options').':'.$val['option']:'').'] '.($val['required']?_t('Required'):_t('Optional')).($val['select_multiple']?' '._t('Multiple'):'').'<br />';
endforeach;?></td>
	<td data-label="<?php _e('Admin');?>" class="td_admin"><a title="<?php _e('Delete');?>" class="action_delete" href="javascript:void(0);"><?php _e('Delete');?></a> <a title="<?php _e('Modify');?>" class="action_modify" href="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'form','mode'=>'insert','id'=>$row['id']));?>"><?php _e('Modify');?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
<input type="submit" value="<?php _e('Bulk Delete');?>" class="btn_submit">
<input type="button" value="<?php _e('Create');?>" class="back" url="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'form','mode'=>'insert'));?>">
</form>
<?php echo $data['pager']['list_put'];?>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('.checkall','.ck_item');
		_('.action_delete').bind('click',function(){
			_('.ck_item').prop('checked',false);
			_(this).parent().parent().find('.ck_item').prop('checked',true);
			_('.btn_submit').run('click');
		});

		_('#bulk_form').bind('submit',function(event){
			_.stopevent(event);
			var no = 0;   
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					no += 1;
				}
			});
			if(no > 0){
				if(!confirm('<?php _e('Are you sure delete it?');?>')){
					return ;
				}
			}else{
				alert('<?php _e('No Record Selected');?>.');
				return ;
			}
			});
	});
//-->
</script>