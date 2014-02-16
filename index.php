<?php
	include("inc/init.php");
	defined('INSTALLED') or _goto(DASHBOARD_DIR.'/');
	if($global_setting['close']){
		include(INCLUDE_DIR."close_tip.php");
		exit();
	}
	$_POST = do_data($_POST);	
	$_GET = do_data($_GET);
	$url_data = initUrl();
	if($url_data){
		foreach($url_data as $key=>$val){
			$_GET[$key] = $val;
		}
	}	
	$lang = themeLang();
	if($lang && file_exists('inc/lang/'.$lang.'.php')){
		include('inc/lang/'.$lang.'.php');		
	}else{
		$lang = 'en-us';
		include("inc/lang/en-us.php");
	}
	$theme = theme();
	define('THEME_DIR',SITE_HOME.($theme?'_themes/'.$theme.'/':'_themes/default/'));
	define('THEME_URL',SITE_URL.($theme?'_themes/'.$theme.'/':'_themes/default/'));
	$lang_types = getLangTypes();
	$s_lang[$lang] = 'selected';
	$theme_types = getThemeTypes();
	$s_theme[$theme] = 'selected';
	$page_theme = get_page_themes();
	$action = $_GET["action"];
	$inc = $last_modify = null;
	$actions = array('attachment'=>array('file'=>'do_attachment.php','type'=>1),
		'lang'=>array('file'=>'do_lang.php','type'=>1),
		'theme'=>array('file'=>'do_theme.php','type'=>1),
		'comment'=>array('file'=>'do_comment.php','type'=>1),
		'rssfeed'=>array('file'=>'do_rssfeed.php','type'=>1),
		'tags'=>array('file'=>'do_tags.php','type'=>1),
		'sitemap'=>array('file'=>'do_sitemap.php','type'=>1),
		'category'=>array('file'=>'do_category.php','type'=>1),
		'entry'=>array('file'=>'do_post.php','type'=>1),
		'ads'=>array('file'=>'do_ads.php','type'=>2),
		'pluginHook'=>array('file'=>null,'type'=>3)
	);
	switch($actions[$action]['type']){
		case 1:
			if(file_exists(INCLUDE_DIR.$actions[$action]['file'])){
				include(INCLUDE_DIR.$actions[$action]['file']);				
			}
		break;
		case 2:
			if(file_exists(INCLUDE_DIR.$actions[$action]['file'])){
				include(INCLUDE_DIR.$actions[$action]['file']);				
			}
			exit();
		break;
		case 3:
			$plugin = $_GET["plugin"];
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
			if($action=='Forbidden'){
				_403();
			}elseif($action&&!preg_match("/(.*)\/$/",$url_data['url'])&&$_GET["rtype"]=='wop'){
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