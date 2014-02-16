<?php
/**
 * Email send template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();

?>
<h1><?php echo POSTEMAIL;?>  * <?php echo POSTEMAIL_TIP;?>.</h1>
<form method="post" action="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'postok'));?>">
<fieldset><legend><?php echo FROM;?></legend>
<input type="text" name="from" value="<?php echo $global_setting['admin_email'];?>"/>
</fieldset>
<fieldset><legend><?php echo SUBJECT;?></legend>
<input type="text" name="subject" class="input_text"/>
</fieldset>
<fieldset><legend><?php echo BODY;?></legend>
<h2><?php echo HTML_BODY;?>:</h2>
<p><label id="lbVisual" onmousedown='doEditor("visual","html_body");'><?php echo VISUAL;?></label>
<label id="lbHtml" class="current_label" onmousedown='doEditor("html","html_body");'><?php echo HTML;?></a></label></p>
<?php include(PLUGIN_DIR."tinymce.php");?>
<textarea name="body" id="html_body" class="input_textarea"><?php echo $body;?></textarea>
<p>*<?php echo MAILBODY_LINK_TIP;?></p>
<h2><?php echo TEXT_BODY;?>:</h2>
<textarea name="text_body" class="input_textarea"></textarea>
</fieldset>
<input type="submit" value="<?php echo DONE;?>"/> <input type="button" value="<?php echo BACK;?>" onclick='javascript:history.go(-1);'>
</form>