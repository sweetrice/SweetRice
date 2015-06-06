<?php
/**
 * App form management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
	defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
	switch($mode){
		case 'delete':
			$id = intval($_GET["id"]);
			if($id > 0){
				db_query("DELETE FROM `".ADB."_app_form` WHERE `id` = '$id'");	
			}
			_goto($_SERVER["HTTP_REFERER"]);
		break;
		case 'bulk':
			$plist = $_POST["plist"];
			foreach($plist as $val){
				$val = intval($val);
				if($val>0){
					$ids[] = $val;
				}
			}
			if(count($ids)>0){
				$ids = implode(',',$ids);
				db_query("DELETE FROM `".ADB."_app_form` WHERE `id` IN ($ids)");
			}
			_goto($_SERVER["HTTP_REFERER"]);
		break;
		case 'insert':
			if($_POST['name']){
				$id = intval($_POST['id']);
				foreach($_POST['fields'] as $key=>$val){
					$fields[] = array('type'=>$_POST['types'][$key],'name'=>$val,'option'=>$_POST['option'][$key],'select_multiple'=>$_POST['select_multiple'][$key],'tip'=>$_POST['tips'][$key],'required'=>$_POST['required'][$key]);
				}
				db_insert(ADB.'_app_form',array('id',$id?$id:null),array('name','fields','method','action','captcha','template'),array($_POST['name'],serialize($fields),$_POST['method'],$_POST['action'],intval($_POST['captcha']),$_POST['template']));
				_goto(pluginDashboardUrl(THIS_APP,array('app_mode'=>'form')));
			}
			$id = intval($_GET["id"]);
			if($id > 0){
				$row = db_array("SELECT * FROM `".ADB."_app_form` WHERE `id` = '$id'");
				$fields = unserialize($row['fields']);
			}
			if($global_setting['theme']){
				$template = get_template(SITE_HOME.'_themes/'.$global_setting['theme'].'/','Entry');
			}else{
				$template = get_template(SITE_HOME.'_themes/default/','Entry');
			}
			$app_inc = 'form_insert.php';
		break;
		default:
			$search = $_GET['search'];
			$where = " 1=1 ";
			if($search){
				$where .= " `name` LIKE '%$search%'";
				$search_url = '&search='.$search;
			}
			$data = db_fetch(array('table'=>ADB.'_app_form',
				'field' => '*',
				'where' => $where,
				'pager' => array('p_link'=>pluginDashboardUrl(THIS_APP,array('app_mode'=>'form')).$search_url.'&','page_limit'=>intval($_COOKIE['page_limit'])?intval($_COOKIE['page_limit']):10,'pager_function'=>'_pager'),
				'debug' => true
			));
			$app_inc = 'form_list.php';
	}
?>