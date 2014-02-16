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
	<input type="text" name="search" value="<?php echo $search;?>"/> <select name="category">
<option value="all"> -- <?php echo ALL.' '.CATEGORY;?> -- </option>
<option value="0" <?php echo $s_category[0];?>> <?php echo UNCATEGORY;?> </option>
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
<input type="submit" value="<?php echo SEARCH;?>"/>
</form>
<form method="post" id="bulk_form" action="./?type=post&mode=bulk">
<div id="tbl">
<table>
<thead><tr><td align="left"><input type="checkbox" id="checkall"/> <a href="javascript:void(0);" data="name" class="btn_sort"><?php echo NAME;?></a></td><td><a href="javascript:void(0);" data="category" class="btn_sort"><?php echo CATEGORY;?></a></td><td><a href="javascript:void(0);" data="date" class="btn_sort"><?php echo TIMES;?></a></td><td><a href="javascript:void(0);" data="comments" stt="number" class="btn_sort"><?php echo COMMENT;?></a></td><td><?php echo PUBLISH;?></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
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
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/> <a href="<?php echo BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>" target="_blank"><span id="name_<?php echo $no;?>"><?php echo $row['name'];?></span></a></td><td><span id="category_<?php echo $no;?>"><?php echo $categories[$row['category']]['name'];?></span></td><td><span id="date_<?php echo $no;?>" class="sortNo"><?php echo $row['date'];?></span><?php echo date(DATE_FORMAT,$row['date']);?></td><td><span id="comments_<?php echo $no;?>"><?php echo number_format($cmtRows[$row['id']]);?></span></td><td><?php echo $row['in_blog']?'Yes':'no';?></td><td><span id="action_<?php echo $no;?>"></span>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
<a title="<?php echo MODIFY;?>" class="action_modify" href="./?type=post&mode=modify&id=<?php echo $row['id'];?>"><?php echo MODIFY;?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<div class="mg5">
<select name="paction" id="paction">
<option value=""><?php echo BULK.' '.ACTION;?></option>
		<option value="pdelete" ><?php echo DELETE_TIP;?></option>
		<option value="pmodify"><?php echo MODIFY;?></option>
	</select>
<div id="pmodify" style="display:none;">
<fieldset><legend><?php echo BULK.' '.MODIFY;?></legend>
<?php echo CATEGORY;?> : <select name="pcat">
<option value="no"><?php echo DO_NOT_CHANGE;?></option>
<option value="0"><?php echo UNCATEGORY;?></option>
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
<?php echo PUBLISH;?> : 
<select name="in_blog">
	<option value="3"><?php echo DO_NOT_CHANGE;?></option>
	<option value="0">No</option>
	<option value="1">Yes</option>
</select>
<?php echo ALLOW_COMMENT;?> : 
<select name="allow_comment">
	<option value="3"><?php echo DO_NOT_CHANGE;?></option>
	<option value="0">No</option>
	<option value="1">Yes</option>
</select>
<?php echo TEMPLATE;?> : 
<select name="template">
	<option value=""><?php echo DO_NOT_CHANGE;?></option>
<?php foreach($template as $key=>$val){
		$s = '';
		if($key==$row['template']){
			$s = 'selected';
		}
		echo '<option value="'.$key.'" '.$s.'>'.$val.'</option>';
	}
?>
</select>
</fieldset>
</div>
</div>
<div class="mg5"><input type="submit" value=" <?php echo DONE;?> ">  <input type="button" value="<?php echo CREATE;?>" onclick="location.href='./?type=post&mode=insert';"></div>
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
			if(!confirm('<?php echo(DELETE_CONFIRM);?>')) return; deleteAction("post",_(this).attr('data'),_(this).attr('no'));
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
				if (!confirm('<?php echo(DELETE_CONFIRM);?>')){
					_().stopevent(event);
				}
			}
			if (!paction || !ckd){
				if (!ckd){
					alert('<?php echo NO_RECORD_SELECTED;?>');
				}
				if (!paction){
					alert('<?php echo NO_PACTION_SELECTED;?>');
				}
				_().stopevent(event);
			}
		});
	});
//-->
</script>