<?php
/**
 * Database backup for PostgreSql.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
function sql2data($table)
{
    $tabledump    = array();
    $serial_field = '';
    $bk_table     = '%--%' . substr($table, strlen(DB_LEFT));
    $tabledump[]  = "DROP TABLE IF EXISTS \"$bk_table\" CASCADE;";
    $createsql    = "CREATE TABLE \"$bk_table\" (";
    $rows         = db_arrays("SELECT attnum,attname , typname , atttypmod-4 , attnotnull ,atthasdef ,adsrc AS def FROM pg_attribute, pg_class, pg_type, pg_attrdef WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND pg_class.oid=adrelid AND adnum=attnum AND atthasdef='t' AND lower(relname)='$table' UNION SELECT attnum,attname , typname , atttypmod-4 , attnotnull , atthasdef ,'' AS def FROM pg_attribute, pg_class, pg_type WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum > 0 AND atthasdef='f' AND lower(relname)='$table' order by attnum");
    foreach ($rows as $r) {
        if (preg_match('/nextval\(\'' . $table . '_.+_seq\'::regclass\).*/', $r['def'])) {
            $createsql .= " \"" . $r['attname'] . "\" serial";
            $serial_field = $r['attname'];
        } else {
            $createsql .= " \"" . $r['attname'] . "\" " . $r['typname'];
            if ($r['typname'] == "varchar") {
                $createsql .= "(" . $r['?column?'] . ")";
            }
            if ($r['attnotnull'] == "t") {
                $createsql .= " ";
            }
            if ($r['atthasdef'] == "t") {
                $createsql .= " DEFAULT " . $r['def'];
            }
        }
        $createsql .= ",";
    }
    $createsql   = rtrim($createsql, ",");
    $tabledump[] = $createsql . ");";
    $rows        = db_arrays("SELECT pg_index.indisprimary,pg_catalog.pg_get_indexdef(pg_index.indexrelid) FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index AS pg_index WHERE c.relname = '$table' AND c.oid = pg_index.indrelid AND pg_index.indexrelid = c2.oid AND pg_index.indisprimary");
    foreach ($rows as $r) {
        $t           = str_replace("CREATE UNIQUE INDEX", "", $r['pg_get_indexdef']);
        $t           = str_replace("USING btree", "|", $t);
        $t           = str_replace("ON", "|", $t);
        $Temparray   = explode("|", $t);
        $tabledump[] = "ALTER TABLE ONLY \"" . '%--%' . substr(trim($Temparray[1]), strlen(DB_LEFT)) . "\" ADD CONSTRAINT " . '%--%' . substr(trim($Temparray[0]), strlen(DB_LEFT)) . " PRIMARY KEY " . trim($Temparray[2]) . ";";
    }
    $rows = db_arrays("SELECT * FROM $table");
    foreach ($rows as $r) {
        foreach ($r as $key => $val) {
            $r[$key] = db_escape($val);
        }
        $sql = "INSERT INTO \"$bk_table\" VALUES ('";
        $sql .= implode("','", $r);
        $sql .= "')" . ($serial_field ? ' RETURNING ' . $serial_field : '') . ";";
        $tabledump[] = $sql;
    }
    $tabledump[] = "SELECT setval('" . $bk_table . "_" . $serial_field . "_seq', (SELECT MAX(" . $serial_field . ") FROM " . $bk_table . ")+1);";
    return $tabledump;
}
$tablelist = $_POST['tablelist'];
$data      = array();
foreach ($tablelist as $table) {
    $data = array_merge($data, sql2data($table));
}
$bak_name      = 'pgsql_bakup_' . date('YmdHis') . '-' . SR_VERSION . '.sql';
$db_backup_dir = SITE_HOME . 'inc/pgsql_backup';
if (!file_exists($db_backup_dir)) {
    mkdir($db_backup_dir);
}
file_put_contents($db_backup_dir . '/' . $bak_name, '<?php return ' . var_export($data, true) . ';?>');
