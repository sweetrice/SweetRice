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
<form method="get" action="./">
<input type="hidden" name="type" value="post"/>
<div class="form_split"><input type="text" name="search" value="<?php echo escape_string($_GET['search']);?>" placeholder="<?php _e('Keywords');?>"/></div>
<div class="form_split">
<select name="category">
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
</div>
<div class="form_split">
<input type="submit" value="<?php _e('Search');?>"/>
</div>

</form>
<?php echo $pager['list_put'];?>
<form method="post" id="bulk_form" action="./?type=post&mode=bulk">
<div id="tbl">
<table>
<thead><tr>
<th class="data_no"><input type="checkbox" id="checkall"/></th>
<th class="max50"><a href="javascript:void(0);" data="name" class="btn_sort"><?php _e('Name');?></a></th>
<th class="media_content"><a href="javascript:void(0);" data="category" class="btn_sort"><?php _e('Category');?></a></th>
<th class="media_content"><a href="javascript:void(0);" data="date" class="btn_sort"><?php _e('Time');?></a></th>
<th><a href="javascript:void(0);" data="comments" stt="number" class="btn_sort"><?php _e('Comments');?></a></th>
<th><a href="javascript:void(0);" data="views" stt="number" class="btn_sort"><?php _e('Views');?></a></th>
<th><?php _e('Publish');?></th>
<th class="td_admin"><?php _e('Admin');?></th></tr></thead>
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
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>">
<td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/></td>
<td class="max50"><a href="<?php echo BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>" target="_blank"><span id="name_<?php echo $no;?>"><?php echo $row['name'];?></span></a></td>
<td class="media_content"><span id="category_<?php echo $no;?>"><?php echo $categories[$row['category']]['name'];?></span></td>
<td class="media_content"><span id="date_<?php echo $no;?>" class="sortNo"><?php echo $row['date'];?></span><?php echo date(_t('M d Y H:i'),$row['date']);?></td>
<td><span id="comments_<?php echo $no;?>"><?php echo $cmtRows[$row['id']];?></span></td>
<td><span id="views_<?php echo $no;?>"><?php echo $row['views'];?></span></td><td><?php echo $row['in_blog']?_t('Yes'):_t('No');?></td><td><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=post&mode=insert&id=<?php echo $row['id'];?>"><?php _e('Modify');?></a> 
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
<div class="form_split">
<span class="mw80"><?php _e('Category');?> : </span><select name="pcat">
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
</select></div>
<div class="form_split"><span class="mw80"><?php _e('Publish');?> :</span> 
<select name="in_blog">
	<option value="3"><?php _e('Do Not Change');?></option>
	<option value="0"><?php _e('No');?></option>
	<option value="1"><?php _e('Yes');?></option>
</select></div>
<div class="form_split"><span class="mw80"><?php _e('Allow Comment');?> : </span>
<select name="allow_comment">
	<option value="3"><?php _e('Do Not Change');?></option>
	<option value="0"><?php _e('No');?></option>
	<option value="1"><?php _e('Yes');?></option>
</select></div>
<div class="form_split"><span class="mw80"><?php _e('Template');?> : </span>
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
</select></div>
</fieldset>
</div>
</div>
<div class="mg5"><input type="submit" value=" <?php _e('Done');?> " class="btn_submit">  <input type="button" value="<?php _e('Create');?>" url="./?type=post&mode=insert" class="back"></div>
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
			_('.ck_item').prop('checked',false);	
			_(this).parent().parent().find('.ck_item').prop('checked',true);
			_('#paction').val('pdelete');
			_('.btn_submit').run('click');
		});
		_('#bulk_form').bind('submit',function(event){
			_.stopevent(event);
			var ckd = false;   
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					ckd = true;
				}
			});
			var paction = _('#paction').val();
			if (paction == 'pdelete' && ckd){
				if (!confirm('<?php _e('Are you sure delete it?');?>')){
					return ;
				}
			}
			if (!paction || !ckd){
				if (!ckd){
					alert('<?php _e('No Record Selected');?>');
					return ;
				}
				if (!paction){
					alert('<?php _e('No bulk action selected');?>');
					return ;
				}
			}
			from_bulk(this,function(){
				if (paction == 'pdelete')
				{
					_('.ck_item').each(function(){
						if (_(this).prop('checked')){
							var _this = this;
							_(this).fadeOut(500,function(){
								_(_this).parent().parent().remove();
							});
						}
					});
				}else{
					window.location.reload();
				}
			});
			return ;
		});
	});
//-->
</script>