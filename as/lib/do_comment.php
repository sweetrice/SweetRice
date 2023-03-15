<?php
/**
 * Comment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
switch ($mode) {
    case 'view':
        $id       = intval($_GET['id']);
        $commets  = db_array("SELECT * FROM `" . DB_LEFT . "_comment` WHERE `id` = '$id' ");
        $top_word = _t('Reply Comment');
        $info     = toggle_attachment($commets['info'], 'dashboard');
        $inc      = 'view_comment.php';
        break;
    case 'reply':
        $id   = intval($_POST['id']);
        $info = toggle_attachment($_POST['info']);
        $info = db_escape($info);
        if ($info) {
            db_query("UPDATE `" . DB_LEFT . "_comment` SET `info` = '$info',`reply_date` = '" . time() . "' WHERE `id` = '$id'");
        }
        _goto('./?type=comment&mode=view&id=' . $id);
        break;
    case 'bulk':
        $plist = $_POST['plist'];
        if (count($plist)) {
            $ids = implode(',', $plist);
            db_query("DELETE FROM `" . DB_LEFT . "_comment` WHERE `id` IN ($ids)");
            output_json(array('status' => 1, 'status_code' => vsprintf(_t('%s (%s) has been delete successfully.'), array(_t('Comment'), $ids))));
        }
        output_json(array('status' => 0, 'status_code' => _t('Sorry,some error happened')));
        break;
    default:
        $search = db_escape($_GET['search']);
        if ($search) {
            $where = " `info` LIKE '%$search%' ";
        } else {
            $where = " ";
        }
        $data = db_fetch(array(
            'table' => "`" . DB_LEFT . "_comment`",
            'field' => "*",
            'where' => $where,
            'order' => "`date` DESC",
            'pager' => array('p_link' => './?type=comment' . ($search ? '&search=' . $search : '') . '&',
                'page_limit'              => page_limit(), 'pager_function' => '_pager'),
        ));
        $rows     = $data['rows'];
        $pager    = $data['pager'];
        $top_word = _t('Comment List');
        $inc      = 'comment.php';
}
