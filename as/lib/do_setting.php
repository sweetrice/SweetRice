<?php
/**
 * Site management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 if($mode == 'save'){
	$dashboard_dirs = preg_replace('/[^\w_\-]+/','',$_POST['dashboard_dirs']);
	if($dashboard_dirs != DASHBOARD_DIR && !file_exists(ROOT_DIR.$dashboard_dirs)){
		rename(ROOT_DIR.DASHBOARD_DIR,ROOT_DIR.$dashboard_dirs);
	}else{
		$dashboard_dirs = false;
	}
	$tmp = '<?php'."\n";
	$tmp .= '$dashboard_dir = \''.($dashboard_dirs?$dashboard_dirs:DASHBOARD_DIR).'\';'."\n";
	$tmp .= '?>';
	file_put_contents(ROOT_DIR.'inc/setting.php',$tmp);
	if($_POST['url_rewrite']){
		 ob_start();
		 phpinfo(INFO_MODULES);
		 $str = ob_get_contents();
		 ob_end_clean();
		 if('apache2handler' == php_sapi_name() && strpos($str,'mod_rewrite')){
			$htaccess = file_get_contents('../inc/htaccess.txt');
			$htaccess = str_replace('%--%',str_replace('//','/',dirname(str_replace('/'.DASHBOARD_DIR,'',$_SERVER['PHP_SELF'])).'/'),$htaccess);
			file_put_contents('../.htaccess',$htaccess);
		 }
	}else{
		if(file_exists('../.htaccess')){
			unlink('../.htaccess');
		}
	}
	$logo = upload_($_FILES['logo'],'../'.ATTACHMENT_DIR,$_FILES['logo']['name'],$_POST['old_logo']);
	$passwd = $_POST['passwd']?md5($_POST['passwd']):$_POST['old_passwd'];
	foreach($_POST['nums_setting'] as $key=>$val){
		$nums_setting[$key] = intval($val);
	}
	$setting = serialize(array('name'=>escape_string($_POST['name']) , 'author'=>escape_string($_POST['author']) ,'title'=>escape_string($_POST['title']) , 'keywords'=>escape_string($_POST['keyword']) , 'description'=>escape_string($_POST['description']) ,
	'admin_priority' => intval($_POST['admin_priority']),
	'admin'=>$_POST['admin'] , 
	'passwd'=>$passwd,
	'close'=>intval($_POST['close']) ,
	'close_tip'=>toggle_attachment($_POST['close_tip']),
	'cache'=>intval($_POST['cache']),
	'cache_expired'=>intval($_POST['cache_expired']),	
	'header_304'=>intval($_POST['header_304']),
	'user_track'=>intval($_POST['user_track']),
	'url_rewrite'=>intval($_POST['url_rewrite']),
	'logo'=>$logo,
	'theme'=>$_POST['theme'],
	'lang'=>$_POST['lang'],
	'theme_lang'=>$_POST['theme_lang'],
	'admin_email'=>$_POST['admin_email'],
	'last_setting'=>time(),
	'timeZone'=>$_POST['timeZone'],
	'nums_setting'=>$nums_setting
	));
	setOption('global_setting',$setting);
	save_custom_field($_POST,'setting',1);
	_goto(BASE_URL.($dashboard_dirs?$dashboard_dirs:DASHBOARD_DIR).'/?type=setting');
 }else{
	define('UPLOAD_MAX_FILESIZE',ini_get('upload_max_filesize'));
	$themes = getThemeTypes();
	$s_theme[$global_setting['theme']] = 'selected';
	$lang = getLangTypes(INCLUDE_DIR.'lang/');
	$lang_types = getLangTypes();
	$s_lang[$global_setting['theme_lang']] = 'selected';
	$dashboard_lang[$global_setting['lang']] = 'selected';
	$top_word = _t('General Setting');
	$cf_rows = db_arrays("SELECT * FROM `".DB_LEFT."_item_data` WHERE `item_id` = 1 AND `item_type` = 'setting'");
	$inc = 'site.php';
 }
 ?>