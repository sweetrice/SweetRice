<?php
/**
 * Sites management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode == 'save'){
	$conn = $db = null;
	initSiteDB();
	_goto('./?type=sites');
 }elseif($mode == 'delete'){
	$host = $_POST["host"];
	$no = $_POST["no"];
	if($host && rmSite($host)){
		output_json(array('status'=>'1','id'=>$host,'no'=>$no,'type'=>SITES,'status_code'=>SUCCESSFULLY));
	}else{
		output_json(array('status'=>'0','id'=>$host,'no'=>$no,'type'=>SITES,'status_code'=>FAILED));
	}
 }elseif($mode == 'insert'){
	$themes = getThemeTypes();
	$top_word = SITES_MANAGEMENT;
	$inc = 'site_modify.php';
 }else{
	$site_list = siteList();
	$top_word = SITE_LIST;
	$inc = 'site_list.php';
 }
 ?>