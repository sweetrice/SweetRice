<?php
/**
 * Sitemap Control Center
 *
 * @package SweetRice
 * @Default template
 * @since 0.7.0
 */
 	defined('VALID_INCLUDE') or die();
	$hList = array();
	$hRow = getOption('hidden_from_sitemap');
	if($hRow['content']){
		$hList = unserialize($hRow['content']);
	}
	$index_setting = getOption('index_setting');
	if($index_setting['content']){
		$index_setting = unserialize($index_setting['content']);
	}
	$lList = array();
	if(is_array($categories)){
		foreach($categories as $val){
			if(!in_array(show_link_cat($val['link'],''),$hList) && show_link_cat($val['link'],'') != $index_setting['url']){
				$lList[] = array('link_html'=>BASE_URL.show_link_cat($val['link'],''),'link_html_body'=>$val['name'],'link_xml'=>show_link_cat_xml($val['link']),'link_xml_body'=>'<img src="images/xmlrss.png">','type'=>'category');
			}
		}
	}
	$pRows = db_arrays("SELECT * FROM `".DB_LEFT."_links` ORDER BY `url` ASC ");
	foreach($pRows as $key=>$val){
		if(URL_REWRITE){
			if(!in_array($val['url'],$hList) && $val['url'] != $index_setting['url']){
				$lList[] = array('link_html'=>BASE_URL.$val['url'],'link_html_body'=>$val['url'],'type'=>'custom');
			}
		}else{
			$reqs = unserialize($val['request']);
			if($reqs){
				$original_url = '?';
				foreach($reqs as $key=>$val){
					$original_url .= $key.'='.$val.'&';
				}
				$original_url = substr($original_url,0,-1);			
			}else{
				$original_url = $row['url'];
			}
			if(!in_array($original_url,$hList) && $original_url != $index_setting['req']){
				$lList[] = array('link_html'=>BASE_URL.$original_url,'link_html_body'=>$original_url,'type'=>'custom');
			}
		}
	}
	$rows = db_arrays("SELECT `sys_name`,`category`,`name`,`date` FROM `".DB_LEFT."_posts` WHERE `in_blog` = '1' ORDER by `id` DESC");
	foreach($rows as $row){
		if(!in_array(show_link_page($categories[$row['category']]['link'],$row['sys_name']),$hList) && show_link_page($categories[$row['category']]['link'],$row['sys_name']) != $index_setting['url']){
			$lList[] = array('link_html'=>BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']),'link_html_body'=>$row['name'],'link_xml'=>show_link_page_xml($row["sys_name"]),'link_xml_body'=>'<img src="images/xmlrss.png">','type'=>'post','date'=>$row['date']);
		}
	}
	$type = $_GET["type"];
	if($type=='xml'){
		$last_modify = pushDate(array($rows,array('date'=>$hRow['date'])));
		include("inc/sitemap_xml.php");
		exit();
	}else{
		$title = SITEMAP.' - '.$global_setting['name'];
		$description = 	$global_setting['description'];
		$keywords = $global_setting['keywords'];
		$inc = THEME_DIR.$page_theme['sitemap'];	
		$last_modify = pushDate(array($rows,array(array('date'=>filemtime($inc)),array('date'=>$hRow['date']))));
	}
?>