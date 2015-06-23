<?php
/**
 * Database import/restore.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	if($import){
?>
<div class="tip"><?php echo $db_file._t(' has been import into your database ,any message maybe here:');?>
<div><?php echo $import_error;?></div></div>
<?php
	}
?>
<div class="tip"><?php _e('Please select backup file to import or save.');?></div>
<span id="deleteTip"></span>
<form method="post" id="bulk_form" action="./?type=data&mode=db_import&form_mode=bulk">
<div id="tbl">
<table>
<thead><tr><th class="data_no"><input type="checkbox" id="checkall"/></th><th class="max50"><a href="javascript:void(0);" class="btn_sort" data="name"><?php _e('Name');?></a></th><th class="td_admin"><?php _e('Admin');?></th></tr></thead>
<tbody>
<?php
if(is_dir($db_backup_dir)){
	$d = dir($db_backup_dir);
	$no = 0;
	while (false !== ($entry = $d->read())) {
	   if($entry !='.' && $entry !='..'){
			$no += 1;
			if($classname=='tr_sigle'){
				$classname = 'tr_double';
			}else{
				$classname='tr_sigle';
			}
?>
	<tr class="<?php echo $classname?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $entry;?>"/></td><td class="max50"><a href="javascript:void(0);" url="./?type=data&mode=db_import&db_file=<?php echo $entry;?>&form_mode=import" class="btn_import"><span id="name_<?php echo $no;?>"><?php echo $entry;?></span></a> (<?php echo number_format(filesize($db_backup_dir.'/'.$entry));?> bytes)</td>
	<td><span id="action_<?php echo $no;?>"></span>
	<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $entry;?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
	<a title="<?php _e('Save');?>" class="action_save" href="./?type=data&mode=db_import&db_file=<?php echo $entry;?>&form_mode=save"><?php _e('Save');?></a> 
	</td></tr>
<?php
		}
	}
	$d->close();
}
?>
</tbody>
</table>
</div>
<input type="submit" value=" <?php _e('Bulk Delete');?>" class="btn_submit">
</form>
<form method="post" action="./?type=data&mode=upload" enctype="multipart/form-data" >
	<input type="file" name="dbfile" /> <input type="submit" value="<?php _e('Done');?>"/>
</form>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_.ready(function(){
		_('.btn_import').bind('click',function(){
			if(confirm('<?php _e('Are you sure replace your database to this data version?');?>')){
				location.href = _(this).attr('url');
			}
		});
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
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
			if(no == 0){
				alert('<?php _e('No Record Selected');?>.');
				_.stopevent(event);
			}
			if(!confirm('<?php _e('Are you sure delete it?');?>')) return; 
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