<?php
/**
 * Navigation section template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
 defined('VALID_INCLUDE') or die();
?>
<div class="plugin_nav">
<a href="<?php echo pluginDashboardUrl(THIS_APP);?>" navorder="0" <?php echo !$_GET['app_mode'] ?'title="'._t('Home').'"':'';?>><?php _e('Home');?></a>
<?php foreach($myApp->app_navs() as $key=>$nav):
	if(!$nav['name']){
		continue;
	}
?>
<a href="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>$nav['app_mode']));?>" <?php echo $_GET['app_mode'] == $nav['app_mode']?'title="'.$nav['name'].'"':'';?> navorder="<?php echo $key+1;?>"><?php echo $nav['name'];?></a>
<?php endforeach;?>
<div class="nav_line">
<span class="curr_line"></span>
</div>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.plugin_nav a').each(function(){
			if (_(this).attr('title'))
			{
				curr_nav(this);
			}
		}).bind('mouseover',function(){
			curr_nav(this);
		});
	});
	function curr_nav(obj){
		_('.curr_line').animate({'width':_(obj).width()+'px','left':(_(obj).position().left - _(obj).parent().position().left)+'px'},200);
	}
//-->
</script>