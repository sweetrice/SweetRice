<?php
/**
 * App database management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
switch ($mode) {
    case 'bulk':
        $ids   = array();
        $plist = $_POST['plist'];
        foreach ($plist as $val) {
            $val = intval($val);
            if ($val > 0) {
                $ids[] = $val;
            }
        }
        if (count($ids) > 0) {
            $ids = implode(',', $ids);
            db_query("DELETE FROM `" . ADB . "_app` WHERE `id` IN ($ids)");
        }
        output_json(array('status' => 1));
        break;
    case 'insert':
        if ($_POST['content']) {
            db_insert(ADB . '_app', array('id', intval($_POST['id'])), array('content'), array($_POST['content']));
            if (intval($_POST['id']) == 0) {
                completeInsert(pluginDashboardUrl(THIS_APP, array('app_mode' => 'database', 'mode' => 'insert')), $_POST['returnUrl'] ? $_POST['returnUrl'] : pluginDashboardUrl(THIS_APP, array('app_mode' => 'database')));
            }
            _goto($_POST['returnUrl'] ? $_POST['returnUrl'] : pluginDashboardUrl(THIS_APP, array('app_mode' => 'database')));
        }
        $id = intval($_GET['id']);
        if ($id) {
            $row = db_array("SELECT * FROM `" . ADB . "_app` WHERE `id` = '$id'");
        }

        $location = parse_url($_SERVER['HTTP_REFERER']);
        if ($location && $location['query']) {
            preg_match('/&p=([0-9]+)/', $location['query'], $matches);
            if (is_array($matches) && $_SESSION['database_list_p'] != $matches[1] && $matches[1]) {
                $_SESSION['database_list_p'] = $matches[1];
            }
        }
        $returnUrl = pluginDashboardUrl(THIS_APP, array('app_mode' => 'database')) . ($_SESSION['database_list_p'] ? '&p=' . $_SESSION['database_list_p'] : '');
        $app_inc   = 'database_insert.php';
        break;
    default:
        $where      = " ";
        $url_search = '';
        if (isset($_GET['keyword'])) {
            $where .= " `content` LIKE '%" . db_escape($_GET['keyword']) . "%' ";
            $url_search .= '&keyword=' . $_GET['keyword'];
        }
        $data = db_fetch(array(
            'table' => "`" . ADB . "_app`",
            'field' => "*",
            'where' => $where,
            'pager' => array('p_link' => pluginDashboardUrl(THIS_APP, array('app_mode' => 'database')) . $url_search . '&',
                'page_limit'              => page_limit(), 'pager_function' => '_pager'),
        ));
        $location = parse_url($_SERVER['REQUEST_URI']);
        preg_match('/&p=([0-9]+)/', $location['query'], $matches);
        if ($_SESSION['database_list_p'] != $matches[1] && $matches[1]) {
            $_SESSION['database_list_p'] = $matches[1];
        }
        $returnUrl = pluginDashboardUrl(THIS_APP, array('app_mode' => 'database')) . ($_SESSION['database_list_p'] ? '&p=' . $_SESSION['database_list_p'] : '');
        $app_inc   = 'database.php';
}
