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
<?php echo $pager['list_put'];?>
<span id="deleteTip"></span>
<form method="post" action="./?type=sitemap&mode=hide">
<div id="tbl">
<table>
<thead><tr><td align="left"><input type="checkbox" id="checkall"/> <?php echo URL;?></td><td><?php echo ORIGINAL_URL;?></td></tr></thead>
<tbody>
<?php
$no = 0;
if(is_array($lList)){
	foreach($lList as $val){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $val['url'];?>" <?php echo in_array($val['url'],$hList)?'checked':'';?>/> <a href="<?php echo BASE_URL.$val['url'];?>"><?php echo $val["link_body"];?></a></td><td><?php if($index_setting['req'] != $val['original_url']):?><a href="javascript:void(0);" class="ha" url="<?php echo $val['url'];?>" ourl="<?php echo $val['original_url'];?>"><?php echo HOMEPAGE;?></a><?php else:?><?php echo IS_INDEX;?> <a href="javascript:void(0);" class="restore_index"><?php echo CANCEL;?></a><?php endif;?> <?php echo SITE_URL.$val['original_url'];?></td></tr>
<?php
		}
}
?>
</tbody>
</table>
</div>
<input type="submit" value=" <?php echo HIDDEN;?> ">
</form>
<script type="text/javascript">
<!--
_().ready(function(){
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