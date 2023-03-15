<?php
/**
 * AD management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
switch ($mode) {
    case 'save':
        $adk = preg_replace('/[^0-9a-zA-Z\-_]+/', '', $_POST['adk']);
        $adv = $_POST['adv'];
        if ($adk && $adv) {
            file_put_contents(SITE_HOME . 'inc/ads/' . $adk . '.php', $adv);
        }
        _goto('./?type=ad');
        break;
    case 'bulk':
        $plist   = $_POST['plist'];
        $ads_dir = SITE_HOME . 'inc/ads/';
        foreach ($plist as $val) {
            unlink($ads_dir . $val . '.php');
        }
        output_json(array('status' => '1', 'status_code' => vsprintf(_t('%s (%s) has been delete successfully.'), array(_t('Ads'), implode(',', $plist)))));
        break;
    default:
        $ads     = array();
        $ads_dir = SITE_HOME . 'inc/ads/';
        if (!file_exists($ads_dir)) {
            mkdir($ads_dir);
        }
        $adk = $_GET['adk'];
        $adv = '';
        if (file_exists($ads_dir . $adk . '.php')) {
            $adv = file_get_contents($ads_dir . $adk . '.php');
        }
        $d = dir($ads_dir);
        while (false !== ($entry = $d->read())) {
            if ($entry != '.' && $entry != '..') {
                $ads[] = str_replace('.php', '', $entry);
            }
        }
        $top_word = _t('Ads') . ' ' . _t('Admin');
        $inc      = 'ad.php';
}
