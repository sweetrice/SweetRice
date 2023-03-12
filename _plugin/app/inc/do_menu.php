<?php
/**
 * App database management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
	switch($mode){
		case 'save':
			$parent_id = intval($_POST['parent_id']);
			$insert_ids = array(0);
			if($_POST['link_text']){
				foreach($_POST['link_text'] as $key=>$val){
					$insert_ids[] = db_insert(ADB.'_app_menus',array('id',intval($_POST['ids'][$key])),array('link_text','link_url','order','parent_id'),array($_POST['link_text'][$key],$_POST['link_url'][$key],$_POST['order'][$key],$parent_id));
				}
			}
			db_query("DELETE FROM `".ADB."_app_menus` WHERE `id` NOT IN(".implode(',',$insert_ids).") AND `parent_id` = '$parent_id'");
			_goto($_SERVER['HTTP_REFERER']);
		break;
		case 'sitemap':
			$hList = array();
			$hRow = getOption('hidden_from_sitemap');
			if($hRow['content']){
				$hList = unserialize($hRow['content']);
			}
			$lList = array();
			if(is_array($categories)){
				foreach($categories as $val){
					$lList[] = array('url'=>show_link_cat($val['link'],''),'link_body'=>$val['name'],'original_url'=>show_link_cat($val['link'],'',true));
				}
			}
			$pRows = db_arrays("SELECT * FROM `".DB_LEFT."_links` ORDER BY `url` ASC ");
			foreach($pRows as $val){
				$reqs = unserialize($val['request']);
				if($reqs){
					$original_url = '?';
					foreach($reqs as $k=>$v){
						$original_url .= $k.'='.$v.'&';
					}
					$original_url = substr($original_url,0,-1);			
				}else{
					$original_url = $val['url'];
				}
				if(URL_REWRITE){
					$lList[] = array('url'=>$val['url'],'link_body'=>$val['url'],'original_url'=>$original_url);
				}else{
					$lList[] = array('url'=>$original_url,'link_body'=>$original_url,'original_url'=>$original_url);
				}
			}
			$rows = db_arrays("SELECT `sys_name`,`category`,`name` FROM `".DB_LEFT."_posts` WHERE `in_blog` = '1' ORDER by `id` DESC");
			foreach($rows as $key => $row){
				$lList[] = array('url'=>show_link_page($categories[$row['category']]['link'],$row['sys_name']),'link_body'=>$row['name'],'original_url'=>show_link_page($categories[$row['category']]['link'],$row['sys_name'],true));
			}
			output_json(array('status'=>1,'data'=>$lList));
		break;
		default:
			$id = intval($_GET['id']);
			$row = array();
			if($id > 0){
				$row = db_array("SELECT * FROM `".ADB."_app_menus` WHERE `id` = '$id'");
			}
			$where = " `parent_id` = '$id'";
			$data = db_fetch(array(
				'table' => "`".ADB."_app_menus`",
				'field' => "*",
				'where' => $where,
				'order' => " `order` ASC "
			));		
			$app_inc = 'menu.php';
	}
?>