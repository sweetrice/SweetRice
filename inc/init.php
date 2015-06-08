<?php
/**
 * Initialize SweetRice.
 *
 * @package SweetRice
 * @since 1.2.5
 */
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT ^ E_ERROR);
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
	register_shutdown_function('error_report');
	set_error_handler('sweetrice_debug',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT ^ E_ERROR);
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
					$db = sqlite_dbhandle($dbname);
				break;
				case 'pgsql':
					$conn = pg_connect('host='.$db_url.' port='.$db_port.' dbname='.$db_name.' user='.$db_username.' password='.$db_passwd);
				break;
				case 'mysql':
					$conn = mysql_connect($db_url.':'.$db_port,$db_username,$db_passwd);
					mysql_select_db($db_name,$conn);
				break;
			}	
		}
		define('INSTALLED',true);
		$global_setting = getOption('global_setting');
		define('SETTING_UPDATE',$global_setting['date']);
		$global_setting = unserialize(clean_quotes($global_setting['content']));
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
		if($global_setting['timeZone']){
			date_default_timezone_set($global_setting['timeZone']);
		}
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
	$_GET = do_data($_GET);
?>