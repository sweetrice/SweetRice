<?php
/**
 * Htaccess management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 switch($mode){
	case 'save':
		ob_start();
		phpinfo(INFO_MODULES);
		$str = ob_get_contents();
		ob_end_clean();
		if('apache2handler' == php_sapi_name() && strpos($str,'mod_rewrite')!==false){
			$support_htaccess = true;
		}
		if(!isset($support_htaccess)){
			output_json(array('status'=>0,'status_code'=>_t('Server does not supports .Htaccess.')));
		}
		$contents = $_POST['content'];
		file_put_contents('../inc/htaccess.txt',$contents);
		$htaccess = file_get_contents('../inc/htaccess.txt');
		$htaccess = str_replace('%--%',str_replace('//','/',dirname(str_replace('/'.DASHBOARD_DIR,'',$_SERVER['PHP_SELF'])).'/'),$htaccess);
		file_put_contents('../.htaccess',$htaccess);
		output_json(array('status'=>1));
	break;
	default:
		$top_word = _t('Edit .htaccess');
		$inc = 'htaccess.php';
 }
?>