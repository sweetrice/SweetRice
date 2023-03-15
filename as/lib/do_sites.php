<?php
/**
 * Sites management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
if ($mode == 'save') {
    $status = initSiteDB();
    if (!$status['inited']) {
        output_json(array('status' => 0, 'status_code' => $status['error_db'] . ' ' . $status['message']));
    } else {
        output_json(array('status' => 1));
    }
} elseif ($mode == 'delete') {
    $host = $_POST['host'];
    if ($host && rmSite($host)) {
        output_json(array('status' => '1', 'status_code' => vsprintf(_t('%s (%s) has been update successfully.'), array(_t('Sites'), $host))));
    }
    output_json(array('status' => 0, 'status_code' => _t('Sorry,some error happened')));
} elseif ($mode == 'insert') {
    $themes   = getThemeTypes();
    $top_word = _t('Site Management');
    $inc      = 'site_modify.php';
} else {
    $site_list = siteList();
    $top_word  = _t('Site List');
    $inc       = 'site_list.php';
}
