<?php
/**
 * Site management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.1
 */
defined('VALID_INCLUDE') or die();
$submode = $_GET['submode'];
$mode    = $_GET['mode'];
switch ($mode) {
    case 'system':
        switch ($submode) {
            case 'save':
                $alias      = array('attachment', 'rssfeed', 'rssfeedCat', 'rssfeedPost', 'sitemapXml', 'sitemapHtml', 'comment', 'tag', 'ad');
                $permalinks = array();
                foreach ($alias as $key => $val) {
                    $tmp = preg_replace('/[^\w_\-]+/', '', $_POST[$val . '_alias']);
                    if ($key == 'attachment') {
                        if (!is_dir(ROOT_DIR . $tmp)) {
                            $permalinks['attachment'] = $tmp;
                        }
                    } else {
                        $permalinks[$val] = $tmp;
                    }
                }
                setOption('permalinks_system', ($permalinks ? serialize($permalinks) : ''));
                _goto('./?type=permalinks&mode=system');
                break;
            default:
                $top_word = _t('Permalink Setting');
                $inc      = 'permalinks_system.php';
        }
        break;
    case 'custom':
        switch ($submode) {
            case 'save':
                $data = $_POST;
                if (!ltrim($data['url'], '/')) {
                    output_json(array('status' => 0, 'status_code' => _t('Url has been update failed.')));
                }
                $keys = $data['keys'];
                $vals = $data['vals'];
                $reqs = array();
                if ($keys && $vals) {
                    foreach ($keys as $key => $val) {
                        if ($val && $vals[$key]) {
                            $reqs[$val] = $vals[$key];
                        }
                    }
                }
                $data['reqs'] = $reqs;
                $result       = links_insert($data);
                output_json(array('status' => 1, 'status_code' => $result['lid'] ? _t('Url has been update successfully.') : _t('Url has been update failed.')));
                break;
            case 'bulk':
                $plist = $_POST['plist'];
                $ids   = array();
                foreach ($plist as $val) {
                    $val = intval($val);
                    if ($val > 0) {
                        $ids[] = $val;
                    }
                }
                db_query("DELETE FROM `" . DB_LEFT . "_links` WHERE `lid` IN (" . implode(',', $ids) . ")");
                output_json(array('status' => '1', 'status_code' => vsprintf(_t('%s (%s) has been delete successfully.'), array(_t('Links'), implode(',', $plist)))));
                break;
            case 'insert':
                $id = intval($_GET['id']);
                if ($id > 0) {
                    $row = db_array("SELECT * FROM `" . DB_LEFT . "_links` WHERE `lid` = '$id'");
                }
                $inc = 'permalinks_custom_modify.php';
                break;
            default:
                $search = db_escape($_GET['search']);
                $where  = '';
                if ($search) {
                    $where .= "`url` LIKE '%$search%'";
                }

                $data = db_fetch(array(
                    'table' => "`" . DB_LEFT . "_links`",
                    'field' => "*",
                    'where' => $where,
                    'order' => "`url` ASC",
                    'pager' => array('p_link' => './?type=permalinks&mode=custom' . ($search ? '&search=' . $search : '') . '&',
                        'page_limit'              => page_limit(null, 20),
                        'pager_function'          => '_pager',
                    ),
                ));
                $rows     = $data['rows'];
                $pager    = $data['pager'];
                $top_word = _t('Links Admin');
                $inc      = 'permalinks_custom.php';
        }
}
