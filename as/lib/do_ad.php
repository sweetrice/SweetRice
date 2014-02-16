<?php
/**
 * AD management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode == 'save'){
	$adk = $_POST["adk"];
	$adv = clean_quotes($_POST["adv"]);
	if($adk&&$adv){
		file_put_contents(SITE_HOME.'inc/ads/'.$adk.'.php',$adv);
	}
	_goto('./?type=ad');
 }elseif($mode=='delete'){
	$ads_dir = SITE_HOME.'inc/ads/';
	$adk = $_POST["adk"];
	$no = $_POST["no"];
	unlink($ads_dir.$adk.'.php');
	output_json(array('status'=>'1','id'=>$adk,'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(AD,$adk))));
 }else{
	$ads = array();
	$ads_dir = SITE_HOME.'inc/ads/';
	if(!file_exists($ads_dir)){
		mkdir($ads_dir);
	}
	$adk = $_GET["adk"];
	if(file_exists($ads_dir.$adk.'.php')){
		$adv = file_get_contents($ads_dir.$adk.'.php');
	}
	$d = dir($ads_dir);
		while (false !== ($entry = $d->read())) {
		 if($entry!='.'&&$entry!='..'){
			$ads[] = str_replace('.php','',$entry);
		 }		
	}
	$top_word = AD.' '.ADMIN;
	$inc = 'ad.php';
 }
 ?>