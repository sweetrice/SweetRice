<?php
/**
 * Sitemap Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$type  = $_GET['type'];
$hList = array();
$hRow  = getOption('hidden_from_sitemap');
if ($hRow['content']) {
    $hList = unserialize($hRow['content']);
}
$index_setting = getOption('index_setting');
if ($index_setting['content']) {
    $index_setting = unserialize($index_setting['content']);
}
$modes   = array('category' => _t('Category'), 'post' => _t('Post'), 'custom' => _t('Custom'));
$lList   = array();
$mode    = $_GET['mode'];
$pager   = array('p_link' => $type == 'xml' ? show_link_sitemapXml(false, $mode) : show_link_sitemapHtml(false, $mode), 'page_limit' => $global_setting['nums_setting'][$mode . '_link_per_page'], 'pager_function' => 'pager');
$lList[] = array('link_html' => BASE_URL . show_link_sitemapHtml(), 'link_html_body' => _t('Sitemap'), 'link_xml' => BASE_URL . show_link_sitemapXml());
foreach ($modes as $key => $val) {
    $lList[] = array('link_html' => BASE_URL . show_link_sitemapHtml(false, $key), 'link_html_body' => $val, 'link_xml' => BASE_URL . show_link_sitemapXml(false, $key));
}
switch ($mode) {
    case 'custom':
        $data = db_fetch(array('table' => "`" . DB_LEFT . "_links`",
            'order'                        => " `url` ASC",
            'pager'                        => $pager,
        ));
        for ($i = 1; $i < $data['pager']['page_total']; $i++) {
            $lList[] = array('link_html' => BASE_URL . show_link_sitemapHtml(false, $mode, $i + 1), 'link_html_body' => _t(ucfirst($mode)) . ' ' . ($i + 1), 'link_xml' => BASE_URL . show_link_sitemapXml(false, $mode, $i + 1));
        }
        foreach ($data['rows'] as $val) {
            if (URL_REWRITE) {
                if (!in_array($val['url'], $hList) && $val['url'] != $index_setting['url']) {
                    $lList[] = array('link_html' => BASE_URL . $val['url'], 'link_html_body' => $val['url'], 'type' => 'custom');
                }
            } else {
                $reqs = unserialize($val['request']);
                if ($reqs) {
                    $original_url = '?';
                    foreach ($reqs as $key => $val) {
                        $original_url .= $key . '=' . $val . '&';
                    }
                    $original_url = substr($original_url, 0, -1);
                } else {
                    $original_url = $row['url'];
                }
                if (!in_array($original_url, $hList) && $original_url != $index_setting['req']) {
                    $lList[] = array('link_html' => BASE_URL . $original_url, 'link_html_body' => $original_url, 'type' => 'custom');
                }
            }
        }
        break;
    case 'post':
        $data = db_fetch(array('table' => "`" . DB_LEFT . "_posts`",
            'where'                        => "`in_blog` = '1'",
            'order'                        => " `date` DESC",
            'pager'                        => $pager,
        ));
        for ($i = 1; $i < $data['pager']['page_total']; $i++) {
            $lList[] = array('link_html' => BASE_URL . show_link_sitemapHtml(false, $mode, $i + 1), 'link_html_body' => _t(ucfirst($mode)) . ' ' . ($i + 1), 'link_xml' => BASE_URL . show_link_sitemapXml(false, $mode, $i + 1));
        }
        foreach ($data['rows'] as $key => $row) {
            if (!in_array(show_link_page($categories[$row['category']]['link'], $row['sys_name']), $hList) && show_link_page($categories[$row['category']]['link'], $row['sys_name']) != $index_setting['url']) {
                $lList[] = array('link_html' => BASE_URL . show_link_page($categories[$row['category']]['link'], $row['sys_name']), 'link_html_body' => $row['name'], 'link_xml' => show_link_page_xml($row['sys_name']), 'link_xml_body' => '<img src="images/xmlrss.png">', 'type' => 'post', 'date' => $row['date']);
            }
        }
        break;
    case 'category':
        $data = db_fetch(array('table' => "`" . DB_LEFT . "_category`",
            'order'                        => " `ID` ASC",
            'pager'                        => $pager,
        ));
        for ($i = 1; $i < $data['pager']['page_total']; $i++) {
            $lList[] = array('link_html' => BASE_URL . show_link_sitemapHtml(false, $mode, $i + 1), 'link_html_body' => _t(ucfirst($mode)) . ' ' . ($i + 1), 'link_xml' => BASE_URL . show_link_sitemapXml(false, $mode, $i + 1));
        }
        foreach ($data['rows'] as $val) {
            if (!in_array(show_link_cat($val['link'], ''), $hList) && show_link_cat($val['link'], '') != $index_setting['url']) {
                $lList[] = array('link_html' => BASE_URL . show_link_cat($val['link'], ''), 'link_html_body' => $val['name'], 'link_xml' => show_link_cat_xml($val['link']), 'link_xml_body' => '<img src="images/xmlrss.png">', 'type' => 'category');
            }
        }
        break;
}
if ($type == 'xml') {
    $last_modify = pushDate(array($rows, array('date' => $hRow['date'])));
    include 'inc/sitemap_xml.php';
    exit();
} else {
    $title       = _t('Sitemap') . ($modes[$mode] ? ' ' . $modes[$mode] . ($_GET['p'] > 0 ? ' ' . _t('Page') . ' ' . $_GET['p'] : '') : '') . ' - ' . $global_setting['name'];
    $description = $global_setting['name'] . ' ' . _t('Sitemap') . ($modes[$mode] ? ' ' . $modes[$mode] . ($_GET['p'] > 0 ? ' ' . _t('Page') . ' ' . $_GET['p'] : '') : '');
    $keywords    = $global_setting['keywords'];
    $inc         = THEME_DIR . $page_theme['sitemap'];
    $last_modify = pushDate(array($rows, array(array('date' => filemtime($inc)), array('date' => $hRow['date']))));
}
