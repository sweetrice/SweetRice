<?php
/**
 * Sites management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 if($mode == 'save'){
	$conn = $db = null;
	$status = initSiteDB();
	if($status['error_db'] || $status['message']){
		output_json(array('status'=>0,'status_code'=>$status['error_db'].' '.$status['message']));
	}else{
		output_json(array('status'=>1,'data'=>$_POST));
	}
 }elseif($mode == 'delete'){
	$host = $_POST['host'];
	$no = $_POST['no'];
	if($host && rmSite($host)){
		output_json(array('status'=>'1','id'=>$host,'no'=>$no,'type'=>_t('Sites'),'status_code'=>_t('Successfully')));
	}else{
		output_json(array('status'=>'0','id'=>$host,'no'=>$no,'type'=>_t('Sites'),'status_code'=>_t('Failed')));
	}
 }elseif($mode == 'insert'){
	$themes = getThemeTypes();
	$top_word = _t('Site Management');
	$inc = 'site_modify.php';
 }else{
	$site_list = siteList();
	$top_word = _t('Site List');
	$inc = 'site_list.php';
 }
 ?>