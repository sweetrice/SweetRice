<?php
/**
 * Dashborad media management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.6.4
 */
defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="media_center" />
<?php _e('Search');?> <a href="./?type=media_center&dir=<?php echo $open_dir; ?>"><?php echo $open_dir; ?></a> <input type="hidden" name="dir" value="<?php echo $open_dir; ?>"/>
	<input type="text" name="keyword" value="<?php echo escape_string($keyword); ?>" placeholder="<?php _e('Keywords');?>" class="mw180"/> <input type="submit" value="<?php _e('Search');?>" class="input_submit"/>
</form>
<p><span class="folder" ></span><a href="./?type=media_center<?php echo $parent ? '&dir=' . $parent : ''; ?>"><?php _e('Parent');?></a></p>
<span id="deleteTip"></span>
<form method="post" id="bulk_form" action="./?type=media_center&mode=bulk">
<div id="tbl">
<table>
<thead>
	<tr>
		<th><input type="checkbox" id="checkall"/> <a href="javascript:void(0);" class="btn_sort" data="name"><?php _e('Name');?></a></th>
		<th><a href="javascript:void(0);" class="btn_sort" data="filetype"><?php _e('File Type');?></a></th>
		<th><a href="javascript:void(0);" class="btn_sort" data="date"><?php _e('Date');?></a></th>
		<th class="td_admin"><?php _e('Admin');?></th>
	</tr>
</thead>
<tbody>
<?php
$no = 0;
for ($i = $pager['page_start']; $i < $pager['page_start'] + $page_limit; $i++) {
    if ($files[$i]) {
        $no += 1;
        ?>
<tr id="tr_<?php echo $no; ?>" data-label="<?php _e('Name');?>">
	<td data-label="<?php _e('Name');?>"><span class="sortNo" id="sortNo_<?php echo $no; ?>"><?php echo $no; ?></span>
<?php
if ($files[$i]['type'] == 'dir') {
            ?>
<span class="folder" ></span><a href="./?type=media_center&dir=<?php echo $files[$i]['link'] . '/'; ?>"><span id="name_<?php echo $no; ?>"><?php echo $files[$i]['name']; ?></span></a>
<?php
} else {
            ?>
<span class="article" ></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $files[$i]['link']; ?>"/>
<?php
if (substr($files[$i]['type'], 0, 5) == 'image') {
                ?>
<a href="javascript:void(0);" class="attimage" data="<?php echo BASE_URL . substr(MEDIA_DIR . $files[$i]['link'], strlen(SITE_HOME)); ?>"><span id="name_<?php echo $no; ?>"><?php echo $files[$i]['name']; ?></span></a>
<?php
} else {
                ?>
<a href="<?php echo BASE_URL . substr(MEDIA_DIR . $files[$i]['link'], strlen(SITE_HOME)); ?>"><span id="name_<?php echo $no; ?>"><?php echo $files[$i]['name']; ?></span></a>
<?php
}
        }
        ?>
</td>
<td data-label="<?php _e('File Type');?>"><span id="filetype_<?php echo $no; ?>"><?php echo $files[$i]['type'] == 'dir' ? _t('Directory') : $files[$i]['type']; ?></span></td>
<td data-label="<?php _e('Date');?>">
<span id="date_<?php echo $no; ?>" class="sortNo"><?php echo $files[$i]['date']; ?></span><?php echo date(_t('M d Y H:i'), $files[$i]['date']); ?></td>
<td class="td_admin" data-label="<?php _e('Admin');?>"><span id="action_<?php echo $no; ?>"></span><a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $files[$i]['link'] ?>" no="<?php echo $no; ?>" href="javascript:void(0);"><?php _e('Delete');?></a></td>
</tr>
<?php
}
}
?>
</tbody>
</table>
</div>
<input type="submit" value=" <?php _e('Bulk Delete');?>"></form>
<?php echo $pager['list_put']; ?>
<div id="upload_form">
<form method="post" action="./?type=media_center&mode=mkdir">
<?php _e('New Directory');?> : <input type="hidden" name="parent_dir" value="<?php echo $open_dir; ?>"/>
	<input type="text" name="new_dir" /> <input type="submit" value="<?php _e('Done');?>"/>
</form>
<form method="post" action="./?type=media_center&mode=upload" enctype="multipart/form-data" >
<div class="form_split">
<?php _e('Upload');?> : <input type="hidden" name="dir_name" value="<?php echo str_replace(MEDIA_DIR, '', $open_dir); ?>"/>
	<input type="file" name="upload[]" multiple>
</div>
<div class="form_split"><?php _e('Extract zip archive?');?> <input type="checkbox" name="unzip" value="1" /> <input type="submit" value="<?php _e('Done');?>"/> <span class="tip"><?php echo _t('Max upload file size'), ':', UPLOAD_MAX_FILESIZE; ?></span></div>
</form>
</div>
</div>

<div class="div_clear"></div>
</div>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript" src="js/media_center.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.action_delete').bind('click',function(){
			if(!confirm('<?php _e('Are you sure delete it?');?>')) return;
			var _this = this;
			_.ajax({
				'type':'post',
				'data':{'file':_(this).attr('data'),'_tkv_':_('#_tkv_').attr('value')},
				'url':'./?type=media_center&mode=delete',
				'success':function(result){
					if (result['status_code'])
					{
						_.ajax_untip(result['status_code']);
					}
					if (result['status'] == 1)
					{
						_(_this).parent().parent().remove();
					}
				}
			});
		});
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl',function(){bind_checkall('#checkall','.ck_item');});
			_('.attimage').unbind('click').bind('click',function(){
				if (!_(this).attr('data')){
					return false;
				}
				_.dialog({'content':'<div style="width:22px;margin:auto;"><img src="../images/loading.gif"></div>','name':'media','width':300,'layer':true});
				psrc = _(this).attr('data');
				image.src = psrc;
				return false;
			});
		});

		_('#bulk_form').bind('submit',function(event){
		var no = 0;
		_('.ck_item').each(function(){
			if (_(this).prop('checked')){
				no += 1;
			}
		});
		if(no > 0){
			if(!confirm('<?php _e('Are you sure delete it?');?>')){
				_().stopevent(event);
			}
		}else{
			alert('<?php _e('No Record Selected');?>.');
			_().stopevent(event);
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
		_.stopevent(event);
		});
	});
</script>
<?php
include 'lib/foot.php';
exit();
?>