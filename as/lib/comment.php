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
	<input type="text" name="search" value="<?php echo escape_string($_GET['search']);?>" placeholder="<?php _e('Keywords');?>"/> <input type="submit" value="<?php _e('Search');?>"  class="input_submit"/>
</form>
<?php echo $pager['list_put'];?>
<form method="post" id="bulk_form" action="./?type=comment&mode=bulk">
<div>
	<ul class="comments">
<?php
	$no = 0;
	foreach($rows as $row){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
		$info = toggle_attachment($row['info'],'dashboard');
?>
<li class="<?php echo $classname;?>" id="li_<?php echo $no;?>">
<div class="comment_list">
<h3><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['id'];?>"/> <a href="<?php echo SITE_URL.show_link_page($row['post_cat'],$row['post_slug']);?>"><?php echo $row['post_name'];?></a> -- <?php _e('By');?> <a href="<?php echo $row['website'];?>"><?php echo $row['name'];?></a> @ <?php echo date(_t('M d Y H:i'),$row['date']);?></h3>
<div class="comment_content">
<?php echo $info;?>
</div>
<p><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $row['id'];?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=comment&mode=view&id=<?php echo $row['id'];?>"><?php _e('Modify');?></a> </p>
</div>
</li>
<?php
	}
?>
</ul>
</div>
<div class="mg5 pd5">
<input type="checkbox" class="checkall"/> <?php _e('All');?> <input type="submit" class="btn_submit" value=" <?php _e('Bulk Delete');?>" class="btn_submit" ></div>
</form>
<?php echo $pager['list_put'];?>

<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('.checkall','.ck_item');
		_('.action_delete').bind('click',function(){	
			_('.ck_item').prop('checked',false);
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
							_(_this).parent().parent().parent().remove();
						});
					}
				});
			});
		});
	});
//-->
</script>