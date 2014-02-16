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
<thead><tr><td><a href="javascript:void(0);" class="btn_sort" data="domain"><?php echo HOST;?></a></td><td><?php echo SITE_CONFIGURATION;?></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
$no = 0;
	foreach($site_list as $key=>$val){
		$no += 1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><a href="http://<?php echo $key.substr(BASE_URL,strlen($_SERVER["HTTP_HOST"])+7);?>"><span id="domain_<?php echo $no;?>"><?php echo $key;?></span></a></td>
<td>
<div style="margin:10px;">
<?php echo DATABASE.' : <strong>'.$val['database_type'].'</strong>';?>  
<?php
	if($val['database_type'] != 'sqlite'){
		echo DATA_ACCOUNT.' : <strong>'.$val['db_user'].'</strong> '.DATABASE_HOST.': <strong>'.$val['db_url'].'</strong> '.DATA_PORT.' : <strong>'.$val['db_port'].'</strong>';
	}
?> 
<?php echo DATA_NAME.' : <strong>'.$val['db_name'].'</strong>';?> <?php echo DATA_PREFIX.' : <strong>'.$val['db_left'].'</strong>';?>
</div>
</td>
<td><span id="action_<?php echo $no;?>"></span>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" onClick='if(confirm("<?php echo(DELETE_CONFIRM);?>")) deleteAction("sites","<?php echo $key;?>",<?php echo $no;?>); else return false;' href="javascript:void(0);"><?php echo DELETE_TIP;?></a></td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<input type="button" value="<?php echo CREATE;?>" onclick="location.href='./?type=sites&mode=insert';">
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
	});
//-->
</script>