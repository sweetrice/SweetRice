<?php
/**
 * .htaccess management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
	if(file_exists('../inc/htaccess.txt')){
		$contents = file_get_contents('../inc/htaccess.txt');
	}else{
		$contents = '';
	}
?>
<form method="post" action="./?type=htaccess&mode=save">
<fieldset><legend><?php echo EDIT;?> .htaccess - <?php echo HTACCESS_TITLE;?></legend>
<textarea name="content" class='link'><?php echo $contents;?></textarea>
<p><?php echo HTACCESS_TIPS;?></p>
</fieldset>
<input type="submit" class="input_submit" value="<?php echo UPDATE;?>"/>
</form>