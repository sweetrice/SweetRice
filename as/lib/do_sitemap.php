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
		$submode = $_POST['submode'];
		$hidden_from_sitemap = getOption('hidden_from_sitemap','serialize');
		if($submode != 'show'){
			foreach($_POST['plist'] as $val){
				if(!in_array($val,$hidden_from_sitemap['output'])){
					$hidden_from_sitemap['output'][] = $val;
				}
			}
		}else{
			$tmp = array();
			foreach($hidden_from_sitemap['output'] as $val){
				if(!in_array($val,$_POST['plist'])){
					$tmp[] = $val;
				}
			}
			$hidden_from_sitemap['output'] = $tmp;
		}
		setOption('hidden_from_sitemap',serialize($hidden_from_sitemap['output']));
		_goto($_SERVER['HTTP_REFERER']);
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
		$mode = $_GET['mode'];
		$pager = array('p_link'=>'./?type=sitemap&mode='.$mode.'&','page_limit'=>page_limit(),'pager_function' => '_pager');
		switch($mode){
			case 'custom':
				$data = db_fetch(array('table' => "`".DB_LEFT."_links`",
				'order' => " `url` ASC",
				'pager' =>  $pager
				));
				foreach($data['rows'] as $val){
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
			break;
			case 'post':
				$data = db_fetch(array('table' => "`".DB_LEFT."_posts`",
				'where'=>"`in_blog` = '1'",
				'order' => " `date` DESC",
				'pager' => $pager
				));
				foreach($data['rows'] as $key=>$row){
					$lList[] = array('url'=>show_link_page($categories[$row['category']]['link'],$row['sys_name']),'link_body'=>$row['name'],'original_url'=>show_link_page($categories[$row['category']]['link'],$row['sys_name'],true));
				}
			break;
			default:
				$data = db_fetch(array('table' => "`".DB_LEFT."_category`",
				'order' => " `ID` ASC",
				'pager' => $pager
				));
				foreach($data['rows'] as $val){
					$lList[] = array('url'=>show_link_cat($val['link'],''),'link_body'=>$val['name'],'original_url'=>show_link_cat($val['link'],'',true));
				}
		}
		$top_word = _t('Sitemap Management');
		$index_setting = getOption('index_setting');
		$index_setting = is_string($index_setting['content']) ? unserialize($index_setting['content']) : array();
		$inc = 'sitemap.php';
 }
 ?>