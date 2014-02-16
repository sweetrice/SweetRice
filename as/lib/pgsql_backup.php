<?php
/**
 * Database backup for PostgreSql.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	function sql2data($table){
		$tabledump = '';
		$bk_table = '%--%'.substr($table,strlen(DB_LEFT));
		$tabledump []= "DROP TABLE IF EXISTS \"$bk_table\" CASCADE;";
		$createsql .= "CREATE TABLE \"$bk_table\" (";
		$res2 = pg_query("SELECT attnum,attname , typname , atttypmod-4 , attnotnull ,atthasdef ,adsrc AS def FROM pg_attribute, pg_class, pg_type, pg_attrdef WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND pg_class.oid=adrelid AND adnum=attnum AND atthasdef='t' AND lower(relname)='$table' UNION SELECT attnum,attname , typname , atttypmod-4 , attnotnull , atthasdef ,'' AS def FROM pg_attribute, pg_class, pg_type WHERE pg_class.oid=attrelid AND pg_type.oid=atttypid AND attnum>0 AND atthasdef='f' AND lower(relname)='$table' order by attnum"); 
		while($r = pg_fetch_row($res2)){
			if(preg_match('/nextval\(\''.$table.'_.+_seq\'::regclass\).*/',$r[6])){
				$createsql .= " \"".$r[1]."\" serial" ;					
			}else{
				$createsql .= " \"".$r[1]."\" " . $r[2];
				if ($r[2]=="varchar"){
					$createsql .= "(".$r[3] .")";
				}
				if ($r[4]=="t"){
					$createsql .= " ";
				}
				if ($r[5]=="t"){
					$createsql .= " DEFAULT ".$r[6];
				}
			}
			$createsql .= ",";
		 }
		$createsql = rtrim($createsql, ","); 
		$tabledump[] = $createsql.");";
		$res3 = pg_query("SELECT * FROM $table");
		while($r = pg_fetch_row($res3)){
			foreach($r as $key=>$val){
				$r[$key] = pg_escape_string($val);
			}
			$sql = "INSERT INTO \"$bk_table\" VALUES ('";
			$sql .= implode("','",$r);
			$sql .= "');";
			$tabledump[] = $sql;
		}
		$res1 = pg_query("SELECT pg_index.indisprimary,			   pg_catalog.pg_get_indexdef(pg_index.indexrelid) FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index AS pg_index WHERE c.relname = '$table' AND c.oid = pg_index.indrelid AND pg_index.indexrelid = c2.oid AND pg_index.indisprimary");
		while($r = pg_fetch_row($res1)){
			$t = str_replace("CREATE UNIQUE INDEX", "", $r[1]);
			$t = str_replace("USING btree", "|", $t);
			// Next Line Can be improved!!!
			$t = str_replace("ON", "|", $t);
			$Temparray = explode("|", $t);
			$tabledump[] = "ALTER TABLE ONLY \"".'%--%'.substr(trim($Temparray[1]),strlen(DB_LEFT))."\" ADD CONSTRAINT " .'%--%'.substr(trim($Temparray[0]),strlen(DB_LEFT)). " PRIMARY KEY ".trim($Temparray[2]).";"; 
		}
		return $tabledump;
	}
	$tablelist = $_POST["tablelist"];
	$data = array();
	foreach($tablelist as $table){
		$data = array_merge($data,sql2data($table));
	}
	$bak_name = 'pgsql_bakup_'.date('YmdHis').'-'.SR_VERSION.'.sql';
	$db_backup_dir = SITE_HOME.'inc/pgsql_backup';
	if(!file_exists($db_backup_dir)){
		mkdir($db_backup_dir);
	}
	file_put_contents($db_backup_dir.'/'.$bak_name,'<?php return '.var_export($data,true).';?>');
?>