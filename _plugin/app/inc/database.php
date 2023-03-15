<?php
/**
 * Database example management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="plugin"/>
<input type="hidden" name="plugin" value="<?php echo THIS_APP; ?>"/>
<input type="hidden" name="app_mode" value="database"/>
<input type="text" name="keyword" value="<?php echo $_GET['keyword']; ?>"/>
<input type="submit" value="<?php _e('Search');?>"/>
</form>
<?php echo $data['pager']['list_put']; ?>
<form method="post" id="bulk_form" action="<?php echo pluginDashboardUrl(THIS_APP, array('app_mode' => 'database', 'mode' => 'bulk')); ?>">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl; ?>"/>
<table>
<thead>
	<tr><th class="data_no"><input type="checkbox" class="checkall"/></th><th><?php _e('ID');?></th><th class="max50"><?php _e('Content');?></th><th style="width:60px;"><?php _e('Admin');?></th></tr>
</thead>
<tbody>
<?php
foreach ($data['rows'] as $row) {
    if ($classname == 'tr_sigle') {
        $classname = 'tr_double';
    } else {
        $classname = 'tr_sigle';
    }
    ?>
<tr class="<?php echo $classname; ?>"><td><input type="checkbox" name="plist[]" value="<?php echo $row['id']; ?>" class="ck_item"/></td><td><?php echo $row['id']; ?></td><td class="max50"><?php echo $row['content']; ?></td><td>
<a title="<?php _e('Delete');?>" class="action_delete" href="javascript:void(0);"><?php _e('Delete');?></a> <a title="<?php _e('Modify');?>" class="action_modify" href="<?php echo pluginDashboardUrl(THIS_APP, array('app_mode' => 'database', 'mode' => 'insert', 'id' => $row['id'])); ?>"><?php _e('Modify');?></a>
</td></tr>
<?php
}
?>
</tbody>
</table>
<input type="submit" value="<?php _e('Bulk Delete');?>" class="btn_submit">
<input type="button" value="<?php _e('Create');?>" class="back" url="<?php echo pluginDashboardUrl(THIS_APP, array('app_mode' => 'database', 'mode' => 'insert')); ?>">
</form>
<?php echo $data['pager']['list_put']; ?>
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
			from_bulk(this,function(){
				_('.ck_item').each(function(){
					if (_(this).prop('checked')){
						var _this = this;
						_(this).fadeOut(500,function(){
							_(_this).parent().parent().remove();
						});
					}
				});
			});
		});
	});
//-->
</script>