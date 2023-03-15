<?php
/**
 * Database backup for Mysql.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
function sql2data($table)
{
    $tabledump   = array();
    $bk_table    = '%--%' . substr($table, strlen(DB_LEFT));
    $tabledump[] = "DROP TABLE IF EXISTS `$bk_table`;";
    $create      = $GLOBALS['db_lib']->fetch_row($GLOBALS['db_lib']->query("SHOW CREATE TABLE `$table`"));
    $tabledump[] = str_replace($table, $bk_table, $create[1]) . ";";
    $res         = $GLOBALS['db_lib']->query("SELECT * FROM `$table`");
    $numfields   = $GLOBALS['db_lib']->num_fields($res);
    while ($row = $GLOBALS['db_lib']->fetch_row($res)) {
        $comma = "";
        $tmp   = "INSERT INTO `$bk_table` VALUES(";
        for ($i = 0; $i < $numfields; $i++) {
            $tmp .= $comma . "'" . $GLOBALS['db_lib']->real_escape_string($row[$i]) . "'";
            $comma = ",";
        }
        $tmp .= ");";
        $tabledump[] = $tmp;
    }
    return $tabledump;
}
$tablelist = $_POST['tablelist'];
$data      = array();
foreach ($tablelist as $table) {
    $data = array_merge($data, sql2data($table));
}
$db_backup_dir = SITE_HOME . 'inc/mysql_backup';
if (!file_exists($db_backup_dir)) {
    mkdir($db_backup_dir);
}
$bak_name = 'mysql_bakup_' . date('YmdHis') . '-' . SR_VERSION . '.sql';
file_put_contents($db_backup_dir . '/' . $bak_name, '<?php return ' . var_export($data, true) . ';?>');
