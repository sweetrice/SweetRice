<?php
/**
 * Links management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="permalinks"/>
<input type="hidden" name="mode" value="custom"/>
<input type="text" name="search" value="<?php echo escape_string($_GET['search']); ?>" placeholder="<?php _e('Keywords');?>"/>
<input type="submit" value="<?php _e('Search');?>"/>
</form>
<?php echo $pager['list_put']; ?>
<form method="post" id="bulk_form" action="./?type=permalinks&mode=custom&submode=bulk">
<table>
<thead>
	<tr>
		<th class="data_no"><input type="checkbox" id="checkall"/></th>
		<th class="max50"><?php _e('URL');?></th>
		<th><?php _e('Request');?></th>
		<th><?php _e('Plugin');?></th>
		<th class="td_admin"><?php _e('Admin');?></th>
	</tr>
</thead>
<tbody>
<?php
$no = 0;
foreach ($rows as $row) {
    $no += 1;
    $reqs = isset($row['request']) ? unserialize($row['request']) : array();
    if ($reqs) {
        $original_url = BASE_URL . '?';
        foreach ($reqs as $key => $val) {
            $original_url .= $key . '=' . $val . '&';
        }
        $original_url = substr($original_url, 0, -1);
    } else {
        $original_url = BASE_URL . $row['url'];
    }
    ?>
<tr id="tr_<?php echo $no; ?>">
<td><span class="sortNo" id="sortNo_<?php echo $no; ?>"><?php echo $no; ?></span>
<input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $row['lid']; ?>"/></td>
<td class="max50" data-label="<?php _e('URL');?>"><a href="<?php echo BASE_URL . $row['url']; ?>"><?php echo BASE_URL . $row['url']; ?></a>
</td>
<td data-label="<?php _e('Request');?>"><a href="<?php echo $original_url; ?>"><?php echo $original_url; ?></a></td>
<td data-label="<?php _e('Plugin');?>"><?php echo $row['plugin'] ? $row['plugin'] : '&nbsp;'; ?></td>
<td class="td_admin" data-label="<?php _e('Admin');?>"><span id="action_<?php echo $no; ?>"></span><a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $row['lid']; ?>" no="<?php echo $no; ?>" href="javascript:void(0);"><?php _e('Delete');?></a>
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=permalinks&mode=custom&submode=insert&id=<?php echo $row['lid']; ?>"><?php _e('Modify');?></a>
</td>
</tr>
<?php
}
?>
</tbody>
</table>
<input type="submit" value=" <?php _e('Bulk Delete');?> " class="btn_submit">  <input type="button" value="<?php _e('Create');?>" class="back" url="./?type=permalinks&mode=custom&submode=insert">
</form>
<?php echo $pager['list_put']; ?>

<script type="text/javascript">
<!--
	_().ready(function(){
		_('.action_delete').bind('click',function(){
			_('.ck_item').prop('checked',false);
			_(this).parent().parent().find('.ck_item').prop('checked',true);
			_('.btn_submit').run('click');
		});
		bind_checkall('#checkall','.ck_item');
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