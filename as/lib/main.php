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
<li><img src="<?php echo BASE_URL;?><?php echo $global_setting['logo']?ATTACHMENT_DIR.$global_setting['logo']:'images/logo.png';?>" alt="<?php echo $global_setting['name'];?>">
<p><?php echo vsprintf(_t('<span>%s</span> System Information'),array($global_setting['name']));?></p></li>
<li><?php echo vsprintf(_t('Website status : %s'),array($global_setting['close']?'<span>'._t('Close').'</span> '._t(' You can through <a href="./?type=setting">Setting</a> to change it.'):'<span>'._t('Running').'</span>'));?></li>
<li><?php echo vsprintf(_t('Database <span>%s</span> <span>%s</span> <span>%s</span>'),array(DATABASE_TYPE,(($db||$conn)?_t('Connected'):_t('does not connected')),(DATABASE_TYPE == 'sqlite'?_t('SQLite driver ').$sqlite_driver:'')));?></li>
<li><?php _e('Category');?> <span><?php echo $cat_total;?></span> <?php _e('Click <a href="./?type=category&mode=insert">here</a> to create category.');?></li>
<li><?php _e('Post');?> <span><?php echo $post_total;?></span> <?php _e('Publish');?> : <span><?php echo $post_total_pub;?></span> <?php _e('Click <a href="./?type=post&mode=insert">here</a> to create post.');?></li>
<li><?php _e('Comment');?> <a href="./?type=comment"><span><?php echo $comment_total;?></span></a></li>
<li><?php _e('URL rewrite');?> : <span><?php echo $global_setting['url_rewrite']?_t('Open'):_t('Close');?></span> <?php _e(' You can through <a href="./?type=setting">Setting</a> to change it.');?></li>
<li><?php _e('Theme');?> : <span><?php echo $global_setting['theme']?$global_setting['theme']:_t('Default');?></span> <?php _e('You can through <a href="./?type=setting">Setting</a> to choose theme.');?></li>
<li><?php _e('Sitemap')?> : <a href="<?php echo BASE_URL,show_link_sitemapHtml();?>">html</a> | <a href="<?php echo BASE_URL,show_link_sitemapXml();?>">xml</a></li>
<li>RSSFeed : <a href="<?php echo BASE_URL,show_link_rssfeed();?>"><img src="../images/xmlrss.png" /></a></li>
<li><?php echo $lastest_update?'<a href="./?type=update">SweetRice '.$lastest_update.' '._t('released').'</a>,'._t('Please upgrade SweetRice.Important: before upgrading, please <a href="./?type=data&mode=db_backup">backup your database</a> and files.'):'';?></li>
</ol>
</div>
<div class="ball"></div>
<script type="text/javascript">
<!--
var timer = null;
	function splash(){
		var color = randomColor();
		var bgcolor = '';
		for (var i = 0;i < 6 ;i++ )
		{
			bgcolor += (0xFF - parseInt(color[i]+color[i+1],16)).toString(16);
			i++ ;
		}
		_('#admin_right').css({'color':'#'+color,'background-color':'#'+bgcolor}).addClass('splash');
		_('.splash .ball').css({'background-color':'#'+color});
		window.clearTimeout(timer);
		timer = setTimeout(splash,8000);
	}
	function randomColor( ) {  
		var rand = Math.floor(Math.random( ) * 0xFFFFFF).toString(16);  
		if(rand.length == 6){  
				return rand;  
		}else{  
				return randomColor();  
		}
	}
	_('#admin_right').bind('mousemove',function(){
		_('#admin_right').css({'color':'#222','background-color':'#f0f0f0'}).removeClass('splash');
		window.clearTimeout(timer);
		timer = setTimeout(splash,15000);
	});
//-->
</script>