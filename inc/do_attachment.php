<?php
/**
 * Attachment Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
	defined('VALID_INCLUDE') or die();
		$id = intval($_GET["id"]);
		$row = db_array("SELECT `file_name` FROM `".DB_LEFT."_attachment` WHERE `id` = '$id' ");
		if(substr($row['file_name'],0,strlen(BASE_URL))==BASE_URL&&!file_exists(str_replace(BASE_URL,ROOT_DIR,$row["file_name"]))){
			_404('attachment');
		}
		db_query("UPDATE `".DB_LEFT."_attachment` SET `downloads` = `downloads`+1 WHERE `id` = '$id' ");
		header('location:'.$row['file_name']);
		exit();
	?>