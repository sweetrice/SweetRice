<?php
/**
 * Comment Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
if ($mode == 'insert') {
    output_json(comment_insert($_POST));
} else {
    $post = db_escape($_GET['post']);
    if (empty($post)) {
        _404('entry');
    }
    $row = getPosts(array('field' => 'ps.*', 'where' => "UPPER(ps.`sys_name`) = UPPER('$post') ", 'custom_field' => true, 'post_type' => 'show', 'fetch_one' => true));
    if (empty($row['id'])) {
        _404('entry');
    }
    $total          = db_total("SELECT COUNT(*) FROM `" . DB_LEFT . "_comment` WHERE `post_id` = '" . $row['id'] . "'");
    $comment_output = $page_theme['comment_output'] && file_exists(THEME_DIR . $page_theme['comment_output']) ? THEME_DIR . $page_theme['comment_output'] : false;
    $page_limit     = $global_setting['nums_setting']['commentList'];
    $m              = $_GET['m'];
    if ($m == 'pins') {
        $plist      = array();
        $pins_num   = $global_setting['nums_setting']['commentPins'];
        $p          = max(1, intval($_GET['p']));
        $moreNum    = max(1, intval($_GET['moreNum']));
        $last_no    = max(1, intval($_GET['last_no']));
        $page_start = $p * $page_limit + ($moreNum - 1) * $pins_num;
        if ($page_start + $pins_num >= $total) {
            $plist['isMax'] = 1;
        } else {
            $plist['isMax'] = 0;
        }
        $plist['body'] = '';
        $data          = db_fetch(array(
            'table' => DB_LEFT . '_comment',
            'field' => "`email` ,`name`,`website`, `info` ,`date`,`reply_date`",
            'where' => "`post_id` = '" . $row['id'] . "'",
            'order' => "`date` DESC",
            'limit' => array($page_start, $pins_num),
        ));
        $comment_link = show_link_comment($row['sys_name'], ($p + 1));
        foreach ($data['rows'] as $k => $val) {
            $last_no += 1;
            $k += 1;
            $plist['body'] .= _comment($val, $k, $last_no, $comment_link, $comment_output);
        }
        $plist['last_no'] = $last_no;
        exit(json_encode($plist));
    }
    $p_link = show_link_comment($post);
    $pager  = pager($total, $page_limit, $p_link);
    if ($pager['outPage']) {
        _404('entry');
    }
    if ($row['allow_comment']) {
        $comment_link = show_link_comment($row['sys_name'], $pager['page']);
    }
    $data = db_fetch(array(
        'table' => DB_LEFT . '_comment',
        'field' => "`email` ,`name`,`website`, `info` ,`date`,`reply_date`",
        'where' => "`post_id` = '" . $row['id'] . "'",
        'order' => "`date` DESC",
        'pager' => array('p_link' => show_link_comment($post), 'page_limit' => $page_limit),
    ));
    $rows        = $data['rows'];
    $pager       = $data['pager'];
    $reply_dates = array();
    foreach ($rows as $key => $val) {
        $reply_dates[] = array('date' => max($val['date'], $val['reply_date']));
    }
    $top_word    = $row['title'] . ' - Comments';
    $title       = $row['title'] . ' comment ' . ($page_m > 1 ? 'page ' . $page_m : '') . ' ' . $global_setting['name'];
    $description = $row['description'] . ' comment ' . ($page_m > 1 ? 'page ' . $page_m : '') . ' ' . $global_setting['name'];
    $keywords    = $row['keywords'];
    $rssfeed     = '<link rel="alternate" type="application/rss+xml" title="' . $row['name'] . '" href="' . show_link_page_xml($row['sys_name']) . '" />';
    $inc         = THEME_DIR . $page_theme['show_comment'];
    $last_modify = pushDate(array($reply_dates, array($row), array(array('date' => filemtime($inc)))));
}
