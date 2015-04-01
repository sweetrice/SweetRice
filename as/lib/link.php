<?php
/**
 * Link management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="post" action="./?type=link&mode=save">
<fieldset><legend><?php _e('Edit links content.');?></legend>
<p><label class="editor_toggle" tid="content" data="visual"><?php _e('Visual');?></label>
<label class="editor_toggle current_label" data="html" tid="content"><?php _e('HTML');?></label></p>
<?php include('lib/tinymce.php');?>
<textarea name="content" id="content" class="link"><?php echo htmlspecialchars($row['content']);?></textarea>
</fieldset>
<input type="submit" class="input_submit" value="<?php _e('Done');?>"/>
</form>