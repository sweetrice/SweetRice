<?php
/**
 * Sitemap management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 switch($mode){
	case 'hide':
		setOption('hidden_from_sitemap',$_POST['plist']?serialize($_POST['plist']):'');
		_goto('./?type=sitemap');
	break;
	case 'make_index':
		$url = js_unescape($_POST['url']);
		$req = js_unescape($_POST['req']);
		if(!$url || !$req){
			output_json(array('status'=>0));
		}
		setOption('index_setting',serialize(array('url'=>$url,'req'=>$req)));
		output_json(array('status'=>1));
	break;
	case 'restore_index':
		delOption('index_setting');
		output_json(array('status'=>1));
	break;
	default:
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
				$original_url = $row['url'];
			}
			if(URL_REWRITE){
				$lList[] = array('url'=>$val['url'],'link_body'=>$val['url'],'original_url'=>$original_url);
			}else{
				$lList[] = array('url'=>$original_url,'link_body'=>$original_url,'original_url'=>$original_url);
			}
		}
		$rows = db_arrays("SELECT `sys_name`,`category`,`name` FROM `".DB_LEFT."_posts` WHERE `in_blog` = '1' ORDER by `id` DESC");
		foreach($rows as $key=>$row){
			$lList[] = array('url'=>show_link_page($categories[$row['category']]['link'],$row['sys_name']),'link_body'=>$row['name'],'original_url'=>show_link_page($categories[$row['category']]['link'],$row['sys_name'],true));
		}
		$top_word = _t('Sitemap Management');
		$index_setting = getOption('index_setting');
		$index_setting = unserialize($index_setting['content']);
		$inc = 'sitemap.php';
 }
 ?>