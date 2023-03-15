<?php
/**
 * Link management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
switch ($mode) {
    case 'save':
        $content = toggle_attachment($_POST['content']);
        setOption('links', $content);
        _goto('./?type=link');
        break;
    default:
        $row            = getOption('links');
        $row['content'] = toggle_attachment($row['content'], 'dashboard');
        $top_word       = _t('Links management');
        $inc            = 'link.php';
}
