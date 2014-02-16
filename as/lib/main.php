<?php
/**
 * Dashborad home template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<div id="sweetrice_status">
<ol>
<li><img src="<?php echo BASE_URL;?><?php echo $global_setting['logo']?ATTACHMENT_DIR.$global_setting['logo']:'images/sweetrice.jpg';?>" alt="<?php echo $global_setting['name'];?>">
<p><strong><?php echo vsprintf(SYSTEM_INFORMATION,array($global_setting['name']));?></strong></p></li>
<li><?php echo vsprintf(SITE_STATUS,array($global_setting['close']?CLOSE_TIP.' '.OPEN_TIPS:OPEN));?></li>
<li><?php echo vsprintf(DATABASE_STATUS,array((($db||$conn)?CONNECTED:DNS_CONNECTED),DATABASE_TYPE,(DATABASE_TYPE=='sqlite'?SQLITE_DRIVER.$sqlite_driver:'')));?></li>
<li><?php echo CATEGORY;?> <strong><?php echo $cat_total;?></strong> <?php echo CREAT_CAT_TIPS;?></li>
<li><?php echo POST;?> <strong><?php echo $post_total;?></strong> <?php echo PUBLISH;?> : <strong><?php echo $post_total_pub;?></strong> <?php echo CREAT_POST_TIPS;?></li>
<li><?php echo COMMENT;?> <a href="./?type=comment"><strong><?php echo $comment_total;?></strong></a></li>
<li><?php echo URL_REWRITE_TIP;?> : <strong><?php echo $global_setting['url_rewrite']?OPEN:CLOSE_TIP?></strong> <?php echo OPEN_TIPS;?></li>
<li><?php echo THEME;?> : <strong><?php echo $global_setting['theme']?$global_setting['theme']:DEFAULT_TIP;?></strong> <?php echo CHANGE_THEME_TIP;?></li>
<li>Sitemap : <a href="<?php echo BASE_URL,show_link_sitemapHtml();?>">html</a> | <a href="<?php echo BASE_URL,show_link_sitemapXml();?>">xml</a></li>
<li>RssFeed : <a href="<?php echo BASE_URL,show_link_rssfeed();?>"><img src="../images/xmlrss.png" /></a></li>
<li><?php echo $lastest_update?'<a href="./?type=update">SweetRice '.$lastest_update.' '.RELEASED.'</a>,'.TIPS_UPDATE:'';?></li>
</ol>
</div>