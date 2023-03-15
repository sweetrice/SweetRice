<?php
/**
 * Convert Database to Mysql.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.5
 */
defined('VALID_INCLUDE') or die();
$to_db_name     = $_POST['to_db_name'];
$to_db_left     = $_POST['to_db_left'];
$to_db_url      = $_POST['to_db_url'];
$to_db_port     = $_POST['to_db_port'];
$to_db_username = $_POST['to_db_username'];
$to_db_passwd   = $_POST['to_db_passwd'];
$tablelist      = $_POST['tablelist'];

if (DATABASE_TYPE == 'mysql' && $to_db_name == $db_name && $to_db_left == DB_LEFT && $to_db_url == $db_url && $to_db_port == $db_port) {
    output_json(array('status' => 1, 'status_code' => _t('Database convert successfully!')));
}
if ($to_db_name && $to_db_left && $tablelist) {
    $plugin_sql  = array();
    $plugin_list = pluginList();
    foreach ($plugin_list as $plugin_config) {
        if (file_exists(SITE_HOME . '_plugin/' . $plugin_config['directory'] . '/plugin_config.php') && $plugin_config['installed']) {
            if ($plugin_config['install_sql']) {
                $plugin_sql[$plugin_config['name']] = SITE_HOME . '_plugin/' . $plugin_config['directory'] . '/' . $plugin_config['install_sql'];
            }
        }
    }
    $GLOBALS['to_db_lib'] = new mysql_lib(array('url' => $to_db_url, 'port' => $to_db_port, 'username' => $to_db_username, 'passwd' => $to_db_passwd, 'name' => $to_db_name, 'newlink' => true));
    if (!$GLOBALS['to_db_lib']->stat()) {
        $message .= _t('Database error!') . ' <br>';
    } else {
        $sql = file_get_contents('lib/app.sql');
        $sql = str_replace('%--%', $to_db_left, $sql);
        $sql = explode(';', $sql);
        foreach ($sql as $key => $val) {
            if (trim($val)) {
                if (!$GLOBALS['to_db_lib']->query($val)) {
                    $message .= $val . ' : ' . $GLOBALS['to_db_lib']->error() . '<br>';
                    break;
                }
            }
        }
        foreach ($plugin_sql as $key => $val) {
            $sql = file_get_contents($val);
            $sql = str_replace('%--%', $to_db_left . '_plugin', $sql);
            $sql = explode(';', $sql);
            foreach ($sql as $key => $val) {
                if (trim($val)) {
                    if (!$GLOBALS['to_db_lib']->query($val)) {
                        $message .= $val . ' : ' . $GLOBALS['to_db_lib']->error() . '<br>';
                        break;
                    }
                }
            }
        }
        $db_error = null;
        switch (DATABASE_TYPE) {
            case 'sqlite':
                foreach ($tablelist as $val) {
                    $to_val     = $to_db_left . substr($val, strlen(DB_LEFT));
                    $field_list = array();
                    $rows       = db_arrays("SELECT * FROM `" . $val . "`");
                    $fields     = db_arrays("PRAGMA table_info(" . $val . ")");
                    foreach ($fields as $field) {
                        $field_list[] = $field['name'];
                    }
                    foreach ($rows as $row) {
                        $comma     = "";
                        $tabledump = "INSERT INTO `" . $to_val . "` VALUES(";
                        foreach ($field_list as $fl) {
                            if (is_string($row[$fl])) {
                                $str = addslashes($row[$fl]);
                            } else {
                                $str = $row[$fl];
                            }
                            $tabledump .= $comma . "'" . $str . "'";
                            $comma = ",";
                        }
                        $tabledump .= ");";
                        if (!$GLOBALS['to_db_lib']->query($tabledump)) {
                            $db_error .= $tabledump . ' : ' . $GLOBALS['to_db_lib']->error() . '<br>';
                            break;
                        }
                    }
                }
                if (!$db_error) {
                    $do_db = true;
                } else {
                    $message .= $db_error;
                }
                break;
            case 'mysql':
                foreach ($tablelist as $val) {
                    $to_val    = $to_db_left . substr($val, strlen(DB_LEFT));
                    $res       = $GLOBALS['db_lib']->query("SELECT * FROM `" . $val . "`");
                    $numfields = $GLOBALS['db_lib']->num_fields($res);
                    while ($row = $GLOBALS['db_lib']->fetch_row($res)) {
                        $comma     = "";
                        $tabledump = "INSERT INTO `" . $to_val . "` VALUES(";
                        for ($i = 0; $i < $numfields; $i++) {
                            if (is_string($row[$i])) {
                                $str = addslashes($row[$i]);
                            } else {
                                $str = $row[$i];
                            }
                            $tabledump .= $comma . "'" . $str . "'";
                            $comma = ",";
                        }
                        $tabledump .= ");";
                        if (!$GLOBALS['to_db_lib']->query($tabledump)) {
                            $db_error .= $tabledump . ' : ' . $GLOBALS['to_db_lib']->error() . '<br>';
                        }
                    }
                }
                if (!$db_error) {
                    $do_db = true;
                } else {
                    $message .= $db_error;
                }
                break;
            case 'pgsql':
                foreach ($tablelist as $val) {
                    $to_val    = $to_db_left . substr($val, strlen(DB_LEFT));
                    $res       = $GLOBALS['db_lib']->query("SELECT * FROM \"" . $val . "\"");
                    $numfields = $GLOBALS['db_lib']->num_fields($res);
                    while ($row = $GLOBALS['db_lib']->fetch_row($res)) {
                        $comma     = "";
                        $tabledump = "INSERT INTO `" . $to_val . "` VALUES(";
                        for ($i = 0; $i < $numfields; $i++) {
                            if (is_string($row[$i])) {
                                $str = addslashes($row[$i]);
                            } else {
                                $str = $row[$i];
                            }
                            $tabledump .= $comma . "'" . $str . "'";
                            $comma = ",";
                        }
                        $tabledump .= ");";
                        if (!$GLOBALS['to_db_lib']->query($tabledump)) {
                            $db_error .= $tabledump . ' : ' . $GLOBALS['to_db_lib']->error() . '<br>';
                        }
                    }
                }
                if (!$db_error) {
                    $do_db = true;
                } else {
                    $message .= $db_error;
                }
                break;
        }
    }
    if ($do_db) {
        $db_str = "<?php\n";
        $db_str .= '$database_type = \'mysql\';' . "\n";
        $db_str .= '$db_left = \'' . $to_db_left . '\';' . "\n";
        $db_str .= '$db_url = \'' . $to_db_url . '\';' . "\n";
        $db_str .= '$db_port = \'' . $to_db_port . '\';' . "\n";
        $db_str .= '$db_name = \'' . $to_db_name . '\';' . "\n";
        $db_str .= '$db_username = \'' . $to_db_username . '\';' . "\n";
        $db_str .= '$db_passwd = \'' . $to_db_passwd . '\';' . "\n";
        $db_str .= "?>";
        file_put_contents(SITE_HOME . 'inc/db.php', $db_str);
        $GLOBALS['to_db_lib']->close();
        if (DATABASE_TYPE == 'sqlite') {
            $GLOBALS['db_lib']->close();
            unlink(SITE_HOME . 'inc/' . $db_name . '.db');
        }
        output_json(array('status' => 1, 'status_code' => _t('Database convert successfully!')));
    } else {
        output_json(array('status' => 0, 'status_code' => $message));
    }
} else {
    output_json(array('status' => 0, 'status_code' => _t('Please fill out form below.')));
}
