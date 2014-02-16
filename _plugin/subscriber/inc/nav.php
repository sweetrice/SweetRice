<?php
/**
 * Navigation section template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<div><a href="<?php echo pluginDashboardUrl(THIS_PLUGIN);?>"><?php echo HOME;?></a> | <a href="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'list'));?>"><?php echo MAILLIST;?></a> | <a href="<?php echo pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'bodylist'));?>"><?php echo BODYLIST;?></a></div>