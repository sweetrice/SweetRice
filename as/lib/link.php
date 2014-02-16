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
<fieldset><legend><?php echo CREAT_LINK_TIP;?></legend>
<p><label id="lbVisual" onmousedown='doEditor("visual","content");'>Visual</label>
<label id="lbHtml" class="current_label" onmousedown='doEditor("html","content");'>HTML</label></p>
<?php include("lib/tinymce.php");?>
<textarea name="content" id="content" class="link"><?php echo htmlspecialchars($row['content']);?></textarea>
</fieldset>
<input type="submit" class="input_submit" value="<?php echo UPDATE;?>"/>
</form>