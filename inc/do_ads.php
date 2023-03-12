<?php
/**
 * Ads Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 1.2.1
 */
 	defined('VALID_INCLUDE') or die();
	$ads = array();
	$ads_dir = SITE_HOME.'inc/ads/';
	$d = dir($ads_dir);
		while (false !== ($entry = $d->read())) {
		 if($entry!='.'&&$entry!='..'){
			$ads[str_replace('.php','',$entry)] = $entry;
		 }		
	}
	$adname = $_GET['adname'];
	if(!$ads[$adname]) die();
	outputHeader(filemtime(SITE_HOME.'inc/ads/'.$ads[$adname]));
	ob_start();
	include(SITE_HOME.'inc/ads/'.$ads[$adname]);	
	$content = ob_get_contents();
	ob_end_clean();
	$content = str_replace(array('\'',"\n","\r"),array('\\\'','\\n','\\r'),$content);
	header('Content-Type: application/x-javascript; charset=UTF-8');
?>
<!--
	document.write('<?php echo $content;?>');
//-->