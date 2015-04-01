<?php
/**
 * Links management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
	defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="permalinks"/>
<input type="hidden" name="linkType" value="custom"/>
<input type="text" name="search" value="<?php echo escape_string($_GET['search']);?>" placeholder="<?php _e('Keywords');?>"/>
<input type="submit" value="<?php _e('Search');?>"/>
</form>
<?php echo $pager['list_put'];?>
<form method="post" id="bulk_form" action="./?type=permalinks&linkType=custom&mode=bulk">
<table>
<thead><tr><td><input type="checkbox" id="checkall"/> <?php _e('URL');?></td><td><?php _e('Request');?></td><td><?php _e('Plugin');?></td><td class="td_admin"><?php _e('Admin');?></td></tr></thead>
<?php
$no = 0;
	foreach($rows as $row){
		$no +=1;
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
		$reqs = unserialize($row['request']);
		if($reqs){
			$original_url = BASE_URL.'?';
			foreach($reqs as $key=>$val){
				$original_url .= $key.'='.$val.'&';
			}
			$original_url = substr($original_url,0,-1);			
		}else{
			$original_url = BASE_URL.$row['url'];
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>">
<td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span>
<input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['lid'];?>"/> <a href="<?php echo BASE_URL.$row['url'];?>"><?php echo BASE_URL.$row['url'];?></a>
</td><td><a href="<?php echo $original_url;?>"><?php echo $original_url;?></a></td>
<td><?php echo $row['plugin'];?></td>
<td class="td_admin">
<span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $row['lid'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=permalinks&linkType=custom&mode=insert&id=<?php echo $row['lid'];?>"><?php _e('Modify');?></a> 
</td></tr>
<?php
	}
?>
</table>
<input type="submit" value=" <?php _e('Bulk Delete');?> ">  <input type="button" value="<?php _e('Create');?>" class="back" url="./?type=permalinks&linkType=custom&mode=insert">
</form>
<?php echo $pager['list_put'];?>

<script type="text/javascript">
<!--
	_().ready(function(){
		_('.action_delete').bind('click',function(){
			if(confirm('<?php _e('Are you sure delete it?');?>')) deleteAction('links',_(this).attr('data'),_(this).attr('no'));
		});
		bind_checkall('#checkall','.ck_item');
		_('#bulk_form').bind('submit',function(event){
		var no = 0;   
		_('.ck_item').each(function(){
			if (_(this).prop('checked')){
				no += 1;
			}
		});
		if(no > 0){
			if(!confirm('<?php _e('Are you sure delete it?');?>')){
				_().stopevent(event);
			}
		}else{
			alert('<?php _e('No Record Selected');?>');
			_().stopevent(event);
		}
		});
	});
//-->
</script>