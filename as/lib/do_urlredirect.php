<?php
/**
 * URL Redirect management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.2.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 if($mode == 'save'){
	$k = $_POST['k'];
	$t = $_POST['t'];
	$r = $_POST['r'];
	$redirectList = array();
	$parseList = array();
	foreach($k as $key=>$val){
		if($val&&$t[$key]){
			if($r[$key]==1){
				$redirectList[$val] = $t[$key];
			}else{
				$parseList[$val] = $t[$key];
			}
		}
	}
	setOption('redirectList',($redirectList?serialize($redirectList):''));
	setOption('parseList',($parseList?serialize($parseList):''));
	_goto('./?type=url_redirect');
 }else{
	$urls = array();
	$row = getOption('redirectList');
	if($row['content']){
		$redirectList = unserialize($row['content']);		
	}
	$row = getOption('parseList');
	if($row['content']){
		$parseList = unserialize($row['content']);
	}
	$top_word = _t('URL Redirect');
	$inc = 'url_redirect.php';
 }
 ?>