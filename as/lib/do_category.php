<?php
/**
 * Category management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
switch ($mode) {
    case 'insert':
        if (isset($_POST['name'])) {
            $cat_data = category_insert();
        }
        if ($cat_data['cat_id']) {
            if ($_POST['done']) {
                if (intval($_POST['id']) <= 0) {
                    completeInsert('./?type=category&mode=insert', './?type=category');
                } else {
                    _goto('./?type=category');
                }
            } else {
                _goto($_SERVER['HTTP_REFERER']);
            }
        } else {
            if ($global_setting['theme']) {
                $template = get_template(SITE_HOME . '_themes/' . $global_setting['theme'] . '/', 'Category');
            } else {
                $template = get_template(SITE_HOME . '_themes/default/', 'Category');
            }
            $id       = intval($_GET['id']);
            $s_parent = array();
            if ($id > 0) {
                $row      = getCategories(array('ids' => $id, 'custom_field' => true, 'fetch_one' => true));
                $top_word = _t('Modify Category');
                if (is_array($row)) {
                    $cf_rows  = $row['custom_field'];
                    $s_parent = array($row['parent_id'] => 'selected="selected"');
                }
            } else {
                $top_word = _t('Create Category');
            }
            $subCategory = subCategory(" AND ip.`plugin` = ''");
            $inc         = 'cat_insert.php';
        }
        break;
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
            removeCategories(array('ids' => $ids));
        }
        output_json(array('status' => 1, 'status_code' => vsprintf(_t('%s (%s) has been delete successfully.'), array(_t('Category'), implode(',', $ids)))));
        break;
    default:
        $where      = " ip.`plugin` = '' AND ip.`item_type` = 'category'";
        $search     = db_escape($_GET['search']);
        $search_url = '';
        if ($search) {
            $where .= " AND c.`title` LIKE '%$search%'";
            $search_url .= '&search=' . $_GET['search'];
        }
        if ($_GET['parent']) {
            $where .= " AND c.`parent_id` = '" . intval($_GET['parent']) . "'";
            $search_url .= '&parent=' . $_GET['parent'];
        }
        $data = db_fetch(array('table' => " `" . DB_LEFT . "_category` AS c LEFT JOIN `" . DB_LEFT . "_item_plugin` AS ip ON c.`id` = ip.`item_id`",
            'field'                        => "c.*",
            'where'                        => $where,
            'pager'                        => array('p_link' => './?type=category' . $search_url . '&',
                'page_limit'                                     => page_limit(), 'pager_function' => '_pager'),
        ));
        $top_word = _t('Category List');
        $inc      = 'category.php';
}
