<?php
/**
 * App management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
 defined('VALID_INCLUDE') or die();
?>
<h1><a href="<?php echo BASE_URL.pluginHookUrl(THIS_APP);?>" target="_blank"><?php _e('App home');?></a></h1>

<form method="post" action="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'links'));?>">
<fieldset><legend><?php _e('Custom App URL');?></legend>
<a href="<?php echo $myApp->app_url('home');?>" target="_blank"><?php _e('Home');?></a> <input type="text" name="url[home]" value="<?php echo $links['home']['url'];?>"/><input type="hidden" name="lids[home]" value="<?php echo $links['home']['lid'];?>" />
<input type="submit" value="<?php _e('Done');?>"/>
</fieldset>
</form>