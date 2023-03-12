<?php	
/**
 *  Template Name:Sitemap page template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	if(is_array($page_theme) && file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}
?>
<div id="div_center">
	<div id="div_right">
<div id="nav"><a href="<?php echo BASE_URL;?>"><?php _e('Home');?></a> &raquo; <a href="<?php echo show_link_sitemapHtml();?>"><?php _e('Sitemap');?></a></div>
<div id="sitemap">
<ul>
<?php
foreach($lList as $key=>$val):
	switch($val['type']):
		case 'category':
			echo '<li><a href="',$val['link_html'],'">',$val['link_html_body'],'</a> <a href="',$val['link_xml'],'">',$val['link_xml_body'],'</a></li>';
		break;
		case 'post':
			echo '<li><a href="',$val['link_html'],'">',$val['link_html_body'],'</a> <a href="',$val['link_xml'],'">',$val['link_xml_body'],'</a> '.date('M,d,Y',$val['date']).'</li>';
		break;
		case 'custom':
			echo '<li><a href="',$val['link_html'],'">',$val['link_html_body'],'</a> <a href="',$val['link_xml'],'">',$val['link_xml_body'],'</a></li>';
		break;
		default:
			echo '<li><a href="',$val['link_html'],'">',$val['link_html_body'],'</a>';
	endswitch;
endforeach;
?>
</ul>
</div>
<?php echo $data['pager']['list_put'];?>
</div>
<?php	
	if(is_array($page_theme) && file_exists(THEME_DIR.$page_theme['sidebar'])){
		include(THEME_DIR.$page_theme['sidebar']);
	}
?>
<div class="div_clear"></div></div>
<?php	
	if(is_array($page_theme) && file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>