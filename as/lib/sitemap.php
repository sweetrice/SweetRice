<?php
/**
 * Sitemap management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
?>
<div class="sitemap_nav">
<label><a href="./?type=sitemap&mode=category"><?php _e('Category');?></a> <a href="./?type=sitemap&mode=post"><?php _e('Post');?></a> <a href="./?type=sitemap&mode=custom"><?php _e('Custom');?></a></label>
</div>
<?php echo $data['pager']['list_put'];?>
<span id="deleteTip"></span>
<form method="post" action="./?type=sitemap&mode=hide">
<input type="hidden" id="submode" name="submode"/>
<div id="tbl">
<table>
<thead><tr><th style="text-align:left;"><input type="checkbox" id="checkall"/></th><th class="max50"><?php _e('URL');?></th><th class="media_content"><?php _e('Original URL');?></th><th style="width:30px;"><?php _e('Show');?></th><th style="width:60px;"><?php _e('Admin');?></th></tr></thead>
<tbody>
<?php
$no = 0;
if(is_array($lList)){
	foreach($lList as $val){
		$no +=1;
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $val['url'];?>"/></td><td class="max50"><a href="<?php echo BASE_URL.$val['url'];?>" target="_blank"><?php echo $val['link_body'];?></a></td><td class="media_content"> <?php echo SITE_URL.$val['original_url'];?></td><td><?php echo in_array($val['url'],$hList)?_t('No'):_t('Yes');?></td><td><?php if($index_setting['req'] != $val['original_url']):?><a href="javascript:void(0);" class="ha" url="<?php echo $val['url'];?>" ourl="<?php echo $val['original_url'];?>"><?php _e('Homepage');?></a><?php else:?><?php _e('Is Index');?> <a href="javascript:void(0);" class="restore_index"><?php _e('Cancel');?></a><?php endif;?></td></tr>
<?php
		}
}
?>
</tbody>
</table>
</div>
<input type="submit" value=" <?php _e('Hidden');?> " class="btn_submit">
<input type="button" value=" <?php _e('Show');?> " class="btn_show">
</form>
<?php echo $data['pager']['list_put'];?>
<script type="text/javascript">
<!--
_().ready(function(){
	_('.btn_show').click(function(){
		_('#submode').val('show');
		_('.btn_submit').click();
	});
	bind_checkall('#checkall','.ck_item');
	_('.ha').bind('click',function(){
		var url = _(this).attr('url');
		var req = _(this).attr('ourl');
		if (!url || !req){
			return ;
		}
		var query = new Object();
		query.url = escape(url);
		query.req = req;
		_.ajax({
			'type':'POST',
			'data':query,
			'url':'./?type=sitemap&mode=make_index',
			'success':function(result){
					if (typeof(result) == 'object'){
						if (result['status']==1){
							window.location.reload();
						}
					}
			}
		});
	});
	_('.restore_index').bind('click',function(){
		var query = new Object();
		_.ajax({
			'type':'POST',
			'data':query,
			'url':'./?type=sitemap&mode=restore_index',
			'success':function(result){
					if (typeof(result) == 'object'){
						if (result['status']==1){
							window.location.reload();
						}
					}
			}
		});
	});
});
//-->
</script>