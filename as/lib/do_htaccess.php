<?php
/**
 * Htaccess management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode=='save'){
		ob_start();
		phpinfo(INFO_MODULES);
		$str = ob_get_contents();
		ob_end_clean();
		if('apache2handler' == php_sapi_name() && strpos($str,'mod_rewrite')!==false){
			$support_htaccess = true;
		}
		if(!$support_htaccess){
			alert('Server does not supports .Htaccess.','./?type=htaccess');
		}
		$contents = $_POST["content"];
		file_put_contents('../inc/htaccess.txt',$contents);
		$htaccess = file_get_contents('../inc/htaccess.txt');
		$htaccess = str_replace('%--%',str_replace('//','/',dirname(str_replace('/'.DASHBOARD_DIR,'',$_SERVER["PHP_SELF"])).'/'),$htaccess);
		file_put_contents('../.htaccess',$htaccess);
		_goto('./?type=htaccess');
 }else{
	$top_word = 'Edit .htaccess';
	$inc = 'htaccess.php';
 }
?>