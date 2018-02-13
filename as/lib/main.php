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
<fieldset><legend><?php echo vsprintf(_t('<span>%s</span> System Information'),array($global_setting['name']));?></legend>
<div class="form_split"><img src="<?php echo BASE_URL;?><?php echo $global_setting['logo']?ATTACHMENT_DIR.$global_setting['logo']:'images/logo.png';?>" alt="<?php echo $global_setting['name'];?>"></div>
<div class="form_split">
<?php echo vsprintf(_t('Database <span>%s</span> <span>%s</span> <span>%s</span>'),array(DATABASE_TYPE,($GLOBALS['db_lib']->stat()?_t('Connected'):_t('does not connected')),(DATABASE_TYPE == 'sqlite'?_t('SQLite driver ').$sqlite_driver:'')));?></div>
</fieldset>
<fieldset><legend><?php echo vsprintf(_t('Website status : %s'),array($global_setting['close']?_t('Close'):_t('Running')));?></legend>
<div class="setting_toggle" data="close">
	<label data="0" <?php echo !$global_setting['close'] ? 'class="setting_open"':'';?>><?php _e('Running');?></label>
<label data="1" <?php echo $global_setting['close']  ? 'class="setting_open"':'';?> ><?php _e('Close');?></label>
</div> 
</fieldset>
<fieldset><legend><?php _e('URL rewrite');?></legend>
<div class="setting_toggle" data="url_rewrite">
	<label data="1" <?php echo $global_setting['url_rewrite']  ? 'class="setting_open"':'';?>><?php _e('Enable');?></label>
<label data="0" <?php echo !$global_setting['url_rewrite']  ? 'class="setting_open"':'';?> ><?php _e('Disable');?></label>
</div>
</fieldset>
<fieldset><legend><?php _e('Theme');?></legend>
<div class="setting_toggle" data="theme">
	<label value="" <?php echo !$global_setting['theme'] ? 'class="setting_open"':'';?>><?php _e('Default');?></label>
<?php
	foreach($themes as $val){
?>
<label data="<?php echo $val;?>" <?php echo $global_setting['theme'] == $val ? 'class="setting_open"':'';?> ><?php echo $val;?></label>
<?php
	}	
?>
</div>
</fieldset>
<fieldset><legend><?php echo _t('Language');?></legend>
<div class="setting_toggle" data="front_lang">
	<label data="" <?php echo !$global_setting['theme_lang'] ? 'class="setting_open"':'';?>><?php _e('Auto detect');?></label>
<?php
	foreach($lang as $key=>$val){
?>
<label data="<?php echo $key;?>.php" <?php echo $global_setting['theme_lang'] == trim($key).'.php' ? 'class="setting_open"':'';?> ><?php echo $val;?></label>
<?php
	}
?>
</div>
</fieldset>
<fieldset><legend><?php echo _t('Dashboard').' '._t('Language');?></legend>
<div class="setting_toggle" data="dashboard_lang">
<?php
	foreach($lang as $key=>$val){
?>
<label data="<?php echo $key;?>.php" <?php echo $global_setting['lang'] == trim($key).'.php' ? 'class="setting_open"':'';?> ><?php echo $val;?></label>
<?php
	}
?>
</div>
</fieldset>
<fieldset><legend><?php _e('Category');?></legend>
<a href="./?type=category"><?php echo $cat_total;?></a>
</fieldset>
<fieldset><legend><?php _e('Post');?></legend>
<a href="./?type=post"><?php echo $post_total;?></a> (<?php _e('Publish');?> : <?php echo $post_total_pub;?>)
</fieldset>
<fieldset><legend><?php _e('Comment');?></legend>
<a href="./?type=comment"><?php echo $comment_total;?></a>
</fieldset>
<fieldset><legend><?php _e('Sitemap')?></legend>
<a href="<?php echo BASE_URL,show_link_sitemapHtml();?>" target="_blank">html</a> | <a href="<?php echo BASE_URL,show_link_sitemapXml();?>" target="_blank">xml</a>
</fieldset>
<fieldset><legend>RSSFeed</legend>
<a href="<?php echo BASE_URL,show_link_rssfeed();?>" target="_blank"><img src="../images/xmlrss.png" /></a>
</fieldset>
<?php if($lastest_update):?>
<fieldset><legend></legend>
<a href="./?type=update">SweetRice <?php echo $lastest_update.' '._t('released');?></a> <?php _e('Please upgrade SweetRice.Important: before upgrading, please <a href="./?type=data&mode=db_backup">backup your database</a> and files.');?>
</fieldset>
<?php endif;?>
</div>
<div class="ball"></div>
<script type="text/javascript">
<!--
	var timer = null;
	_.ready(function(){
		_('.setting_toggle label').click(function(){
			_.ajax({
				'type':'post',
				'data':{'data':_(this).attr('data'),'_tkv_':_('#_tkv_').attr('value')},
				'url':'./?type=setting&mode=update&submode='+_(this).parent().attr('data'),
				'success':function(result){
					if (result['status'] == 1)
					{
						window.location.reload();
					}
				}
			});
		});
		function splash(){
			var color = _.randomColor();
			var bgcolor = _.fromColor(color);
			_('#admin_right').css({'color':color,'background-color':bgcolor}).addClass('splash');
			_('.splash .ball').css({'background-color':color});
			window.clearTimeout(timer);
			timer = setTimeout(splash,8000);
		}
		_('#admin_right').bind('mousemove',function(){
			_('#admin_right').css({'color':'#222','background-color':'#f0f0f0'}).removeClass('splash');
			window.clearTimeout(timer);
			timer = setTimeout(splash,15000);
		});
	});
//-->
</script>