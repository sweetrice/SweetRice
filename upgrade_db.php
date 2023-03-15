<?php
define('VALID_INCLUDE', true);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
$base_url = 'http://' . $_SERVER["HTTP_HOST"] . str_replace('//', '/', dirname($_SERVER["PHP_SELF"]) . '/');
define('BASE_URL', $base_url);
$root_dir = dirname(__FILE__) . '/';
define('ROOT_DIR', $root_dir);
define('INCLUDE_DIR', ROOT_DIR . 'inc/');
if (file_exists("inc/site.php")) {
    include "inc/site.php";
}
if (file_exists("inc/db.php")) {
    include "inc/db.php";
}
define('DB_LEFT', $db_left);
define('DATABASE_TYPE', $database_type);
$global_setting['cache'] = false;
include "inc/function.php";
if (function_exists('mysql_connect')) {
    define('MYSQL_LIB', 'mysql');
} else {
    define('MYSQL_LIB', 'mysqli');
}
switch ($database_type) {
    case 'sqlite':
        $dbname = 'inc/' . $db_name . '.db';
        if (is_file($dbname)) {
            $GLOBALS['db_lib'] = new sqlite_lib(array('name' => $dbname));
        }
        break;
    case 'pgsql':
        $GLOBALS['db_lib'] = new pgsql_lib(array('url' => $db_url, 'port' => $db_port, 'username' => $db_username, 'passwd' => $db_passwd, 'name' => $db_name));
        break;
    case 'mysql':
        $GLOBALS['db_lib'] = new mysql_lib(array('url' => $db_url, 'port' => $db_port, 'username' => $db_username, 'passwd' => $db_passwd, 'name' => $db_name));
        break;
}
if (!$GLOBALS['db_lib']->link) {
    header('HTTP/1.1 404 Page Not Found');
    die('db error');
}
function db_123()
{
    $rows   = db_arrays_nocache("SELECT `id`,`file_name` FROM `" . DB_LEFT . "_attachment`");
    $output = '';
    foreach ($rows as $row) {
        if (substr($row['file_name'], 0, strlen(BASE_URL)) != BASE_URL) {
            $output .= db_query("UPDATE `" . DB_LEFT . "_attachment` SET `file_name` = '" . BASE_URL . $row['file_name'] . "' WHERE `id` = '" . $row['id'] . "'");
        }
    }
    return $output;
}
function db_124()
{
    return;
}
function db_125()
{
    global $db_name, $db_url, $db_port, $db_name, $db_username, $db_passwd;
    $comments        = db_arrays_nocache("SELECT * FROM `" . DB_LEFT . "_comment`");
    $update_db       = '';
    $old_blog_exists = false;
    switch (DATABASE_TYPE) {
        case 'sqlite':
            $update_db .= createTable(DB_LEFT . '_comment', "CREATE TABLE \"" . DB_LEFT . "_comment\" (  \"id\" INTEGER PRIMARY KEY ,  \"name\" varchar(60)  default '',  \"email\" varchar(255)  default '',  \"website\" varchar(255)  ,  \"info\" text ,  \"post_id\" INTEGER  default '0',  \"post_name\" varchar(255) ,  \"post_cat\" varchar(128) ,  \"post_slug\" varchar(128) ,  \"date\" int(10)  default '0',  \"ip\" varchar(39)  default '');", true);
            $update_db .= createTable(DB_LEFT . '_options', "CREATE TABLE  \"" . DB_LEFT . "_options\" (  \"id\" INTEGER PRIMARY KEY ,  \"name\" varchar(256) UNIQUE,\"content\" text,  \"date\" int(10)  default '0') ", true);
            $update_db .= createTable(DB_LEFT . '_item_plugin', "CREATE TABLE \"" . DB_LEFT . "_item_plugin\" (  \"id\"  INTEGER PRIMARY KEY ,  \"item_id\" int(10) NOT NULL,  \"item_type\" varchar(255) NOT NULL,  \"plugin\" varchar(255) NOT NULL)", true);
            $total = db_total_nocache("SELECT COUNT(*) FROM `sqlite_master` WHERE `type` = 'table' AND `name` = '" . DB_LEFT . "_blog'");
            if ($total) {
                $old_blog_exists = true;
            }
            $db_str = "<?php \n";
            $db_str .= '$database_type = \'sqlite\';' . "\n";
            $db_str .= '$db_left = \'' . DB_LEFT . '\';' . "\n";
            $db_str .= '$db_name = \'' . $db_name . '\';' . "\n";
            $db_str .= "?>";
            file_put_contents(ROOT_DIR . 'inc/db.php', $db_str);
            break;
        case 'pgsql':
            $update_db .= createTable(DB_LEFT . '_comment', "CREATE TABLE \"" . DB_LEFT . "_comment\" (  \"id\" serial ,  \"name\" varchar(60)  default '',  \"email\" varchar(255)  default '',  \"website\" varchar(255)  ,  \"info\" text ,  \"post_id\" INT  default '0',  \"post_name\" varchar(255) ,  \"post_cat\" varchar(128) ,  \"post_slug\" varchar(128) ,  \"date\" INT  default '0',  \"ip\" varchar(39)  default '',  PRIMARY KEY  (\"id\"))", true);
            $update_db .= createTable(DB_LEFT . '_options', "CREATE TABLE \"" . DB_LEFT . "_options\" (  \"id\" serial,  \"name\" varchar(256) NOT NULL UNIQUE, \"content\" text NOT NULL,  \"date\" int NOT NULL default '0',  PRIMARY KEY  (\"id\"))", true);
            $update_db .= createTable(DB_LEFT . '_item_plugin', "CREATE TABLE \"" . DB_LEFT . "_item_plugin\" (  \"id\" serial,  \"item_id\" int NOT NULL,  \"item_type\" varchar(255) NOT NULL,  \"plugin\" varchar(255) NOT NULL,  PRIMARY KEY  (\"id\"))", true);
            $total = db_total_nocache("SELECT COUNT(*) FROM `pg_statio_user_tables` WHERE `relname` = '" . DB_LEFT . "_blog'");
            if ($total) {
                $old_blog_exists = true;
            }
            $db_str = "<?php\n";
            $db_str .= '$database_type = \'pgsql\';' . "\n";
            $db_str .= '$db_left = \'' . DB_LEFT . '\';' . "\n";
            $db_str .= '$db_url = \'' . $db_url . '\';' . "\n";
            $db_str .= '$db_port = \'' . $db_port . '\';' . "\n";
            $db_str .= '$db_name = \'' . $db_name . '\';' . "\n";
            $db_str .= '$db_username = \'' . $db_username . '\';' . "\n";
            $db_str .= '$db_passwd = \'' . $db_passwd . '\';' . "\n";
            $db_str .= "?>";
            file_put_contents(ROOT_DIR . 'inc/db.php', $db_str);
            break;
        default:
            $update_db .= createTable(DB_LEFT . '_comment', "CREATE TABLE `" . DB_LEFT . "_comment` (  `id` int(10) NOT NULL auto_increment ,  `name` varchar(60)  default '',  `email` varchar(255)  default '',  `website` varchar(255)  ,  `info` text ,  `post_id` INT  default '0',  `post_name` varchar(255) ,  `post_cat` varchar(128) ,  `post_slug` varchar(128) ,  `date` INT  default '0',  `ip` varchar(39)  default '', PRIMARY KEY  (`id`));", true);
            $update_db .= createTable(DB_LEFT . '_options', "CREATE TABLE `" . DB_LEFT . "_options` ( `id` int(10) NOT NULL auto_increment,  `name` varchar(256) NOT NULL,  `content` mediumtext NOT NULL, `date` int(10) NOT NULL, PRIMARY KEY  (`id`),  UNIQUE KEY `name` (`name`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;", true);
            $update_db .= createTable(DB_LEFT . '_item_plugin', "CREATE TABLE `" . DB_LEFT . "_item_plugin` (  `id` int(10) NOT NULL auto_increment, `item_id` int(10) NOT NULL,  `item_type` varchar(255) NOT NULL,  `plugin` varchar(255) NOT NULL,  PRIMARY KEY  (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ", true);
            $row = db_arrays("SHOW TABLES");
            foreach ($row as $key => $val) {
                if (array_search(DB_LEFT . '_blog', $val) !== false) {
                    $old_blog_exists = true;
                    break;
                }
            }
            $db_str = "<?php\n";
            $db_str .= '$database_type = \'mysql\';' . "\n";
            $db_str .= '$db_left = \'' . DB_LEFT . '\';' . "\n";
            $db_str .= '$db_url = \'' . $db_url . '\';' . "\n";
            $db_str .= '$db_port = \'' . $db_port . '\';' . "\n";
            $db_str .= '$db_name = \'' . $db_name . '\';' . "\n";
            $db_str .= '$db_username = \'' . $db_username . '\';' . "\n";
            $db_str .= '$db_passwd = \'' . $db_passwd . '\';' . "\n";
            $db_str .= "?>";
            file_put_contents(ROOT_DIR . 'inc/db.php', $db_str);
    }
    foreach ($comments as $val) {
        db_insert(DB_LEFT . '_comment', array('id', $val['id']), array('name', 'email', 'website', 'info', 'post_id', 'post_name', 'post_cat', 'post_slug', 'date', 'ip'), array($val['name'], $val['email'], $val['website'], db_escape($val['info']), intval($val['post_id']), db_escape($val['post_name']), $val['post_cat'], $val['post_slug'], intval($val['date']), $val['ip']));
    }
    if ($old_blog_exists) {
        $tmp = array();
        $row = db_array_nocache("SELECT * FROM `" . DB_LEFT . "_blog` WHERE `id` = '1'");
        foreach ($row as $key => $val) {
            if ($key != 'id') {
                $tmp[$key] = $val;
            }
        }
        $content = db_escape(serialize($tmp));
        $update_db .= dropTable(DB_LEFT . '_blog');
        setOption('global_setting', (isset($content) ? $content : db_escape($row['content'])));
    }
    $row = db_arrays_nocache("SELECT `id` FROM `" . DB_LEFT . "_posts`");
    foreach ($row as $val) {
        $total = db_total_nocache("SELECT COUNT(*) FROM `" . DB_LEFT . "_item_plugin` WHERE `item_id` = '" . $val['id'] . "' AND `item_type` = 'post'");
        if ($total == 0) {
            db_insert(DB_LEFT . "_item_plugin", array('id', ''), array('item_id', 'item_type', 'plugin'), array($val['id'], 'post', ''));
        }
    }
    $row = db_arrays_nocache("SELECT * FROM `" . DB_LEFT . "_category`");
    foreach ($row as $val) {
        $total = db_total_nocache("SELECT COUNT(*) FROM `" . DB_LEFT . "_item_plugin` WHERE `item_id` = '" . $val['id'] . "' AND `item_type` = 'category'");
        if ($total == 0) {
            db_insert(DB_LEFT . "_item_plugin", array('id', ''), array('item_id', 'item_type', 'plugin'), array($val['id'], 'category', ''));
        }
    }
    $content = db_escape(serialize($row));
    setOption('categories', ($content ? $content : db_escape($row['content'])));
    if (file_exists(ROOT_DIR . 'inc/category.php')) {
        unlink(ROOT_DIR . 'inc/category.php');
    }
    if (file_exists(ROOT_DIR . 'inc/site.php')) {
        unlink(ROOT_DIR . 'inc/site.php');
    }
    if (file_exists(ROOT_DIR . 'inc/install.lock')) {
        $old_lock = file_get_contents(ROOT_DIR . 'inc/install.lock');
        file_put_contents(ROOT_DIR . 'inc/install.lock.php', '<?php $installLock = \'' . substr($old_lock, strpos($old_lock, ':') + 1) . '\';?>');
        unlink(ROOT_DIR . 'inc/install.lock');
    }
    $old_init = file_get_contents(ROOT_DIR . 'inc/init.php');
    $new_init = preg_replace("/if\(file_exists\(INCLUDE_DIR\.\"site\.php\"\)\)[\s\n]+{[\s\n]+include\(INCLUDE_DIR\.\"site\.php\"\);[\s\n]+}/", "", $old_init);
    file_put_contents(ROOT_DIR . 'inc/init.php', $new_init);
    $content = '';
    if (file_exists(ROOT_DIR . 'inc/link.txt')) {
        $str = file(ROOT_DIR . 'inc/link.txt');
        foreach ($str as $val) {
            $val = trim($val);
            $tmp = explode('|', $val);
            if ($tmp[0] && $tmp[1]) {
                $content .= '<p><a href="' . $tmp[1] . '">' . $tmp[0] . '</a></p>';
            }
        }
        unlink(ROOT_DIR . 'inc/link.txt');
    }
    $content = db_escape($content);
    setOption('links', ($content ? $content : db_escape($row['content'])));
    return $update_db;
}
function db_130()
{
    return;
}
function db_131()
{
    return;
}
function db_132()
{
    $update_db = false;
    switch (DATABASE_TYPE) {
        case 'sqlite':
            createTable(DB_LEFT . '_links', "CREATE TABLE \"" . DB_LEFT . "_links\"(  \"lid\" INTEGER PRIMARY KEY ,  \"request\" text,  \"url\" text,  \"plugin\" varchar(255))");
            alterTable(DB_LEFT . '_comment', "ALTER TABLE \"" . DB_LEFT . "_comment\" ADD COLUMN \"reply_date\" int(10) default '0'");
            break;
        case 'pgsql':
            createTable(DB_LEFT . '_links', "CREATE TABLE \"" . DB_LEFT . "_links\"( \"lid\" serial,  \"request\" text NOT NULL,  \"url\" text NOT NULL,  \"plugin\" varchar(255) NOT NULL,  PRIMARY KEY  (\"lid\"))");
            alterTable(DB_LEFT . '_comment', "ALTER TABLE \"" . DB_LEFT . "_comment\" ADD COLUMN \"reply_date\" int NOT NULL default '0'");
            break;
        case 'mysql':
            createTable(DB_LEFT . '_links', "CREATE TABLE `" . DB_LEFT . "_links`(  `lid` int(10) NOT NULL auto_increment,  `request` text NOT NULL,  `url` text NOT NULL,  `plugin` varchar(255) NOT NULL,  PRIMARY KEY  (`lid`))");
            alterTable(DB_LEFT . '_comment', "ALTER TABLE `" . DB_LEFT . "_comment` ADD COLUMN `reply_date` int(10) NOT NULL default '0'");
            break;
    }
    if (file_exists(INCLUDE_DIR . 'permalinks.php')) {
        include INCLUDE_DIR . 'permalinks.php';
        setOption('permalinks_system', (isset($permalinks) ? serialize($permalinks) : ''));
        unlink(INCLUDE_DIR . 'permalinks.php');
    }
    if (file_exists(INCLUDE_DIR . 'url_redirect.txt')) {
        $urls         = file(INCLUDE_DIR . 'url_redirect.txt');
        $redirectList = array();
        foreach ($urls as $val) {
            $val = trim($val);
            if ($val) {
                $tmp                         = explode('->', $val);
                $redirectList[trim($tmp[0])] = trim($tmp[1]);
            }
        }
        setOption('redirectList', (isset($redirectList) ? serialize($redirectList) : ''));
        unlink(INCLUDE_DIR . 'url_redirect.txt');
    }
    $pied             = getOption('plugin_installed');
    $plugin_installed = array();
    $d                = dir(ROOT_DIR . "_plugin/");
    while (false !== ($entry = $d->read())) {
        if ($entry != '.' && $entry != '..' && file_exists(ROOT_DIR . '_plugin/' . $entry . '/plugin_config.php')) {
            if (file_exists(ROOT_DIR . '_plugin/' . $entry . '/install.lock')) {
                $plugin_installed[$entry] = time();
                unlink(ROOT_DIR . '_plugin/' . $entry . '/install.lock');
            }
        }
    }
    setOption('plugin_installed', ($plugin_installed ? serialize($plugin_installed) : $pied['content']));

    if (file_exists(ROOT_DIR . 'inc/setting.php')) {
        include ROOT_DIR . 'inc/setting.php';
    }
    if (!isset($dashboard_dir)) {
        $dashboard_dir = 'as';
    }
    if (is_dir(ROOT_DIR . $dashboard_dir . '/lib/mysql_backup')) {
        rename(ROOT_DIR . $dashboard_dir . '/lib/mysql_backup', ROOT_DIR . 'inc/mysql_backup');
    }
    if (is_dir(ROOT_DIR . $dashboard_dir . '/lib/pgsql_backup')) {
        rename(ROOT_DIR . $dashboard_dir . '/lib/pgsql_backup', ROOT_DIR . 'inc/pgsql_backup');
    }
    if (is_dir(ROOT_DIR . $dashboard_dir . '/lib/sqlite_backup')) {
        rename(ROOT_DIR . $dashboard_dir . '/lib/sqlite_backup', ROOT_DIR . 'inc/sqlite_backup');
    }
    return $update_db;
}

