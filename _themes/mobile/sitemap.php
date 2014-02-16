<?php
/**
 * Template Name:Sitemap page template.
 *
 * @package SweetRice
 * @Wblog template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	if(file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}
?>
<div id="content">
<div id="nav"><a href="<?php echo BASE_URL;?>"><?php echo HOME;?></a> &raquo; <a href="<?php echo show_link_sitemapHtml();?>"><?php echo SITEMAP;?></a></div>
<div id="sitemap">
    <ul>
	<?php
		foreach($categories as $val):
				echo '<li><a href="',show_link_cat($val['link'],''),'">',$val['name'],'</a></li>';
		endforeach;
		foreach($rows as $row):
			echo '<li><a href="',show_link_page($categories[$row['category']]['link'],$row['sys_name']),'">',$row['name'],'</a>  <a href="'.show_link_page_xml($row["sys_name"]).'"><img src="images/xmlrss.png"></a> <span class="sitemap_date">',date('M,d,Y',$row['date']),'</span></li>';
		endforeach;
	?>
</ul>
  </div>
</div>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>