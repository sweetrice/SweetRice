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
<strong><?php _e('You can edit ads code and put it to template,or you can directly edit template <a href="./?type=theme">here</a>');?></strong>
<div id="tbl">
<table>
<thead><tr><td><a href="javascript:void(0);" class="btn_sort" data="name"><?php _e('Name');?></a></td><td><?php _e('Quote Code');?></td><td class="td_admin"><?php echo _e('Admin');?></td></tr></thead>
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
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $val;?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=ad&adk=<?php echo $val;?>"><?php _e('Modify');?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<form method="post" action="./?type=ad&mode=save">
<fieldset><legend><strong><?php _e('Ads name');?>:</strong></legend>
<input type="text" name="adk" value="<?php echo $adk;?>" class="input_text"/>
</fieldset>
<fieldset><legend><strong><?php _e('Ads code');?>:</strong></legend>
<textarea name="adv" class="ad"><?php echo $adv;?></textarea>
</fieldset>
<input type="submit" value=" <?php _e('Done');?> " class="input_submit"/>
</form>

<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){
			if(confirm('<?php _e('Are you sure delete it?');?>')) deleteAction('ad',_(this).attr('data'),_(this).attr('no'));
		});
	});
//-->
</script>