function sites_133()
{
    $GLOBALS['db_lib_root'] = $GLOBALS['db_lib'];
    $site_home              = ROOT_DIR . '_sites/';
    if (!is_dir($site_home)) {
        return;
    }
    $d = dir($site_home);
    while (false !== ($entry = $d->read())) {
        if ($entry != '.' && $entry != '..' && file_exists($site_home . $entry . '/inc/db.php')) {
            include $site_home . $entry . '/inc/db.php';
            if (!isset($database_type) || !isset($db_name)) {
                continue;
            }
            switch ($database_type) {
                case 'sqlite':
                    $dbname            = $site_home . $entry . 'inc/' . $db_name . '.db';
                    $GLOBALS['db_lib'] = new sqlite_lib(array('name' => $dbname));
                    break;
                case 'pgsql':
                    $GLOBALS['db_lib'] = new pgsql_lib(array('url' => $db_url, 'port' => $db_port, 'username' => $db_username, 'passwd' => $db_passwd, 'name' => $db_name));
                    break;
                case 'mysql':
                    $GLOBALS['db_lib'] = new mysql_lib(array('url' => $db_url, 'port' => $db_port, 'username' => $db_username, 'passwd' => $db_passwd, 'name' => $db_name));
                    break;
            }
            if (!$GLOBALS['db_lib']->link) {
                header('HTTP/1.1 404 Page Not Found');
                die('db error');
            }
            $plugin_installed = $plugin_installeds = array();
            $optionRow        = db_array("SELECT * FROM `" . $db_left . "_options` WHERE `name` = 'plugin_installed'", 'ASSOC');
            if ($optionRow['content']) {
                $plugin_installed = unserialize($optionRow['content']);
            }
            $site_plugin_dir = $site_home . $entry . "/_plugin/";
            $d_plugin        = dir($site_plugin_dir);
            while (false !== ($entry_plugin = $d_plugin->read())) {
                if ($entry_plugin != '.' && $entry_plugin != '..' && file_exists($site_plugin_dir . $entry_plugin . '/plugin_config.php')) {
                    if ($plugin_installed[$entry_plugin]) {
                        include $site_plugin_dir . $entry_plugin . '/plugin_config.php';
                        if (isset($plugin_config) && $entry != $plugin_config['name']) {
                            $plugin_installed[$plugin_config['name']] = time();
                            $plugin_installed[$entry]                 = false;
                        }
                    }
                }
            }
            $d_plugin->close();
            foreach ($plugin_installed as $key => $val) {
                if ($val) {
                    $plugin_installeds[$key] = $val;
                }
            }
            db_insert($db_left . '_options', array('id', $optionRow['id']), array('name', 'content', 'date'), array('plugin_installed', serialize($plugin_installeds), time()));
        }
    }
    $d->close();
    $GLOBALS['db_lib'] = $GLOBALS['db_lib_root'];
    return;
}

