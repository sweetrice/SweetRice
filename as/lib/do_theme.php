<?php
/**
 * Theme management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	if($global_setting['theme']){
		$theme_dir = SITE_HOME.'_themes/'.$global_setting['theme'].'/';
	}else{
		$theme_dir = SITE_HOME.'_themes/default/';
	}
	$themes = get_AllTemplate($theme_dir);
	$mode = $_GET['mode'];
	switch($mode){
		case 'change_theme':
			$theme = $_POST['theme'];
			if($theme && is_dir(SITE_HOME.'_themes/'.$theme)){
				$_global_setting = getOption('global_setting');
				$_global_setting = unserialize(clean_quotes($_global_setting['content']));
				$_global_setting['date'] = time();
				$_global_setting['theme'] = $theme;
				setOption('global_setting',serialize($_global_setting));
				output_json(array('status'=>1,'status_code'=>_t('Theme has been change successfully')));
			}
			output_json(array('status'=>1,'status_code'=>_t('No theme seleced or theme does not exists')));
		break;
		case 'delete':
			$theme = $_POST['theme'];
			if(!$theme){
				output_json(array('status'=>0,'status_code'=>_t('No theme selected')));
			}
			if($global_setting['theme'] == $theme || (!$global_setting['theme'] && $theme == 'default')){
				output_json(array('status'=>0,'status_code'=>$app_name._t(' is using,please change system theme before delete.')));
			}
			if(is_dir(SITE_HOME.'_themes/'.$theme)){
				un_(SITE_HOME.'_themes/'.$theme,true);
			}
			output_json(array('status'=>1,'status_code'=>$theme._t(' has been delete successfully')));
		break;
		case 'add_theme':
			if(preg_match('/^(ht|f)tps?:\/\/.+\/.+\.zip$/',$_POST['theme_url'])){
				$tmp = explode('/',$_POST['theme_url']);
				$data = get_data_from_url($_POST['theme_url']);
				if($data){
					file_put_contents(SITE_HOME.ATTACHMENT_DIR.end($tmp),$data);
					$theme_name = basename(end($tmp),'.zip');
					mkdir(SITE_HOME.'_themes/'.$theme_name);
					extractZIP(SITE_HOME.ATTACHMENT_DIR.end($tmp),SITE_HOME.'_themes/'.$theme_name.'/');
					unlink(SITE_HOME.ATTACHMENT_DIR.end($tmp));
				}
			}
			if($_FILES['theme_file']['name'] && $_FILES['theme_file']['error'] == 0 ){
				upload_($_FILES['theme_file'],SITE_HOME.ATTACHMENT_DIR,$_FILES['theme_file']['name'],null);
				$app_name = basename($_FILES['theme_file']['name'],'.zip');
				mkdir(SITE_HOME.'_themes/'.$app_name);
				extractZIP(SITE_HOME.ATTACHMENT_DIR.$_FILES['theme_file']['name'],SITE_HOME.'_themes/'.$app_name.'/');
				unlink(SITE_HOME.ATTACHMENT_DIR.$_FILES['theme_file']['name']);
			}
			_goto('./?type=theme');
		break;
		case 'save':
			$page = $_GET['page'];
			if(trim($themes[$page]) && $_POST['contents']){
				$contents = clean_quotes($_POST['contents']);
				file_put_contents(SITE_HOME.$themes[$page],$contents);
				$data = getOption($themes[$page].'.bak');
				$data = unserialize($data['content']);
				$data[time()] = $_POST['contents'];
				setOption($themes[$page].'.bak',serialize($data));
			}
			$top_word = _t('Modify Theme');;
			$inc = 'theme.php';
		break;
		case 'clean_backup':
			$tb = $_POST['tb'];
			$page = $_GET['page'];
			$data = getOption($themes[$page].'.bak');
			if($data['content']){
				$data = unserialize($data['content']);
			}
			foreach($data as $key=>$val){
				if(!in_array($key,$tb)){
					$bak_list[$key] = $val;
				}
			}
			if(count($bak_list)){
				setOption($themes[$page].'.bak',serialize($bak_list));
			}else{
				delOption($themes[$page].'.bak');
			}
			_goto($_SERVER['HTTP_REFERER']);
		break;
		case 'add':
			$theme_type = $_POST['theme_type'];
			$name = $_POST['name'];
			$name = preg_replace('/[^0-9A-Za-z\-_]/','',$name);
			if(!$name){
				_goto($_SERVER['HTTP_REFERER']);
			}
			$name_template = $name.' page template';
			if(!file_exists($theme_dir.$name.'.php') && !$themes[$name_template]){
				switch($theme_type){
					case 'category':
						file_put_contents($theme_dir.$name.'.php','<?php'."\n".'/**'."\n".' * Category Template:'.$name_template.'.'."\n".' *'."\n".' * @package SweetRice'."\n".' * @Custom template'."\n".' * @since '.SR_VERSION."\n".' */'."\n".'	defined(\'VALID_INCLUDE\') or die();'."\n".'if(file_exists(THEME_DIR.$page_theme[\'head\'])){'."\n".'		include(THEME_DIR.$page_theme[\'head\']);'."\n".'	}'."\n".'?>'."\n".'<?php'."\n".'	if(file_exists(THEME_DIR.$page_theme[\'foot\'])){'."\n".'		include(THEME_DIR.$page_theme[\'foot\']);'."\n".'	}'."\n".'?>');
					break;
					case 'entry':
						file_put_contents($theme_dir.$name.'.php','<?php'."\n".'/**'."\n".' * Entry Template:'.$name_template.'.'."\n".' *'."\n".' * @package SweetRice'."\n".' * @Custom template'."\n".' * @since '.SR_VERSION."\n".' */'."\n".'	defined(\'VALID_INCLUDE\') or die();'."\n".'if(file_exists(THEME_DIR.$page_theme[\'head\'])){'."\n".'		include(THEME_DIR.$page_theme[\'head\']);'."\n".'	}'."\n".'?>'."\n".'<?php'."\n".'	if(file_exists(THEME_DIR.$page_theme[\'foot\'])){'."\n".'		include(THEME_DIR.$page_theme[\'foot\']);'."\n".'	}'."\n".'?>');
					break;
				}
				_goto('./?type=theme&page='.$name_template);
			}else{
				_goto($_SERVER['HTTP_REFERER']);
			}
		break;
		case 'delete':
			$page = $_GET['page'];
			if($themes[$page] && file_exists(SITE_HOME.$themes[$page])){
				unlink(SITE_HOME.$themes[$page]);
			}
			_goto('./?type=theme');
		break;
		default:
			$page = $_GET['page'];
			$top_word = _t('Modify Theme');;
			$inc = 'theme.php';
 }
 ?>