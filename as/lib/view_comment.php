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
<fieldset><legend><?php echo POST;?></legend>
<a href="<?php echo BASE_URL.show_link_page($commets['post_cat'],$commets['post_slug']);?>"><?php echo $commets['post_name'];?></a></fieldset>
<fieldset><legend><?php echo COMMENT_USER;?></legend>
<?php echo $commets['umail'];?> 
<?php echo date('M,d Y H:i:s',$commets['date']);?></fieldset>
<fieldset><legend><?php echo BODY;?></legend>
<?php echo $info;?></fieldset>
<form method="post" action="./?type=comment&mode=reply">
<input type="hidden" name="id" value="<?php echo $commets['id'];?>" />
<fieldset><legend><?php echo REPLY;?></legend>
<div class="mg5"><label class="editor_toggle" tid="info" data="visual"><?php echo VISUAL;?></label>
<label class="editor_toggle current_label" data="html" tid="info"><?php echo HTML;?></a></label></div>
<?php include("lib/tinymce.php");?>
<textarea name="info" id="info">
<?php 
echo htmlspecialchars($info);?>
<blockquote><?php echo $global_setting['author'];?> <?php echo REPLY;?>[<?php echo date('M,d,Y D');?>]:<p></p></blockquote>
</textarea></fieldset>
<input type="submit" value="<?php echo DONE;?>" class="input_submit"/> <input type="button" value="<?php echo BACK;?>" onclick="location.href='./?type=comment';" class="input_submit">
</form>