function db_133()
{
    $plugin_installed = $plugin_installeds = array();
    $optionRow        = getOption('plugin_installed');
    if ($optionRow['content']) {
        $plugin_installed = unserialize($optionRow['content']);
    }
    $d = dir(ROOT_DIR . "_plugin/");
    while (false !== ($entry = $d->read())) {
        if ($entry != '.' && $entry != '..' && file_exists(ROOT_DIR . '_plugin/' . $entry . '/plugin_config.php')) {
            if ($plugin_installed[$entry]) {
                include ROOT_DIR . '_plugin/' . $entry . '/plugin_config.php';
                if (isset($plugin_config) && $entry != $plugin_config['name']) {
                    $plugin_installed[$plugin_config['name']] = time();
                    $plugin_installed[$entry]                 = false;
                }
            }
        }
    }
    $d->close();
    foreach ($plugin_installed as $key => $val) {
        if ($val) {
            $plugin_installeds[$key] = $val;
        }
    }
    setOption('plugin_installed', serialize($plugin_installeds));
    return sites_133();
}

function db_140()
{
    $dashboard_dir = 'as';
    if (file_exists(ROOT_DIR . 'inc/setting.php')) {
        include ROOT_DIR . 'inc/setting.php';
    }
    include $dashboard_dir . '/lib/function.php';
    un_('_plugin/tiny_mce/');
    un_('_plugin/subscriber/js/');
}

