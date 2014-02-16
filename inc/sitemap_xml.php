<?php
/**
 * Template Name:Sitemap page template [xml version].
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
	$xml_header = '<url>'."\n";
	$xml_header .="<loc>".BASE_URL."</loc>\n";
	$xml_header .="<priority>1.0</priority>\n";
	$xml_header .="<lastmod>".mod_date('')."</lastmod>\n";
	$xml_header .="<changefreq>daily</changefreq>\n";
	$xml_header .= '</url>'."\n";
	foreach($lList as $key=>$val){
		switch($val['type']){
			case 'category':
				$tmp .= '<url>'."\n";
				$tmp .="<loc>".str_replace('&','&amp;',$val['link_html'])."</loc>\n";
				$tmp .="<priority>0.9</priority>\n";
				$tmp .="<lastmod>".mod_date('')."</lastmod>\n";
				$tmp .="<changefreq>daily</changefreq>\n";
				$tmp .= '</url>'."\n";
			break;
			case 'post':
				$tmp .= '<url>'."\n";
				$tmp .="<loc>".str_replace('&','&amp;',$val['link_html'])."</loc>\n";
				$tmp .="<priority>0.8</priority>\n";
				$tmp .="<lastmod>".mod_date($val['date'])."</lastmod>\n";
				$tmp .="<changefreq>daily</changefreq>\n";
				$tmp .= '</url>'."\n";
			break;
			case 'custom':
				$tmp .= '<url>'."\n";
				$tmp .="<loc>".str_replace('&','&amp;',$val['link_html'])."</loc>\n";
				$tmp .="<priority>0.8</priority>\n";
				$tmp .="<lastmod>".mod_date('')."</lastmod>\n";
				$tmp .="<changefreq>daily</changefreq>\n";
				$tmp .= '</url>'."\n";
			break;
		}
	}
	header("Content-type:text/xml");
	outputHeader($last_modify);
	echo '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="'.BASE_URL.'images/sitemap.xsl"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',"\n";
	echo $xml_header,$tmp;
	echo '</urlset>';
?>