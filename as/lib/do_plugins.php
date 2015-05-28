<?php
/**
 * Plugin management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
	$plugin = $_GET['plugin'];
	switch($mode){
		case 'delete':
			$app_name = $_POST['app_name'];
			if(!$app_name){
				output_json(array('status'=>0,'status_code'=>_t('No plugin selected')));
			}
			if(isPluginInstall($app_name)){
				output_json(array('status'=>0,'status_code'=>$app_name._t(' is installed,please uninstall it before delete.')));
			}
			if(is_dir(SITE_HOME.'_plugin/'.$app_name)){
				un_(SITE_HOME.'_plugin/'.$app_name,true);
			}
			output_json(array('status'=>1,'status_code'=>$app_name._t(' has been delete successfully')));
		break;
		case 'add':
			if(preg_match('|^(https?|ftp)://.+?/.+\.zip$|',$_POST['app_url'])){
				$tmp = explode('/',$_POST['app_url']);
				$data = get_data_from_url($_POST['app_url']);
				if($data){
					file_put_contents(SITE_HOME.ATTACHMENT_DIR.end($tmp),$data);
					$app_name = basename(end($tmp),'.zip');
					mkdir(SITE_HOME.'_plugin/'.$app_name);
					extractZIP(SITE_HOME.ATTACHMENT_DIR.end($tmp),SITE_HOME.'_plugin/'.$app_name.'/');
					unlink(SITE_HOME.ATTACHMENT_DIR.end($tmp));
				}
			}
			if($_FILES['app_file']['name'] && $_FILES['app_file']['error'] == 0 ){
				upload_($_FILES['app_file'],SITE_HOME.ATTACHMENT_DIR,$_FILES['app_file']['name'],null);
				$app_name = basename($_FILES['app_file']['name'],'.zip');
				mkdir(SITE_HOME.'_plugin/'.$app_name);
				extractZIP(SITE_HOME.ATTACHMENT_DIR.$_FILES['app_file']['name'],SITE_HOME.'_plugin/'.$app_name.'/');
				unlink(SITE_HOME.ATTACHMENT_DIR.$_FILES['app_file']['name']);
			}
			_goto('./?type=plugins');
		break;
		case 'install':
			output_json(plugin_install($plugin));
		break;
		case 'deinstall':
			output_json(plugin_deinstall($plugin));
		break;
		default:
			$top_word = _t('Plugin Admin');
			$inc = 'plugin.php';
	}
 ?>