function db_141()
{
    $update_db = '';
    switch (DATABASE_TYPE) {
        case 'sqlite':
            $update_db .= createTable(DB_LEFT . '_item_data', "CREATE TABLE \"" . DB_LEFT . "_item_data\"(   \"id\" INTEGER PRIMARY KEY ,  \"item_id\" int(10),  \"item_type\" varchar(255),  \"data_type\" varchar(20),  \"name\" varchar(255),  \"value\" text)");
            db_query("CREATE INDEX item_data_item_id_index ON " . DB_LEFT . "_item_data(item_id)");
            db_query("CREATE INDEX item_data_item_type_index ON " . DB_LEFT . "_item_data(item_type)");
            db_query("CREATE INDEX item_data_name_index ON " . DB_LEFT . "_item_data(name)");
            break;
        case 'pgsql':
            $update_db .= createTable(DB_LEFT . '_item_data', "CREATE TABLE \"" . DB_LEFT . "_item_data\"(  \"id\" serial,  \"item_id\" int NOT NULL,  \"item_type\" varchar(255) NOT NULL,  \"data_type\" varchar(20) NOT NULL,  \"name\" varchar(255) NOT NULL,  \"value\" text NOT NULL,	KEY (\"item_id\"),	KEY (\"item_type\"),	KEY (\"name\"),  PRIMARY KEY  (\"id\")");
            break;
        case 'mysql':
            $update_db .= createTable(DB_LEFT . '_item_data', "CREATE TABLE `" . DB_LEFT . "_item_data`(  `id` int(10) NOT NULL auto_increment,  `item_id` int(10) NOT NULL,  `item_type` varchar(255) NOT NULL,  `data_type` varchar(20) NOT NULL,  `name` varchar(255) NOT NULL,  `value` text NOT NULL,	KEY (`item_id`),	KEY (`item_type`),	KEY (`name`),  PRIMARY KEY  (`id`)");
            break;
    }
    return $update_db;
}

