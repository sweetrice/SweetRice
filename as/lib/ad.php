<?php
/**
 * Ad management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
?>
<strong><?php echo CREAT_AD_TIP;?></strong>
<div id="tbl">
<table>
<thead><tr><td><a href="javascript:void(0);" class="btn_sort" data="name"><?php echo NAME;?></a></td><td><?php echo QUOTE_CODE;?></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
$no = 0;
	foreach($ads as $val){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>">
<td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><span id="name_<?php echo $no;?>"><?php echo $val;?></span></td><td>
<?php highlight_string('<script type="text/javascript" src="'.BASE_URL.show_link_ads($val).'"></script>');?></td>
<td class="td_admin">
<span id="action_<?php echo $no;?>"></span>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" onclick="if(confirm('<?php echo DELETE_CONFIRM;?>')) deleteAction('ad','<?php echo $val;?>',<?php echo $no;?>); else return false;" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
<a title="<?php echo MODIFY;?>" class="action_modify" href="./?type=ad&adk=<?php echo $val;?>"><?php echo MODIFY;?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<form method="post" action="./?type=ad&mode=save">
<fieldset><legend><strong><?php echo ADS_NAME;?>:</strong></legend>
<input type="text" name="adk" value="<?php echo $adk;?>" class="input_text"/>
</fieldset>
<fieldset><legend><strong><?php echo ADS_CODE;?>:</strong></legend>
<textarea name="adv" class="ad"><?php echo $adv;?></textarea>
</fieldset>
<input type="submit" value=" <?php echo DONE;?> " class="input_submit"/>
</form>

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