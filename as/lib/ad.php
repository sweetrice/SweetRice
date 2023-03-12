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
<form method="post" id="bulk_form" action="./?type=ad&mode=bulk">
<div>
<ul class="ads toggle-list">
<?php
$no = 0;
	foreach($ads as $val){
		$no +=1;
?>
<li id="li_<?php echo $no;?>">
<div class="ads_list">
<h3><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $val;?>"/> <?php echo $val;?></h3>
<div class="ads_content">
<?php highlight_string('<script type="text/javascript" src="'.BASE_URL.show_link_ads($val).'"></script>');?>
</div>
<p><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $val;?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=ad&adk=<?php echo $val;?>"><?php _e('Modify');?></a> </p>
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
		bind_checkall('.checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
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