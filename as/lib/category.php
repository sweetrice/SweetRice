<?php
/**
 * Category list template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="category"/>
	<input type="text" name="search" value="<?php echo $search;?>"/> <input type="submit" value="<?php echo SEARCH;?>" class="input_submit"/>
</form>
<form method="post" id="bulk_form" action="./?type=category&mode=bulk">
<div id="tbl">
<table>
<thead><tr><td align="left"><input type="checkbox" id="checkall"/> <a href="javascript:void(0);" class="btn_sort" data="name"><?php echo CAT_NAME;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="slug"><?php echo SLUG;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="parent"><?php echo PARENT;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="title"><?php echo TITLE;?></a></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
$no = 0;
foreach($subCategory as $row){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/> 
<?php
for($i=0; $i<$row['level']; $i++){
	echo '-- ';
}	
?><a href="<?php echo BASE_URL.show_link_cat($categories[$row['id']]['link'],'');?>" target="_blank"><span id="name_<?php echo $no;?>"><?php echo $categories[$row['id']]['name'];?></span></a></td><td><span id="slug_<?php echo $no;?>"><?php echo $categories[$row['id']]['link'];?></span></td><td><span id="parent_<?php echo $no;?>"><?php echo $categories[$row['id']]['parent_id']?$categories[$categories[$row['id']]['parent_id']]['name']:'Main';?></span></td><td><span id="title_<?php echo $no;?>"><?php echo $categories[$row['id']]['name'];?></span></td><td>
<span id="action_<?php echo $no;?>"></span>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
<a title="<?php echo MODIFY;?>" class="action_modify" href="./?type=category&mode=modify&id=<?php echo $row['id'];?>"><?php echo MODIFY;?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<input type="submit" value="<?php echo BULK.' '.DELETE_TIP;?>"> <input type="button" value="<?php echo CREATE;?>" onclick="location.href='./?type=category&mode=insert';"> </form>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){
			if(!confirm("<?php echo(DELETE_CONFIRM);?>")) return; deleteAction("category",_(this).attr('data'),_(this).attr('no'));
		});
		_('#bulk_form').bind('submit',function(event){
		var no = 0;   
		_('.ck_item').each(function(){
			if (_(this).prop('checked')){
				no += 1;
			}
		});
		if(no > 0){
			if(!confirm("<?php echo(DELETE_CONFIRM);?>")){
				_().stopevent(event);
			}
		}else{
			alert("<?php echo NO_RECORD_SELECTED;?>.");
			_().stopevent(event);
		}
		});
	});
//-->
</script>