<?php
/**
 * App form management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
defined('VALID_INCLUDE') or die();
$mode     = $_GET['mode'];
$location = parse_url($_SERVER['HTTP_REFERER']);
if ($location && $location['query']) {
    preg_match('/&p=([0-9]+)/', $location['query'], $matches);
    if (is_array($matches) && $_SESSION['form_list_p'] != $matches[1] && $matches[1]) {
        $_SESSION['form_list_p'] = $matches[1];
    }
}
$returnUrl = pluginDashboardUrl(THIS_APP, array('app_mode' => 'form')) . ($_SESSION['form_list_p'] ? '&p=' . $_SESSION['form_list_p'] : '');
switch ($mode) {
    case 'delete':
        $id = intval($_GET["id"]);
        if ($id > 0) {
            db_query("DELETE FROM `" . ADB . "_app_form` WHERE `id` = '$id'");
        }
        _goto($_SERVER["HTTP_REFERER"]);
        break;
    case 'bulk':
        $ids   = array();
        $plist = $_POST["plist"];
        foreach ($plist as $val) {
            $val = intval($val);
            if ($val > 0) {
                $ids[] = $val;
            }
        }
        if (count($ids) > 0) {
            $ids = implode(',', $ids);
            db_query("DELETE FROM `" . ADB . "_app_form` WHERE `id` IN ($ids)");
        }
        _goto($_SERVER["HTTP_REFERER"]);
        break;
    case 'insert':
        if ($_POST['name']) {
            $id     = intval($_POST['id']);
            $fields = array();
            foreach ($_POST['fields'] as $key => $val) {
                $fields[] = array('type' => $_POST['types'][$key], 'name' => $val, 'option' => $_POST['option'][$key], 'select_multiple' => $_POST['select_multiple'][$key], 'tip' => $_POST['tips'][$key], 'required' => $_POST['required'][$key]);
            }
            db_insert(ADB . '_app_form', array('id', $id ? $id : null), array('name', 'fields', 'method', 'action', 'captcha', 'template'), array($_POST['name'], serialize($fields), $_POST['method'], $_POST['action'], intval($_POST['captcha']), $_POST['template']));
            _goto(pluginDashboardUrl(THIS_APP, array('app_mode' => 'form')));
        }
        $id     = intval($_GET["id"]);
        $row    = array();
        $fields = array();
        if ($id > 0) {
            $row    = db_array("SELECT * FROM `" . ADB . "_app_form` WHERE `id` = '$id'");
            $fields = unserialize($row['fields']);
        }
        if ($global_setting['theme']) {
            $template = get_template(SITE_HOME . '_themes/' . $global_setting['theme'] . '/', 'Entry');
        } else {
            $template = get_template(SITE_HOME . '_themes/default/', 'Entry');
        }
        $app_inc = 'form_insert.php';
        break;
    default:
        $where      = " 1=1 ";
        $search_url = '';
        if (isset($_GET['search'])) {
            $where .= " `name` LIKE '%" . db_escape($_GET['search']) . "%'";
            $search_url = '&search=' . $_GET['search'];
        }
        $data = db_fetch(array('table' => ADB . '_app_form',
            'field'                        => '*',
            'where'                        => $where,
            'pager'                        => array('p_link' => pluginDashboardUrl(THIS_APP, array('app_mode' => 'form')) . $search_url . '&', 'page_limit' => page_limit(null, 10), 'pager_function' => '_pager'),
            'debug'                        => true,
        ));
        $app_inc = 'form_list.php';
}