function db_150()
{
    $data      = getPosts();
    $tag_posts = array();
    foreach ($data['rows'] as $row) {
        if (!$row['tags']) {
            continue;
        }
        $taglist = explode(',', $row['tags']);
        foreach ($taglist as $val) {
            $tag_posts[$val][] = $row['id'];
        }
    }
    setOption('tag_posts', serialize($tag_posts));
}

function db_151()
{
    $update_db = '';
    switch (DATABASE_TYPE) {
        case 'pgsql':
            $tablelist = db_list();
            foreach ($tablelist as $table) {
                $rows = db_arrays("SELECT attnum,attname , typname , atttypmod-4 , attnotnull ,atthasdef ,adsrc AS def FROM pg_attribute, pg_class, pg_type, pg_attrdef WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND pg_class.oid=adrelid AND adnum=attnum AND atthasdef='t' AND lower(relname)='$table' UNION SELECT attnum,attname , typname , atttypmod-4 , attnotnull , atthasdef ,'' AS def FROM pg_attribute, pg_class, pg_type WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum > 0 AND atthasdef='f' AND lower(relname)='$table' order by attnum");
                foreach ($rows as $r) {
                    if (preg_match('/nextval\(\'' . $table . '_.+_seq\'::regclass\).*/', $r['def'])) {
                        $serial_field = $r['attname'];
                        $update_db .= db_query("SELECT setval('" . $table . "_" . $serial_field . "_seq', (SELECT MAX(" . $serial_field . ") FROM " . $table . ")+1);");
                        break;
                    }
                }
            }
            $site_home = ROOT_DIR . '_sites/';
            if (is_dir($site_home)) {
                $GLOBALS['db_lib_root'] = $GLOBALS['db_lib'];
                $d                      = dir($site_home);
                while (false !== ($entry = $d->read())) {
                    if ($entry != '.' && $entry != '..' && file_exists($site_home . $entry . '/inc/db.php')) {
                        include $site_home . $entry . '/inc/db.php';
                        switch ($database_type) {
                            case 'pgsql':
                                $GLOBALS['db_lib'] = new pgsql_lib(array('url' => $db_url, 'port' => $db_port, 'username' => $db_username, 'passwd' => $db_passwd, 'name' => $db_name));
                                $tablelist         = db_list();
                                foreach ($tablelist as $table) {
                                    $table = str_replace(DB_LEFT . '_', $db_left . '_', $table);
                                    $rows  = db_arrays("SELECT attnum,attname , typname , atttypmod-4 , attnotnull ,atthasdef ,adsrc AS def FROM pg_attribute, pg_class, pg_type, pg_attrdef WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND pg_class.oid=adrelid AND adnum=attnum AND atthasdef='t' AND lower(relname)='$table' UNION SELECT attnum,attname , typname , atttypmod-4 , attnotnull , atthasdef ,'' AS def FROM pg_attribute, pg_class, pg_type WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum > 0 AND atthasdef='f' AND lower(relname)='$table' order by attnum");
                                    foreach ($rows as $r) {
                                        if (preg_match('/nextval\(\'' . $table . '_.+_seq\'::regclass\).*/', $r['def'])) {
                                            $serial_field = $r['attname'];
                                            $update_db .= db_query("SELECT setval('" . $table . "_" . $serial_field . "_seq', (SELECT MAX(" . $serial_field . ") FROM " . $table . ")+1);");
                                            break;
                                        }
                                    }
                                }
                                break;
                        }
                    }
                }
                $d->close();
                $GLOBALS['db_lib'] = $GLOBALS['db_lib_root'];
            }
            return $update_db;
            break;
    }
}

function upgrade_db()
{
    $upgrade_funs      = array(123, 124, 125, 130, 132, 133, 140, 141, 150, 151);
    $installed_version = str_replace('.', '', file_get_contents('inc/lastest.txt'));
    $update_db         = '';
    foreach ($upgrade_funs as $val) {
        if ($val >= $installed_version) {
            $update_db .= call_user_func('db_' . $val);
        }
    }
    if ($update_db) {
        return $update_db;
    } else {
        return 'Successfully';
    }
}
echo upgrade_db();
