<?php
/**
 * Database backup for Mysql.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	function sql2data($table){
		$bk_table = '%--%'.substr($table,strlen(DB_LEFT));
		$tabledump[] = "DROP TABLE IF EXISTS `$bk_table`;";
		$create = $GLOBALS['mysql_lib']->fetch_row($GLOBALS['mysql_lib']->query("SHOW CREATE TABLE `$table`"));
		$tabledump[] = str_replace($table,$bk_table,$create[1]).";";
		$rows = $GLOBALS['mysql_lib']->query("SELECT * FROM `$table`");
		$numfields = $GLOBALS['mysql_lib']->num_fields($rows);
		while ($row = $GLOBALS['mysql_lib']->fetch_row($rows)){
		  $comma = "";
		  $tmp = "INSERT INTO `$bk_table` VALUES(";
		  for($i = 0; $i < $numfields; $i++){
		   $tmp .= $comma."'".$GLOBALS['mysql_lib']->real_escape_string($row[$i])."'";
		   $comma = ",";
		  }
		  $tmp .= ");";
			$tabledump[] = $tmp;
		}
		return $tabledump;
	}
	$tablelist = $_POST['tablelist'];
	$data = array();
	foreach($tablelist as $table){
		$data = array_merge($data,sql2data($table));
	}
	$db_backup_dir = SITE_HOME.'inc/mysql_backup';
	if(!file_exists($db_backup_dir)){
		mkdir($db_backup_dir);
	}
	$bak_name = 'mysql_bakup_'.date('YmdHis').'-'.SR_VERSION.'.sql';
	file_put_contents($db_backup_dir.'/'.$bak_name,'<?php return '.var_export($data,true).';?>');
?>