<?php
/**
 * Subscriber management template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<fieldset><legend><?php echo PLUGIN_DESCRIPTION;?></legend>
<?php echo SUBSCRIBER_DESCRIPTION;?>
</fieldset>
<fieldset><legend><?php echo POSTEMAIL;?></legend>
<input type="button" value="<?php echo POSTEMAIL;?>" onclick="location.href='./?type=plugin&plugin=<?php echo THIS_PLUGIN;?>&plugin_mod=post';">
</fieldset>
<fieldset><legend><?php echo MAIL_POST_TOTAL;?></legend>
<?php echo $body_total;?>
</fieldset>
<fieldset><legend><?php echo EMAIL_LIST_TOAL;?></legend>
<?php echo $mail_total;?>
</fieldset>
<fieldset><legend><?php echo SUBSCRIBER;?></legend>
<div><a href="<?php echo BASE_URL.($linkRow['url']&&URL_REWRITE?$linkRow['url']:pluginHookUrl(THIS_PLUGIN,array()));?>" target="_blank"><?php echo SUBSCRIBER;?></a></div>
<h4><?php echo CUSTOM_SUBSCRIBER_URL;?></h4>
<form method="post" action="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'links'));?>">
<input type="hidden" name="id" value="<?php echo $linkRow['lid'];?>"/>
	<input type="text" name="url" value="<?php echo $linkRow['url'];?>"/> <input type="submit" value="<?php echo DONE;?>"/>
</form>
</fieldset>