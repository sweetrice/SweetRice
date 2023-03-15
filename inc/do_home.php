<?php
/**
 * Home Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$post_output = $page_theme['post_output'] && file_exists(THEME_DIR . $page_theme['post_output']) ? THEME_DIR . $page_theme['post_output'] : false;
$total       = db_total("SELECT COUNT(*) FROM `" . DB_LEFT . "_posts` WHERE `in_blog` = '1'");
$page_limit  = $global_setting['nums_setting']['postHome'];
$pager       = pager($total, $page_limit, $p_link);
$m           = $_GET['m'];
if ($m == 'pins') {
    $pins_num   = $global_setting['nums_setting']['postPins'];
    $p          = max(1, intval($_GET['p']));
    $moreNum    = max(1, intval($_GET['moreNum']));
    $page_start = $p * $page_limit + ($moreNum - 1) * $pins_num;
    if ($page_start + $pins_num >= $total) {
        $plist['isMax'] = 1;
    } else {
        $plist['isMax'] = 0;
    }
    $plist['body'] = '';
    $data          = getPosts(array(
        'table'        => " `" . DB_LEFT . "_posts` as ps LEFT JOIN `" . DB_LEFT . "_item_plugin` AS ip ON ip.`item_id` = ps.`id`",
        'field'        => "ps.*",
        'where'        => " ip.`plugin` = '' AND ip.`item_type` = 'post' ",
        'post_type'    => 'show',
        'order'        => "ps.`id` DESC",
        'limit'        => array($page_start, $pins_num),
        'custom_field' => true,
    ));
    foreach ($data['rows'] as $val) {
        $plist['body'] .= _posts($val, $post_output);
    }
    exit(json_encode($plist));
}
$data = getPosts(array(
    'table'        => " `" . DB_LEFT . "_posts` as ps LEFT JOIN `" . DB_LEFT . "_item_plugin` AS ip ON ip.`item_id` = ps.`id`",
    'field'        => "ps.*",
    'where'        => " ip.`plugin` = '' AND ip.`item_type` = 'post' ",
    'pager'        => array('p_link' => '', 'page_limit' => $global_setting['nums_setting']['postHome']),
    'post_type'    => 'show',
    'order'        => "ps.`id` DESC",
    'custom_field' => true,
));
$rows        = $data['rows'];
$inc         = THEME_DIR . $page_theme['home'];
$last_modify = pushDate(array($rows, array(array('date' => filemtime($inc)))));
