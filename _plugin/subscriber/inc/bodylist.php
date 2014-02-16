<?php
/**
 * Email body management template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<input type="button" value="<?php echo POSTEMAIL;?>" onclick='location.href="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'post'));?>";'>
<table>
	<tr class="tr_head"><td><?php echo NO;?></td><td><?php echo SUBJECT;?></td><td><?php echo TOTAL;?></td><td><?php echo DATE_TIP;?></td><td><?php echo ADMIN;?></td></tr>
<?php
	foreach($ml AS $mls ){
		$no +=1;
		if($bgcolor=='#F1F1F1'){
			$bgcolor = '#F8F8F3';
		}else{
			$bgcolor='#F1F1F1';
		}
?>
<tr onmouseover="this.style.backgroundColor='#E0E8F1';" onmouseout="this.style.backgroundColor='<?php echo $bgcolor;?>';" style="background-color:<?php echo $bgcolor;?>;"><td><?php echo $no;?></td><td><a href="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'viewbody','id'=>$mls['id']));?>"><?php echo $mls['subject'];?></a></td><td><?php echo $mls['total'];?></td><td><?php echo date('m/d/Y H:i:s',$mls['date']);?></td><td>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" onClick='if(confirm("<?php echo DELETE_CONFIRM;?>")) deleteAction("category","<?php echo $row['id'];?>",<?php echo $no;?>); else return false;' href="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'delete_body','id'=>$mls['id']));?>"><?php echo DELETE_TIP;?></a> 
</td></tr>
<?php
	}
?>
</table>