<?php
/**
 * Template Name:Rssfeed template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
header('Content-type:text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<rss version="2.0">'."\n".'<channel>'."\n";
echo '<title><![CDATA[',htmlspecialchars_decode($categories[$cat_id]['name'],ENT_QUOTES),']]></title>',"\n<image><title><![CDATA[",htmlspecialchars_decode($categories[$cat_id]['name'],ENT_QUOTES),"]]></title><url>".($global_setting['logo']?SITE_URL.ATTACHMENT_DIR.$global_setting['logo']:BASE_URL.'images/sweetrice.png')."</url><link><![CDATA[",$cat_page[$cat_id],"]]></link></image>\n<link><![CDATA[",BASE_URL.show_link_cat($categories[$cat_id]['link'],''),"]]></link>\n","<description><![CDATA[",htmlspecialchars_decode($categories[$cat_id]['description'],ENT_QUOTES),"]]></description>","\n";
$old_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
$new_link = array('src="'.SITE_URL.ATTACHMENT_DIR,'data="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.ATTACHMENT_DIR);
	foreach($rows as $row){
		$tmp_rss = "<item>\n";
		$tmp_rss .= "<title><![CDATA[".htmlspecialchars_decode($row['name'],ENT_QUOTES)."]]></title>\n";
		$tmp_rss .= "<link><![CDATA[".BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name'])."]]></link>\n";
		$tmp_rss .= "<description><![CDATA[".str_replace($old_link,$new_link,filterXMLContent($row['body']))."]]></description>\n";
		$tmp_rss .= "<pubDate>".date('Y-m-d',$row['date'])."</pubDate>\n";
		$tmp_rss .= "<author><![CDATA[".$global_setting['author']."]]></author>\n";
		$tmp_rss .= "</item>\n";
		echo $tmp_rss;
	}
echo "</channel>\n</rss>";
?>