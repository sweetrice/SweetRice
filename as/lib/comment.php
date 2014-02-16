<?php
/**
 * Comment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="comment"/>
	<input type="text" name="search" value="<?php echo $search;?>"/> <input type="submit" value="<?php echo SEARCH;?>"  class="input_submit"/>
</form>
<?php echo $pager['list_put'];?>
<div class="mg5 pd5"><input type="checkbox" class="checkall"/> <?php echo ALL;?></div>
<form method="post" id="bulk_form" action="./?type=comment&mode=bulk">
<div>
	<ul class="comments">
<?php
	$no = 0;
	$old_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR,'value="_plugin/','data="_plugin/','src="_plugin/');
	$new_link = array('src="'.SITE_URL.ATTACHMENT_DIR,'data="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.'_plugin/','data="'.SITE_URL.'_plugin/','src="'.SITE_URL.'_plugin/');
	foreach($rows as $row){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
		$info = str_replace($old_link,$new_link,$row['info']);
?>
<li class="<?php echo $classname;?>" id="li_<?php echo $no;?>"><span id="name_<?php echo $no;?>"><?php echo date(DATE_FORMAT,$row['date']);?></span>
<div class="comment_list">
<h3><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/> <?php echo NO;?><?php echo $no;?> <?php echo POST;?> : <a href="<?php echo SITE_URL.show_link_page($row['post_cat'],$row['post_slug']);?>"><?php echo $row['post_name'];?></a> -- by <a href="<?php echo $row['website'];?>"><?php echo $row['name'];?></a> @ <?php echo date(DATE_FORMAT,$row['date']);?></h3>
<div class="comment_content">
<?php echo $info;?>
</div>
<p><span id="action_<?php echo $no;?>"></span>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php echo DELETE_TIP;?></a> 
<a title="<?php echo MODIFY;?>" class="action_modify" href="./?type=comment&mode=view&id=<?php echo $row['id'];?>"><?php echo MODIFY;?></a> </p>
</div>
</li>
<?php
	}
?>
</ul>
</div>
<div class="mg5 pd5">
<input type="checkbox" class="checkall"/> <?php echo ALL;?> <input type="submit" value=" <?php echo BULK.' '.DELETE_TIP;?>" class="btn_submit" ></div>
</form>
<?php echo $pager['list_put'];?>

<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('.checkall','.ck_item');
		_('.action_delete').bind('click',function(){
			if(!confirm("<?php echo(DELETE_CONFIRM);?>")) return; deleteAction("comment",_(this).attr('data'),_(this).attr('no'));
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