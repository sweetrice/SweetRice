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
		$create = mysql_fetch_row(mysql_query("SHOW CREATE TABLE `$table`"));
		$tabledump[] = str_replace($table,$bk_table,$create[1]).";";
		$rows = mysql_query("SELECT * FROM `$table`");
		$numfields = mysql_num_fields($rows);
		while ($row = mysql_fetch_row($rows)){
		  $comma = "";
		  $tmp = "INSERT INTO `$bk_table` VALUES(";
		  for($i = 0; $i < $numfields; $i++){
		   $tmp .= $comma."'".mysql_real_escape_string($row[$i])."'";
		   $comma = ",";
		  }
		  $tmp .= ");";
			$tabledump[] = $tmp;
		}
		return $tabledump;
	}
	$tablelist = $_POST["tablelist"];
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