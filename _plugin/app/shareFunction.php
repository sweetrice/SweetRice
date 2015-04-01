<?php
/**
 * App plugin shareFunction for SweetRice.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	define('THIS_APP','App');
	if(!defined('APP_DIR')){
		define('APP_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	}
	define('APP_HOME',str_replace(SITE_HOME,SITE_URL,APP_DIR));
	define('ADB',DB_LEFT_PLUGIN);
	if(defined('DASHABOARD')){
		$lang = $global_setting['lang'];
	}else{
		$lang = themeLang().'.php';
	}
	if($lang && file_exists(APP_DIR.'lang/'.$lang)){
		init_lang(APP_DIR.'lang/'.$lang);
	}else{
		init_lang(APP_DIR.'lang/en-us.php');
	}

	class App
	{
		function app_links(){
			return array(
				'home'=>array('action'=>'pluginHook','plugin'=>THIS_APP)
			);
		}

		function app_url($page=false){
			if(!$page){
				$page = $_REQUEST['page'];
			}
			$app_links = $this->app_links();
			if(!$app_links[$page]){return ;}
			return BASE_URL.pluginHookUrl(THIS_APP,$app_links[$page]);
		}

		function app_navs(){
			return array(
				array('app_mode'=>'database','name'=>_t('Database')),
				array('app_mode'=>'menu','name'=>_t('Menu'))
			);
		}

		function app_actions(){
			$actions = array();
			foreach($this->app_navs() as $val){
				$actions[$val['app_mode']] = array();
			}
			return array_merge($actions,array(
				'links'=>array()
			));
		}

		function app_nav(){
			$_menu_data = db_arrays("SELECT * FROM `".ADB."_app_menus`");
			foreach($_menu_data as $val){
				$menu_data[$val['id']] = $val;
			}
			$app_menus = subMenus();
			$output = '<link href="'.APP_HOME.'css/app.css" rel="stylesheet" type="text/css" media="screen" /><script type="text/javascript" src="'.APP_HOME.'js/app.js"></script><div class="app_nav">';

			$nav_order = 0;
			foreach($app_menus as $nav){
				$nav_order += 1;
				$output .= '<a href="'.$menu_data[$nav['id']]['link_url'].'" '.($_SERVER['REQUEST_URI'] == $menu_data[$nav['id']]?'title="'.$menu_data[$nav['id']]['link_text'].'"':'').' navorder="'.$nav_order.'" parentid="'.$menu_data[$nav['id']]['parent_id'].'" menuid="'.$nav['id'].'" level="'.$nav['level'].'">'.$menu_data[$nav['id']]['link_text'].'</a>';
			}
			$output .= '<div class="nav_line"><span class="curr_line"></span></div><div class="curr_child"></div></div>';
			return $output;
		}
		
	}
	
	function subMenus($sql='',$id=0,$level=0){
		$subMenus = array();
		$row = db_arrays("SELECT `id` FROM `".ADB."_app_menus` WHERE `parent_id` = '$id' ".$sql." ORDER BY `order` ASC ");
		foreach($row as $val){
			$val['level'] = $level;
			$subMenus[] = $val;
			$subMenus = array_merge ($subMenus,subMenus($sql,$val['id'],$level+1));
		}
		return $subMenus;
	}

	$myApp = new App();
?>