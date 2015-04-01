<?php
/**
 * Entry management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
 $s_category[$category] = 'selected';
?>
<?php echo $pager['list_put'];?>
<form method="get" action="./">
<input type="hidden" name="type" value="post"/>
	<input type="text" name="search" value="<?php echo escape_string($_GET['search']);?>" placeholder="<?php _e('Keywords');?>"/> <select name="category">
<option value="all"> -- <?php _e('All Categories');?> -- </option>
<option value="0" <?php echo $s_category[0];?>> <?php _e('Uncategory');?> </option>
<?php
	foreach($subCategory as $val){
		$_prefix = '';
		for($i=0; $i<$val['level']; $i++){
			$_prefix .= '-- ';
		}
		echo '<option value="'.$val['id'].'" '.$s_category[$val['id']].'>'.$_prefix.$categories[$val['id']]['name'].'</option>';
	}
?>
</select>
<input type="submit" value="<?php _e('Search');?>"/>
</form>
<form method="post" id="bulk_form" action="./?type=post&mode=bulk">
<div id="tbl">
<table>
<thead><tr><td align="left"><input type="checkbox" id="checkall"/> <a href="javascript:void(0);" data="name" class="btn_sort"><?php _e('Name');?></a></td><td><a href="javascript:void(0);" data="category" class="btn_sort"><?php _e('Category');?></a></td><td><a href="javascript:void(0);" data="date" class="btn_sort"><?php _e('Time');?></a></td><td><a href="javascript:void(0);" data="comments" stt="number" class="btn_sort"><?php _e('Comments');?></a></td><td><?php _e('Publish');?></td><td class="td_admin"><?php _e('Admin');?></td></tr></thead>
<tbody>
<?php
$no = 0;
	foreach($rows AS $row){
		$no +=1;
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/> <a href="<?php echo BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>" target="_blank"><span id="name_<?php echo $no;?>"><?php echo $row['name'];?></span></a></td><td><span id="category_<?php echo $no;?>"><?php echo $categories[$row['category']]['name'];?></span></td><td><span id="date_<?php echo $no;?>" class="sortNo"><?php echo $row['date'];?></span><?php echo date(_t('M d Y H:i'),$row['date']);?></td><td><span id="comments_<?php echo $no;?>"><?php echo number_format($cmtRows[$row['id']]);?></span></td><td><?php echo $row['in_blog']?_t('Yes'):_t('No');?></td><td><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=post&mode=modify&id=<?php echo $row['id'];?>"><?php _e('Modify');?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<div class="mg5">
<select name="paction" id="paction">
<option value=""><?php _e('Bulk Action');?></option>
		<option value="pdelete" ><?php _e('Delete');?></option>
		<option value="pmodify"><?php _e('Modify');?></option>
	</select>
<div id="pmodify" style="display:none;">
<fieldset><legend><?php _e('Bulk Modify');?></legend>
<?php _e('Category');?> : <select name="pcat">
<option value="no"><?php _e('Do Not Change');?></option>
<option value="0"><?php _e('Uncategory');?></option>
<?php
$s_category[$category] = 'selected';
	foreach($subCategory as $val){
		$_prefix = '';
		for($i=0; $i<$val['level']; $i++){
			$_prefix .= '-- ';
		}
		echo '<option value="'.$val['id'].'" '.$s_category[$val['id']].'>'.$_prefix.$categories[$val['id']]['name'].'</option>';
	}
?>
</select>
<?php _e('Publish');?> : 
<select name="in_blog">
	<option value="3"><?php _e('Do Not Change');?></option>
	<option value="0"><?php _e('No');?></option>
	<option value="1"><?php _e('Yes');?></option>
</select>
<?php _e('Allow Comment');?> : 
<select name="allow_comment">
	<option value="3"><?php _e('Do Not Change');?></option>
	<option value="0"><?php _e('No');?></option>
	<option value="1"><?php _e('Yes');?></option>
</select>
<?php _e('Template');?> : 
<select name="template">
	<option value=""><?php _e('Do Not Change');?></option>
<?php foreach($template as $key=>$val){
		$s = '';
		if($key == $row['template']){
			$s = 'selected';
		}
		echo '<option value="'.$key.'" '.$s.'>'.$val.'</option>';
	}
?>
</select>
</fieldset>
</div>
</div>
<div class="mg5"><input type="submit" value=" <?php _e('Done');?> ">  <input type="button" value="<?php _e('Create');?>" url="./?type=post&mode=insert" class="back"></div>
</form>
<?php echo $pager['list_put'];?>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('#paction').bind('change',function(){
			if(_(this).val() == 'pmodify'){
				_('#pmodify').show();
			}else{
				_('#pmodify').hide();
			}
		});
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){
			if(!confirm('<?php _e('Are you sure delete it?');?>')) return; deleteAction('post',_(this).attr('data'),_(this).attr('no'));
		});
		_('#bulk_form').bind('submit',function(event){
			var ckd = false;   
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					ckd = true;
				}
			});
			var paction = _('#paction').val();
			if (paction == 'pdelete' && ckd){
				if (!confirm('<?php _e('Are you sure delete it?');?>')){
					_().stopevent(event);
					return ;
				}
			}
			if (!paction || !ckd){
				if (!ckd){
					alert('<?php _e('No Record Selected');?>');
					_().stopevent(event);
					return ;
				}
				if (!paction){
					alert('<?php _e('No bulk action selected');?>');
					_().stopevent(event);
					return ;
				}
			}
		});
	});
//-->
</script>