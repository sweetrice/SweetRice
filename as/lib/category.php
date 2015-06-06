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
	<input type="text" name="search" value="<?php echo escape_string($_GET['search']);?>" placeholder="<?php _e('Keywords');?>"/> <input type="submit" value="<?php _e('Search');?>" class="input_submit"/>
</form>
<?php echo $data['pager']['list_put'];?>
<form method="post" id="bulk_form" action="./?type=category&mode=bulk">
<div id="tbl">
<table>
<thead><tr><th class="data_no"><input type="checkbox" id="checkall"/></th><th class="max50"><a href="javascript:void(0);" class="btn_sort" data="name"><?php _e('Name');?></a></th><th class="media_content"><a href="javascript:void(0);" class="btn_sort" data="slug"><?php _e('Slug');?></a></th><th class="media_content"><a href="javascript:void(0);" class="btn_sort" data="parent"><?php echo _e('Parent');?></a></th><th class="media_content"><a href="javascript:void(0);" class="btn_sort" data="title"><?php _e('Title');?></a></th><th class="td_admin"><?php _e('Admin');?></th></tr></thead>
<tbody>
<?php
$no = 0;
foreach($data['rows'] as $row){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/></td><td class="max50"><a href="<?php echo BASE_URL.show_link_cat($row['link'],'');?>" target="_blank"><span id="name_<?php echo $no;?>"><?php echo $row['name'];?></span></a></td><td class="media_content"><span id="slug_<?php echo $no;?>"><?php echo $row['link'];?></span></td><td class="media_content"><span id="parent_<?php echo $no;?>"><a href="<?php echo show_link_cat($categories[$row['parent_id']]['link']);?>"><?php echo $row['parent_id']?$categories[$row['parent_id']]['name']:_t('Main');?></a></span></td><td class="media_content"><span id="title_<?php echo $no;?>"><?php echo $row['title'];?></span></td><td>
<span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=category&mode=modify&id=<?php echo $row['id'];?>"><?php _e('Modify');?></a> 
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<input type="submit" value="<?php _e('Bulk Delete');?>" class="btn_submit"> <input type="button" value="<?php _e('Create');?>" class="back" url="./?type=category&mode=insert"> <input type="button" value="<?php _e('Back');?>" class="back" url="./?type=category"></form>
<?php echo $data['pager']['list_put'];?>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){	
			_(this).parent().parent().find('.ck_item').prop('checked',true);
			_('.btn_submit').run('click');
		});
		_('#bulk_form').bind('submit',function(event){
		_.stopevent(event);
		var no = 0;   
		_('.ck_item').each(function(){
			if (_(this).prop('checked')){
				no += 1;
			}
		});
		if(no > 0){
			if(!confirm('<?php _e('Are you sure delete it?');?>')){
				return ;
			}
		}else{
			alert('<?php _e('No Record Selected');?>');
			return ;
		}
		from_bulk(this,function(){
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					var _this = this;
					_(this).fadeOut(500,function(){
						_(_this).parent().parent().remove();
					});
				}
			});
		});
		});
	});
//-->
</script>