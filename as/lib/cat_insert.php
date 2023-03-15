<?php
/**
 * Category management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
?>
<form method="post" enctype="multipart/form-data" action="./?type=category&mode=insert">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
<fieldset><legend><?php _e('Name');?></legend>
<input type="text" name="name" class="input_text" value="<?php echo $row['name']; ?>" > * <span class="tip"><?php _e('Category name');?></span>
</fieldset>
<fieldset><legend><?php _e('Slug');?></legend>
<input type="text" name="link" class="input_text slug" value="<?php echo $row['link']; ?>"> * <span class="tip"><?php _e('Only a-z,A-Z,0-9,-,_');?></span>
</fieldset>
<fieldset><legend><?php _e('Title');?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $row['title']; ?>"> * <span class="tip"><?php _e('Title of Page');?></span>
</fieldset>
<fieldset><legend><?php _e('Meta Setting');?></legend>
<div class="mb10"><input type="text" name="keyword" class="input_text meta" value="<?php echo $row['keyword'] ? $row['keyword'] : _t('Keywords'); ?>" data="<?php _e('Keywords');?>"> * <span class="tip"><?php _e('Keywords of Page');?></span></div>
<div class="mb10"><input type="text" name="description" data="<?php _e('Description');?>" class="input_text meta" value="<?php echo $row['description'] ? $row['description'] : _t('Description'); ?>"> * <span class="tip"><?php _e('Description of Page');?></span></div>
</fieldset>
<fieldset><legend><?php _e('Top word');?></legend>
<input type="text" name="sort_word" value="<?php echo $row['sort_word']; ?>" class="input_text"> * <span class="tip"><?php _e('At top right of page');?></span>
</fieldset>
<fieldset><legend><?php _e('Parent');?></legend>
<select name="parent_id">
<option value="0" <?php echo $s_parent[0]; ?>> -- <?php _e('Parent');?> -- </option>
<?php
foreach ($subCategory as $val) {
    $_prefix = '';
    for ($i = 0; $i < $val['level']; $i++) {
        $_prefix .= '-- ';
    }
    ?>
<option value="<?php echo $val['id']; ?>" <?php echo $s_parent[$val['id']]; ?>><?php echo $_prefix . $categories[$val['id']]['name']; ?></option>
<?php
}
?>
</select>
</fieldset>
<fieldset><legend><?php _e('Template');?></legend>
<select name="template">
<?php
if (!in_array($row['template'], $template) && $row['template']) {
    $template[$row['template']] = $row['template'];
}
foreach ($template as $key => $val) {
    $s = '';
    if ($key == $row['template']) {
        $s = 'selected';
    }
    echo '<option value="' . $key . '" ' . $s . ' title="' . $key . '">' . $val . '</option>';
}
?>
</select></fieldset>
<?php include 'lib/tinymce.php';?>
<?php
$cfdata = getOption('custom_category_field');
include 'lib/custom_field.php';
?>
<input type="submit" class="input_submit" value="<?php _e('Done');?>" name="done">
<?php
if ($row['link']) {
    ?><input type="submit" value="<?php _e('Update');?>" name="update" class="input_submit">
<?php
}
?><input type="button" value="<?php _e('Back');?>" url="./?type=category" class="input_submit back">
</form>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.slug').bind('change',function(){
			_(this).val(_(this).val().replace(/([^a-z0-9A-Z\-_])/g,'-').replace(/(^-*)|(-*$)/g,''));
		});
		_('.meta').bind('blur',function(){
			if (!_(this).val()) {
				_(this).val(_(this).attr('data'));
			}
		}).bind('focus',function(){
			if (_(this).val() == _(this).attr('data')) {
				_(this).val('');
			}
		});
	});
//-->
</script>