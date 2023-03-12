<?php
/**
 * Database backup for Sqlite.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	 function sql2data($table){
		$bk_table = '%--%'.substr($table,strlen(DB_LEFT));
		$tabledump = array();
		$tabledump[] = "DROP TABLE IF EXISTS `$bk_table`;";
		$create = db_array("select * from sqlite_master WHERE type = 'table' and name = '$table'");
		$tabledump[] = str_replace($table,$bk_table,$create['sql']).";";
		$field_list = array();
		$rows = db_arrays("SELECT * FROM `".$table."`");
		$fields = db_arrays("PRAGMA table_info(".$table.")");
		foreach($fields as $field){
			$field_list[] = $field['name'];
		}
		foreach($rows as $row){
			$comma = "";
			$tmp = "INSERT INTO `".$bk_table."` VALUES(";
			foreach($field_list as $fl){
				if(is_string($row[$fl])){
					$str = sqlite_escape_string($row[$fl]);
				}else{
					$str = $row[$fl];
				}	
				$tmp .= $comma."'".$str."'";
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
	$db_backup_dir = SITE_HOME.'inc/sqlite_backup';
	if(!file_exists($db_backup_dir)){
		mkdir($db_backup_dir);
	}
	$bak_name = 'sqlite_bakup_'.date('YmdHis').'-'.SR_VERSION.'.sql';
	file_put_contents($db_backup_dir.'/'.$bak_name,'<?php return '.var_export($data,true).';?>');
?>