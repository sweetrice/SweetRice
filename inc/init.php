<?php
/**
 * Initialize SweetRice.
 *
 * @package SweetRice
 * @since 1.2.5
 */
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
	session_name('sweetrice');
	session_start();
	define('VALID_INCLUDE',true);
	define('INCLUDE_DIR',dirname(__FILE__).'/');
	if(file_exists(INCLUDE_DIR.'setting.php')){
		include(INCLUDE_DIR.'setting.php');
	}
	if(!$dashboard_dir){
		$dashboard_dir = 'as';
	}
	define('DASHBOARD_DIR',$dashboard_dir);
	$http = $_SERVER['SERVER_PORT'] == 443?'https://':'http://';
	define('BASE_URL',str_replace('\\','',$http.$_SERVER['HTTP_HOST'].str_replace('//','/',dirname(str_replace('/'.DASHBOARD_DIR,'',$_SERVER['PHP_SELF'])).'/')));
	define('BASE_DIR',str_replace('\\','',str_replace('//','/',dirname(str_replace('/'.DASHBOARD_DIR,'',$_SERVER['PHP_SELF'])).'/')));
	unset($http);
	define('ROOT_DIR',substr(INCLUDE_DIR,0,-4));
	if(is_dir(ROOT_DIR.'_sites/'.$_SERVER['HTTP_HOST'])){
		define('SITE_HOME',ROOT_DIR.'_sites/'.$_SERVER['HTTP_HOST'].'/');
		define('SITE_URL',BASE_URL.'_sites/'.$_SERVER['HTTP_HOST'].'/');
	}else{
		define('SITE_HOME',ROOT_DIR);
		define('SITE_URL',BASE_URL);
	}
	include(INCLUDE_DIR.'function.php');
	if (file_exists(INCLUDE_DIR.'function_custom.php')) {
		include(INCLUDE_DIR.'function_custom.php');
	}
	register_shutdown_function('error_report');
	set_error_handler('sweetrice_debug',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
	if (function_exists('mysql_connect')) {
		define('MYSQL_LIB','mysql');
	}else{
		define('MYSQL_LIB','mysqli');
	}
	if(file_exists(INCLUDE_DIR.'install.lock.php')){
		if(file_exists(SITE_HOME.'inc/db.php')){
			include(SITE_HOME.'inc/db.php');
			define('DB_LEFT',$db_left);
			define('DB_LEFT_PLUGIN',DB_LEFT.'_plugin');
			define('DATABASE_TYPE',$database_type);
			$db_left_plugin = DB_LEFT_PLUGIN;
			switch(DATABASE_TYPE){
				case 'sqlite':
					$dbname = SITE_HOME.'inc/'.$db_name.'.db';
					if (is_file($dbname)) {
						$GLOBALS['db_lib'] = new sqlite_lib(array('name'=>$dbname,'sqlite_driver'=>$sqlite_driver));
					}
				break;
				case 'pgsql':
					$GLOBALS['db_lib'] = new pgsql_lib(array('url'=>$db_url,'port'=>$db_port,'username'=>$db_username,'passwd'=>$db_passwd,'name'=>$db_name));
				break;
				case 'mysql':
					$GLOBALS['db_lib'] = new mysql_lib(array('url'=>$db_url,'port'=>$db_port,'username'=>$db_username,'passwd'=>$db_passwd,'name'=>$db_name));
				break;
			}
			if (!$GLOBALS['db_lib']->link) {
				header('HTTP/1.1 404 Page Not Found');
				die('db error');
			}
		}
		define('INSTALLED',true);
		$global_setting = getOption('global_setting');
		$global_setting = unserialize(clean_quotes($global_setting['content']));
		define('SETTING_UPDATE',$global_setting['date']);
		$global_setting['nums_setting'] = initNumsSetting($global_setting['nums_setting']);
		define('URL_REWRITE',$global_setting['url_rewrite']);
		if(defined('DASHABOARD')){
			define('CACHE_SETTING',$global_setting['cache']);
			$global_setting['cache'] = false;
		}else{
			$links = getOption('links');
			define('LINKS_UPDATE',$links['date']);
		}
		$categories_data = getOption('categories');
		define('CATEGORIES_UPDATE',$categories_data['date']);
		if($categories_data['content']){
			$categories_data = unserialize($categories_data['content']);
			foreach($categories_data as $val){
				if($val['id']){
					$categories[$val['id']] = $val;
					$categoriesByLink[$val['link']] = $val['id'];			
				}
			}
		}
		if(!$global_setting['timeZone']){
			$global_setting['timeZone'] = 'America/Los_Angeles';
		}
		date_default_timezone_set($global_setting['timeZone']);
		$permalinks = initPermalinks();
		if(SITE_HOME != ROOT_DIR && file_exists(SITE_HOME.'inc/site_config.php')){
			include(SITE_HOME.'inc/site_config.php');
			if($attachment_dir){
				if(!is_dir(SITE_HOME.$attachment_dir)){
					mkdir(SITE_HOME.$attachment_dir);
				}
				define('ATTACHMENT_DIR','_sites/'.$_SERVER['HTTP_HOST'].'/'.$attachment_dir.'/');
			}
		}else{
			define('ATTACHMENT_DIR','attachment/');
		}
		define('LANG_DIR',INCLUDE_DIR.'lang/');
		define('SR_VERSION',file_get_contents(INCLUDE_DIR.'lastest.txt'));
	}
	$_POST = do_data($_POST);
	$_GET = do_data($_GET,'strict');
?>