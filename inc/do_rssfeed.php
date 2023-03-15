<?php
/**
 * RSSFeed Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$type = $_GET['type'];
if ($type == 'entry') {
    $post  = db_escape($_GET['post']);
    $where = " 1=1 ";
    if ($post) {
        $where .= "  AND UPPER(ps.`sys_name`) = UPPER('$post')";
    } else {
        _404('entry');
    }
    $row = getPosts(array('field' => 'ps.*', 'where' => $where, 'custom_field' => true, 'post_type' => 'show', 'fetch_one' => true));
    if (!$row['id']) {
        _404('entry');
    }
    if ($row['allow_comment']) {
        $comments = db_arrays("SELECT `name` ,`website`, `info` ,`date` FROM `" . DB_LEFT . "_comment` WHERE `post_id` = '" . $row['id'] . "'");
    }
    outputHeader($row['date']);
    include 'inc/rssfeed_entry.php';
} elseif ($type == 'category') {
    $cat = $_GET['c'];
    if (empty($cat)) {
        _404('category');
    }
    $cat_id = intval($categoriesByLink[$cat]);
    if ($cat_id == 0) {
        _404('category');
    }
    $data = getPosts(array(
        'category_ids' => $cat_id,
        'order'        => "ps.`id` DESC",
        'post_type'    => 'show',
        'limit'        => array(0, $global_setting['nums_setting']['postRssfeed']),
    ));
    $rows        = $data['rows'];
    $last_modify = pushDate(array($rows));
    outputHeader($last_modify);
    include 'inc/rssfeed_category.php';
} else {
    $data = getPosts(array(
        'order'     => "ps.`id` DESC",
        'post_type' => 'show',
        'limit'     => array(0, $global_setting['nums_setting']['postRssfeed']),
    ));
    $rows        = $data['rows'];
    $last_modify = pushDate(array($rows));
    outputHeader($last_modify);
    include 'inc/rssfeed.php';
}
exit();
