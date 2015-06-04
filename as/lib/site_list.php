<?php
/**
 * Sites management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
?>
<div id="tbl">
<table>
<thead><tr><th class="max50"><a href="javascript:void(0);" class="btn_sort" data="domain"><?php _e('Host');?></a></th><th><?php _e('Website Configuration');?></th><th class="td_admin"><?php _e('Admin');?></th></tr></thead>
<tbody>
<?php
$no = 0;
	foreach($site_list as $key=>$val){
		$no += 1;
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td class="max50"><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><a href="http://<?php echo $key.substr(BASE_URL,strlen($_SERVER['HTTP_HOST'])+7);?>"><span id="domain_<?php echo $no;?>"><?php echo $key;?></span></a></td>
<td>
<div style="margin:10px;">
<?php echo _t('Database').' : <strong>'.$val['database_type'].'</strong>';?>  
<?php
	if($val['database_type'] != 'sqlite'){
		echo _t('Database Account').' : <strong>'.$val['db_user'].'</strong> '._t('Database Host').': <strong>'.$val['db_url'].'</strong> '._t('Database Port').' : <strong>'.$val['db_port'].'</strong>';
	}
?> 
<?php echo _t('Database Name').' : <strong>'.$val['db_name'].'</strong>';?> <?php echo _t('Database Prefix').' : <strong>'.$val['db_left'].'</strong>';?>
</div>
</td>
<td><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $key;?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a></td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<input type="button" value="<?php _e('Create');?>" class="back" url="./?type=sites&mode=insert">
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){
			if(!confirm('<?php _e('Are you sure delete it?');?>')) return; deleteAction('sites',_(this).attr('data'),_(this).attr('no'));
		});
	});
//-->
</script>