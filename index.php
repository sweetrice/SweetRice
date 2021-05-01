<?php
	include('inc/init.php');
	defined('INSTALLED') or _goto(DASHBOARD_DIR.'/');
	$url_data = initUrl();
	if(is_array($url_data)){
		foreach($url_data as $key=>$val){
			$_GET[$key] = $val;
		}
	}
	$lang_data = array();
	$lang = themeLang();
	if(!$lang || !file_exists('inc/lang/'.$lang.'.php')){
		$lang = 'en-us.php';
	}else{
		$lang .= '.php';
	}
	init_lang(INCLUDE_DIR.'lang/'.$lang);
	$lang = basename($lang,'.php');
	if(defined('THEME_DIR') && file_exists(THEME_DIR.'lang/'.$lang)){
		init_lang(THEME_DIR.'lang/'.$lang);
	}
	$theme = theme();
	define('THEME_DIR',SITE_HOME.($theme?'_themes/'.$theme.'/':'_themes/default/'));
	define('THEME_URL',SITE_URL.($theme?'_themes/'.$theme.'/':'_themes/default/'));
	$action = $_GET['action'];
	$page_theme = get_page_themes();
	if($global_setting['close']){
		include($page_theme['close']?THEME_DIR.$page_theme['close']:INCLUDE_DIR.'close_tip.php');
		exit();
	}
	if($page_theme['template_helper'] && is_file(THEME_DIR.$page_theme['template_helper'])){
		include(THEME_DIR.$page_theme['template_helper']);		
	}
	$inc = $last_modify = null;
	$actions = array(
		'attachment'=>array('type'=>1),
		'lang'=>array('type'=>1),
		'theme'=>array('type'=>1),
		'comment'=>array('type'=>1),
		'rssfeed'=>array('type'=>1),
		'tags'=>array('type'=>1),
		'sitemap'=>array('type'=>1),
		'category'=>array('type'=>1),
		'entry'=>array('type'=>1),
		'ads'=>array('type'=>2),
		'pluginHook'=>array('type'=>3)
	);
	switch($actions[$action]['type']){
		case 1:
			if(file_exists(INCLUDE_DIR.'do_'.$action.'.php')){
				include(INCLUDE_DIR.'do_'.$action.'.php');
			}
		break;
		case 2:
			if(file_exists(INCLUDE_DIR.'do_'.$action.'.php')){
				include(INCLUDE_DIR.'do_'.$action.'.php');
			}
			exit();
		break;
		case 3:
			$plugin = $_GET['plugin'];
			if(!$plugin){
				_404('plugin');
			}
			$plugin_inc = pluginHook($plugin);
			if($plugin_inc && file_exists($plugin_inc)){
				include($plugin_inc);				
			}else{
				_404('plugin');
			}
		break;
		default:
			if($action == 'Forbidden'){
				_403();
			}elseif($action && !preg_match('/(.*)\/$/',$url_data['url']) && $_GET['rtype'] == 'wop'){
				_301(BASE_URL.$url_data['url'].'/');
			}elseif($action){
				_404('home');
			}
			include(INCLUDE_DIR.'do_home.php');
	}
	if($global_setting['user_track'])	user_track();	
	if($global_setting['header_304'])	outputHeader($last_modify);
	header('Access-Control-Allow-Origin: *');
	if($inc && file_exists($inc)){
		_out();
		include($inc);
		_flush();
	}
?>