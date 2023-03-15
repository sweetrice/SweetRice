<?php
/**
 * Media Center management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
define('MEDIA_DIR', ROOT_DIR . ATTACHMENT_DIR);
define('STRLEN_MEDIA_DIR', strlen(MEDIA_DIR));
$mode = $_GET['mode'];
switch ($mode) {
    case 'delete':
        $no = $_POST['no'];
        $f  = MEDIA_DIR . js_unescape($_POST['file']);
        if (is_dir($f) && substr($f, 0, STRLEN_MEDIA_DIR) == MEDIA_DIR) {
            if (@rmdir($f)) {
                $do_delete = true;
            } else {
                output_json(array('status' => '0', 'id' => $f, 'no' => $no, 'status_code' => _t('Not exists or not empty.')));
            }
        } elseif (is_file($f) && substr($f, 0, STRLEN_MEDIA_DIR) == MEDIA_DIR) {
            if (@unlink($f)) {
                $do_delete = true;
            }
        } else {
            output_json(array('status' => '0', 'id' => $f, 'no' => $no, 'status_code' => _t('Not exists or not empty.')));
        }
        if (isset($do_delete)) {
            output_json(array('status' => '1', 'id' => js_unescape($_POST['file']), 'no' => $no, 'status_code' => vsprintf(_t('%s (%s) has been delete successfully.'), array(_t('Media'), js_unescape($_POST['file'])))));
        } else {
            output_json(array('status' => '0', 'id' => js_unescape($_POST['file']), 'no' => $no, 'status_code' => _t('Failed') . $f));
        }
        break;
    case 'mkdir':
        $parent_dir = file_exists(MEDIA_DIR . $_POST['parent_dir']) ? MEDIA_DIR . $_POST['parent_dir'] : MEDIA_DIR;
        $new_dir    = $_POST['new_dir'];
        if (!is_dir($parent_dir . $new_dir)) {
            mkdir($parent_dir . $new_dir);
        }
        _goto('./?type=media_center&dir=' . substr($parent_dir . $new_dir . '/', STRLEN_MEDIA_DIR));
        break;
    case 'bulk':
        $plist = $_POST['plist'];
        $dlist = array();
        foreach ($plist as $val) {
            $tmp = $val;
            $val = MEDIA_DIR . $val;
            if (is_file($val) && substr($val, 0, STRLEN_MEDIA_DIR) == MEDIA_DIR) {
                @unlink($val);
                $dlist[] = $tmp;
            }
        }
        output_json(array('status' => 1, 'status_code' => vsprintf(_t('%s (%s) has been delete successfully.'), array(_t('Media'), implode(',', $dlist)))));
        break;
    case 'upload':
        $_POST['dir_name'] = str_replace('../', '', $_POST['dir_name']);
        $dest_dir          = file_exists(MEDIA_DIR . $_POST['dir_name']) ? MEDIA_DIR . $_POST['dir_name'] : MEDIA_DIR;
        if (is_array($_FILES['upload']['name'])) {
            foreach ($_FILES['upload']['name'] as $key => $val) {
                $tmp = array(
                    'name'     => $_FILES['upload']['name'][$key],
                    'type'     => $_FILES['upload']['type'][$key],
                    'tmp_name' => $_FILES['upload']['tmp_name'][$key],
                    'error'    => $_FILES['upload']['error'][$key],
                    'size'     => $_FILES['upload']['size'][$key],
                );
                if (substr($tmp['name'], -4) == '.zip' && $_POST['unzip']) {
                    extractZIP($tmp['tmp_name'], $dest_dir, true);
                } else {
                    upload_($tmp, $dest_dir, $tmp['name'], null);
                }
            }
        } else {
            if (substr($_FILES['upload']['name'], -4) == '.zip' && $_POST['unzip']) {
                extractZIP($_FILES['upload']['tmp_name'], $dest_dir, true);
            } else {
                upload_($_FILES['upload'], $dest_dir, $_FILES['upload']['name'], null);
            }
        }
        _goto($_SERVER['HTTP_REFERER']);
        break;
    default:
        $_dir = MEDIA_DIR . $_GET['dir'];
        if (file_exists($_dir) && substr($_dir, 0, STRLEN_MEDIA_DIR) == MEDIA_DIR) {
            $_open_dir = $_dir;
            $tmp       = explode('/', substr($_dir, 0, -1));
            if (count($tmp) > 0) {
                $parent = str_replace(end($tmp) . '/', '', $_dir);
                $parent = substr($parent, STRLEN_MEDIA_DIR);
            }
        } else {
            $_open_dir = MEDIA_DIR;
        }
        $open_dir = substr($_open_dir, STRLEN_MEDIA_DIR);
        $keyword  = $_GET['keyword'];
        $files    = array();
        $tmp_list = array();
        $tmp_data = array();
        if (is_dir($_open_dir)) {
            $d = dir($_open_dir);
            while (false !== ($entry = $d->read())) {
                if ($entry != '.' && $entry != '..') {
                    if (isset($keyword)) {
                        if (strpos($entry, $keyword) !== false) {
                            $tmp = array('name' => $entry, 'type' => (is_dir($_open_dir . $entry) ? 'dir' : sr_file_type($_open_dir . $entry)), 'date' => filemtime($_open_dir . $entry), 'link' => $open_dir . $entry);
                            if (!in_array(filemtime($_open_dir . $entry), $tmp_list)) {
                                $files[filemtime($_open_dir . $entry)] = $tmp;
                                $tmp_list[]                            = filemtime($_open_dir . $entry);
                            } else {
                                $tmp_data[filemtime($_open_dir . $entry)][] = $tmp;
                            }
                        }
                    } else {
                        $tmp = array('name' => $entry, 'type' => (is_dir($_open_dir . $entry) ? 'dir' : sr_file_type($_open_dir . $entry)), 'date' => filemtime($_open_dir . $entry), 'link' => $open_dir . $entry);
                        if (!in_array(filemtime($_open_dir . $entry), $tmp_list)) {
                            $files[filemtime($_open_dir . $entry)] = $tmp;
                            $tmp_list[]                            = filemtime($_open_dir . $entry);
                        } else {
                            $tmp_data[filemtime($_open_dir . $entry)][] = $tmp;
                        }
                    }
                }
            }
            $d->close();
        }
        if (count($files) > 0) {
            krsort($files);
            $_files = array();
            foreach ($files as $key => $val) {
                $_files[] = $val;
                if (!is_array($tmp_data[$key])) {
                    continue;
                }
                foreach ($tmp_data[$key] as $v) {
                    $_files[] = $v;
                }
            }
            $files = $_files;
        }
        $total      = count($files);
        $page_limit = page_limit();
        $p_link     = './?type=media_center&dir=' . $open_dir . '&' . ($keyword ? 'keyword=' . $keyword . '&' : '');
        $pager      = _pager($total, $page_limit, $p_link);
        $top_word   = _t('Media Center');
        define('UPLOAD_MAX_FILESIZE', ini_get('upload_max_filesize'));
        $inc = 'media_center.php';
}
