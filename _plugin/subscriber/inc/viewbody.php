<?php
/**
 * View email body template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<fieldset><legend><?php echo SUBJECT;?></legend>
<?php echo $mailbody['subject'].' -- '.date('m/d/Y H:i:s',$mailbody['date']);?>
</fieldset>
<fieldset><legend><?php echo BODY;?></legend>
<h2>Html</h2>
<div style="margin:10px;">
<?php echo $mailbody['body'];?>
</div>
<h2>Text</h2>
<div style="margin:10px;">
<?php echo $mailbody['text_body'];?></div>
</fieldset>
<fieldset><legend><?php echo SUBSCRIBER;?></legend>
<?php echo $mailbody['to'];?>
</fieldset>
<input type="button" value="<?php echo BACK;?>" onclick='javascript:history.go(-1);'>