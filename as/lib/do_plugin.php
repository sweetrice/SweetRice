<?php
/**
 * Plugin management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$plugin      = $_GET['plugin'];
$plugin_list = pluginList();
if ($plugin_list[$plugin]['installed'] && $plugin_list[$plugin]['directory'] && file_exists(SITE_HOME . '_plugin/' . $plugin_list[$plugin]['directory'] . '/plugin_config.php')) {
    include SITE_HOME . '_plugin/' . $plugin_list[$plugin]['directory'] . '/plugin_config.php';
    include SITE_HOME . '_plugin/' . $plugin_list[$plugin]['directory'] . '/' . $plugin_config['home'];
    $top_word = $plugin;
} else {
    _goto($_SERVER['HTTP_REFERER']);
}
