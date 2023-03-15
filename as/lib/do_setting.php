<?php
/**
 * Site management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
defined('VALID_INCLUDE') or die();
$mode = $_GET['mode'];
switch ($mode) {
    case 'save':
        $dashboard_dirs = preg_replace('/[^\w_\-]+/', '', $_POST['dashboard_dirs']);
        if ($dashboard_dirs != DASHBOARD_DIR && !file_exists(ROOT_DIR . $dashboard_dirs)) {
            rename(ROOT_DIR . DASHBOARD_DIR, ROOT_DIR . $dashboard_dirs);
        } else {
            $dashboard_dirs = false;
        }
        $tmp = '<?php' . "\n";
        $tmp .= '$dashboard_dir = \'' . ($dashboard_dirs ? $dashboard_dirs : DASHBOARD_DIR) . '\';' . "\n";
        $tmp .= '?>';
        file_put_contents(ROOT_DIR . 'inc/setting.php', $tmp);
        if ($_POST['url_rewrite']) {
            ob_start();
            phpinfo(INFO_MODULES);
            $str = ob_get_contents();
            ob_end_clean();
            if ('apache2handler' == php_sapi_name() && strpos($str, 'mod_rewrite')) {
                $htaccess = file_get_contents('../inc/htaccess.txt');
                $htaccess = str_replace('%--%', str_replace('//', '/', dirname(str_replace('/' . DASHBOARD_DIR, '', $_SERVER['PHP_SELF'])) . '/'), $htaccess);
                file_put_contents('../.htaccess', $htaccess);
            }
        } else {
            if (file_exists('../.htaccess')) {
                unlink('../.htaccess');
            }
        }
        $logo                    = upload_($_FILES['logo'], '../' . ATTACHMENT_DIR, $_FILES['logo']['name'], $_POST['old_logo']);
        $passwd                  = $_POST['passwd'] ? md5($_POST['passwd']) : $_POST['old_passwd'];
        $setting                 = $_POST['global_setting'];
        $setting['log']          = $logo;
        $setting['passwd']       = $passwd;
        $setting['last_setting'] = time();
        $setting['close_tip']    = toggle_attachment($setting['close_tip']);
        $setting['name']         = escape_string($setting['name']);
        $setting['author']       = escape_string($setting['author']);
        $setting['title']        = escape_string($setting['title']);
        $setting['keywords']     = escape_string($setting['keywords']);
        $setting['description']  = escape_string($setting['description']);
        setOption('global_setting', serialize($setting));
        save_custom_field($_POST, 'setting', 1);
        _goto(BASE_URL . ($dashboard_dirs ? $dashboard_dirs : DASHBOARD_DIR) . '/?type=setting');
        break;
    case 'update':
        $data = $_POST['data'];
        if (!$_GET['submode']) {
            output_json(array('status' => 0));
        }
        $_global_setting = getOption('global_setting');
        $_global_setting = unserialize(clean_quotes($_global_setting['content']));
        switch ($_GET['submode']) {
            case 'dashboard_lang':
                if (!$data || file_exists(INCLUDE_DIR . 'lang/' . $data)) {
                    $_global_setting['lang'] = $data;
                }
                break;
            case 'front_lang':
                if (!$data || file_exists(INCLUDE_DIR . 'lang/' . $data)) {
                    $_global_setting['theme_lang'] = $data;
                }
                break;
            case 'theme':
                if (!$data || is_dir(ROOT_DIR . '_themes/' . $data)) {
                    $_global_setting['theme'] = $data;
                }
                break;
            case 'url_rewrite':
                $_global_setting['url_rewrite'] = intval($data);
                break;
            case 'close':
                $_global_setting['close'] = intval($data);
                break;
        }
        setOption('global_setting', serialize($_global_setting));
        output_json(array('status' => 1));
        break;
    default:
        define('UPLOAD_MAX_FILESIZE', ini_get('upload_max_filesize'));
        $themes                                  = getThemeTypes();
        $s_theme                                 = array();
        $s_theme[$global_setting['theme']]       = 'selected';
        $lang                                    = getLangTypes(INCLUDE_DIR . 'lang/');
        $lang_types                              = getLangTypes();
        $s_lang                                  = array();
        $s_lang[$global_setting['theme_lang']]   = 'selected';
        $dashboard_lang                          = array();
        $dashboard_lang[$global_setting['lang']] = 'selected';
        $top_word                                = _t('General Setting');
        $cf_rows                                 = db_arrays("SELECT * FROM `" . DB_LEFT . "_item_data` WHERE `item_id` = 1 AND `item_type` = 'setting'");
        $inc                                     = 'site.php';
}
