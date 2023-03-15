<?php
/**
 * App database management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
defined('VALID_INCLUDE') or die();
$mode      = $_GET['mode'];
$app_links = $myApp->app_links();
$url       = $_POST['url'];
$lids      = $_POST['lids'];
$str       = '';
$data      = array();
foreach ($url as $key => $val) {
    if ($val) {
        $data['id']     = $lids[$key];
        $data['url']    = $val;
        $data['reqs']   = $app_links[$key];
        $data['plugin'] = THIS_APP;
        $result         = links_insert($data);
        $str .= $val . ' : ' . ($result['lid'] ? _t('URl Update Successfully') : _t('URl Update Failed'));
    }
}
alert($str, './?type=plugin&plugin=' . THIS_APP);
