<?php
/**
 * Dashboard center.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
	define('DASHABOARD',true);
	include('../inc/init.php');
	include('lib/function.php');
	$type = $_GET['type'];
	if ($type == 'form_token') {
		if ($_POST['_tkv_']) {
			output_json(array('status' => 1,'form_token'=>form_token('','','return')));
		}	
	}
	check_form_token();
	form_token();
	if(!defined('INSTALLED')){
		include('lib/install.php');
		exit();
	}
	if(!$global_setting['lang']){
		$global_setting['lang'] = dashboardLang();
	}
	$lang_data = array();
	init_lang(INCLUDE_DIR.'lang/'.$global_setting['lang']);
	$inc = null;
	$plugin_page = array();
	switch($type){
		case 'signin':
			dashboardSignin();
		break;
		case 'signout':
			dashboard_signout();
			exit();
		break;
		case 'password':
			include('lib/do_password.php');
		break;
		default:
			if(!dashboard_signin() || !getOption('global_setting')){
				include('lib/auth_form.php');	
				exit();
			}
			$actions = dashboard_actions();
			if($actions[$type]['file'] && file_exists('lib/'.$actions[$type]['file'])){
				switch($type){
					case 'plugin':
						if(!dashboard_role('dashboard/'.$_GET['plugin'])){
							alert('page not found',SITE_URL.DASHBOARD_DIR);
						}
					break;
					default:
						if(!dashboard_role($type,$actions[$type]['mustBase'])){
							alert('page not found',SITE_URL.DASHBOARD_DIR);
						}
				}
				include('lib/'.$actions[$type]['file']);
			}else{
				include('lib/do_main.php');
			}
	}
	include('lib/head.php');
	$bgnav = array();
	$bgnav[$type] = 'class="currency_nav"';
?>
<div id="div_center">
<div id="admin_left"><?php dashboard_menu($type,$bgnav);?></div>
<div id="admin_right">
<?php 
if($inc && file_exists('lib/'.$inc)){
	include('lib/'.$inc);
}elseif(is_array($plugin_page) && count($plugin_page)){
	foreach($plugin_page as $val){
		if($val && file_exists($val)){
			include($val);
		}
	}
}
?>
</div>
<div class="div_clear"></div>
</div>
<?php include('lib/foot.php');?>