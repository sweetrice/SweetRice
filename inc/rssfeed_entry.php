<?php
/**
 * Template Name:Rssfeed entry template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.6.8
 */
defined('VALID_INCLUDE') or die();
header("Content-type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<rss version="2.0">'."\n".'<channel>'."\n";
echo '<title><![CDATA[',htmlspecialchars_decode($row['name'],ENT_QUOTES),']]></title>',"\n<image><title><![CDATA[",htmlspecialchars_decode($row['name'],ENT_QUOTES),"]]></title><url>".($global_setting['logo']?SITE_URL.ATTACHMENT_DIR.$global_setting['logo']:BASE_URL.'images/sweetrice.jpg')."</url><link><![CDATA[",htmlspecialchars_decode($row['name'],ENT_QUOTES),"]]></link></image>\n<link><![CDATA[".BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name'])."]]></link>\n","<description><![CDATA[",htmlspecialchars_decode($row['description'],ENT_QUOTES),"]]></description>","\n";
$old_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
$new_link = array('src="'.BASE_URL.ATTACHMENT_DIR,'data="'.BASE_URL.ATTACHMENT_DIR,'value="'.BASE_URL.ATTACHMENT_DIR);
	foreach($comments as $comment){
		$no +=1;
		if($no==1){
			$comment_body .= '<h2>'.USER_COMMENTS.'</h2>';
		}
		$comment_body .= '<hr><h2>'.$no;
		$comment_body .= ' - '.($comment['website']?'<a href="'.$comment['website'].'" rel="external nofollow">'.$comment['name'].'</a>':$comment['name']); 
		$comment_body .= ' - '.date('M,d,Y D',$comment['date']).'</h2>';
		$comment_body .= nl2br($comment['info']);
	}
		$tmp_rss = "<item>\n";
		$tmp_rss .= "<title><![CDATA[".htmlspecialchars_decode($row['name'],ENT_QUOTES)."]]></title>\n";
		$tmp_rss .= "<link><![CDATA[".BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name'])."]]></link>\n";
		$tmp_rss .= "<description><![CDATA[".str_replace($old_link,$new_link,filterXMLContent($row['body'].$comment_body))."]]></description>\n";
		$tmp_rss .= "<pubDate>".date('Y-m-d',$row['date'])."</pubDate>\n";
		$tmp_rss .= "<author><![CDATA[".$global_setting['author']."]]></author>\n";
		$tmp_rss .= "</item>\n";
		echo $tmp_rss;

echo "</channel>\n</rss>";
?>