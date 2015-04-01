<?php
/**
 * Comment view and reply template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<fieldset><legend><?php _e('Post');?></legend>
<a href="<?php echo BASE_URL.show_link_page($commets['post_cat'],$commets['post_slug']);?>"><?php echo $commets['post_name'];?></a></fieldset>
<fieldset><legend><?php _e('Comment User');?></legend>
<?php echo $commets['name'];?> 
<?php echo date(_t('M d Y H:i'),$commets['date']);?></fieldset>
<fieldset><legend><?php _e('Body');?></legend>
<?php echo $info;?></fieldset>
<form method="post" action="./?type=comment&mode=reply">
<input type="hidden" name="id" value="<?php echo $commets['id'];?>" />
<fieldset><legend><?php _e('Reply');?></legend>
<div class="mg5"><label class="editor_toggle" tid="info" data="visual"><?php _e('Visual');?></label>
<label class="editor_toggle current_label" data="html" tid="info"><?php _e('HTML');?></label></div>
<?php include('lib/tinymce.php');?>
<textarea name="info" id="info">
<?php echo htmlspecialchars($info);?>
<blockquote><?php echo $global_setting['author'];?> <?php _e('Reply');?>[<?php echo date('M,d,Y D');?>]:<p></p></blockquote>
</textarea></fieldset>
<input type="submit" value="<?php _e('Done');?>" class="input_submit"/> <input type="button" value="<?php _e('Back');?>" url="./?type=comment" class="input_submit back">
</form>