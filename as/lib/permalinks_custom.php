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
<input type="text" name="search" value="<?php echo $search;?>"/>
<input type="submit" value="<?php echo SEARCH;?>"/>
</form>
<?php echo $pager['list_put'];?>
<form method="post" action="./?type=permalinks&linkType=custom&mode=bulk">
<table>
<thead><tr><td><input type="checkbox" id="checkall"/> <?php echo URL;?></td><td><?php echo REQUEST;?></td><td><?php echo PLUGIN;?></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<?php
$no = 0;
	foreach($rows as $row){
		$no +=1;
		if($classname=='tr_sigle'){
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
<a title="<?php echo DELETE_TIP;?>" class="action_delete" onClick="if(confirm('<?php echo DELETE_CONFIRM;?>')) deleteAction('links','<?php echo $row['lid'];?>',<?php echo $no;?>); else return false;" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
<a title="<?php echo MODIFY;?>" class="action_modify" href="./?type=permalinks&linkType=custom&mode=insert&id=<?php echo $row['lid'];?>"><?php echo MODIFY;?></a> 
</td></tr>
<?php
	}
?>
</table>
<input type="submit" value=" <?php echo BULK.' '.DELETE_TIP;?> " onclick="if(postBulk()){return true;}else{return false;}">  <input type="button" value="<?php echo CREATE;?>" onclick="location.href='./?type=permalinks&linkType=custom&mode=insert';">
</form>
<?php echo $pager['list_put'];?>

<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
	});
//-->
</script>