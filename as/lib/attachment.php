<?php
/**
 * Attachment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="attachment"/>
	<input type="text" name="search" value="<?php echo escape_string($_GET['search']); ?>" placeholder="<?php _e('Keywords');?>"/> <input type="submit" value="<?php _e('Search');?>" class="input_submit"/>
</form>
<div id="tbl">
<table>
<thead>
	<tr>
		<th class="max50"><a href="javascript:void(0);" class="btn_sort" data="filename"><?php _e('Filename');?></a></th>
		<th><a href="javascript:void(0);" class="btn_sort" data="filesize"><?php _e('File size');?></a></th>
		<th><a href="javascript:void(0);"  class="btn_sort" data="downloadtimes" stt="0"><?php _e('Download times');?></a></th><th><a href="javascript:void(0);"  class="btn_sort" data="date"><?php _e('Upload Time');?></a></th>
		<th class="td_admin"><?php _e('Admin');?></th></tr></thead>
<tbody>
<?php
$no = 0;
foreach ($rows as $row) {
    $no += 1;
    ?>
<tr id="tr_<?php echo $no; ?>">
	<td class="max50"><span class="sortNo" id="sortNo_<?php echo $no; ?>"><?php echo $no; ?></span><a href="<?php echo getAttachmentUrl($row['file_name']); ?>" target="_blank" class="preview-image"><span id="filename_<?php echo $no; ?>"><?php echo getAttachmentUrl($row['file_name']); ?></span></a></td>
	<td data-label="<?php _e('File size');?>"><span id="filesize_<?php echo $no; ?>"><?php echo filesize2print($row['file_name']); ?></span></td>
	<td data-label="<?php _e('Download times');?>"><span id="downloadtimes_<?php echo $no; ?>"><?php echo $row['downloads']; ?></span></td>
	<td data-label="<?php _e('Upload Time');?>"><span id="date_<?php echo $no; ?>" class="sortNo"><?php echo $row['date']; ?></span><?php echo date(_t('M d Y H:i'), $row['date']); ?></td>
	<td class="td_admin" data-label="<?php _e('Admin');?>"><a title="<?php _e('Modify');?>" class="action_modify" href="./?type=post&mode=insert&id=<?php echo $row['post_id']; ?>"><?php _e('Modify');?></a>
</td></tr>
<?php
}
?>
</tbody>
</table>
</div>
<?php echo $pager['list_put']; ?>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.preview-image').click(function(event){
			_.stopevent(event);
			_.dialog({'content':'<div class="preview-image-wrap"><img src="'+_(this).attr('href')+'"></div>','layer':1});
		})
	});
//-->
</script>