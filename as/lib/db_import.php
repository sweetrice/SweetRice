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
<h2><?php echo $db_file.DATAIMPORT_TIP1;?></h2>
<p><?php echo $import_error;?></p>
<br />
<?php
	}
?>
<h2><?php echo DATAIMPORT_TIP2;?></h2>
<span id="deleteTip"></span>
<form method="post" id="bulk_form" action="./?type=data&mode=db_import&form_mode=bulk">
<div id="tbl">
<table>
<thead><tr><td><input type="checkbox" id="checkall"/> <a href="javascript:void(0);" class="btn_sort" data="name"><?php echo NAME;?></a></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
if(file_exists($db_backup_dir)){
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
	<tr class="<?php echo $classname?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $entry;?>"/> <a href="./?type=data&mode=db_import&db_file=<?php echo $entry;?>&form_mode=import" onclick="if(confirm('<?php echo DATAIMPORT_TIPS;?>'))return;else return false;"><span id="name_<?php echo $no;?>"><?php echo $entry;?></span></a> (<?php echo number_format(filesize($db_backup_dir.'/'.$entry));?> bytes)</td>
	<td><span id="action_<?php echo $no;?>"></span>
	<a title="<?php echo DELETE_TIP;?>" class="action_delete" data="<?php echo $entry;?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
	<a title="<?php echo SAVE;?>" class="action_save" href="./?type=data&mode=db_import&db_file=<?php echo $entry;?>&form_mode=save"><?php echo SAVE;?></a> 
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
<input type="submit" value=" <?php echo BULK.' '.DELETE_TIP;?>">
</form>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){
			if(!confirm("<?php echo(DELETE_CONFIRM);?>")) return; deleteAction("db_backup",_(this).attr('data'),_(this).attr('no'));
		});
		_('#bulk_form').bind('submit',function(event){
			var no = 0;   
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					no += 1;
				}
			});
			if(no == 0){
				alert("<?php echo NO_RECORD_SELECTED;?>.");
				_().stopevent(event);
			}
		});
	});
//-->
</script>