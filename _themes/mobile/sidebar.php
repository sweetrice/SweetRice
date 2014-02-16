<?php
/**
 * Template Name:Sidebar section template.
 *
 * @package SweetRice
 * @Wblog template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	$category_list = getCategoryPosts($global_setting['nums_setting']['postCategories']);
	$uncategory_list = getUncategoryPosts($global_setting['nums_setting']['postUnCategories']);
	$t = getTagLists($global_setting['nums_setting']['tags']);
?>
<div id="sidebar_body">
<div id="sidebar" onmouseover="show_sidebar();" onmouseout="hidden_sidebar();">
<div id="float_nav" style="position: absolute; top: 30px; z-index: 100; " onmouseover="show_sidebar();">&laquo; <?php echo NAVIGATION;?></div>
<div id="menu" onmouseover="show_sidebar();" onmouseout="hidden_sidebar();">
	<h4><?php echo UNCATEGORY;?></h4>		
	<div class="entry_list">
		<ul>
		<li><a href="<?php echo BASE_URL;?>"><?php echo HOME;?></a></li>
		<li><a href="<?php echo show_link_sitemapHtml();?>"><?php echo SITEMAP;?></a></li>
		<?php
			if(count($uncategory_list)):
				foreach($uncategory_list as $post_row):?>
			<li><a href="<?php echo show_link_page('',$post_row['sys_name']);?>"><?php echo $post_row['name'];?></a></li>
		<?php endforeach;?>
		<?php endif;?>
		</ul>
	</div>
<?php
	foreach($category_list as $val):
	for($i=0; $i<$val['category_level']; $i++):
		$prefix_nav .= '-- ';
	endfor;
?>
<h4><a href="<?php echo show_link_cat($categories[$val['category_id']]['link'],'');?>"><?php echo $prefix_nav.$categories[$val['category_id']]['name'];?></a> (<?php echo $val['total_post'];?>)</h4>
<div class="entry_list">
<?php if(is_array($val['post_rows'])):?>
<ul>
<?php 
	foreach($val['post_rows'] as $post_row):?>
	<li><a href="<?php echo show_link_page($categories[$post_row['category']]['link'],$post_row['sys_name']);?>"><?php echo $post_row['name'];?></a></li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>
<?php endforeach;?>

<?php if(count($t)):?>
<h4><?php echo TAGS;?></h4>
<div class="tags">
<?php
	$taglist = array();
	foreach($t as $ts):
		$tags = explode(',',$ts['tags']);
		foreach($tags as $val):
			$val = trim($val);
			if($val&&!in_array($val,$taglist)):
				$tmp = strlen($val)%5;
?>
<a href="<?php echo show_link_tag($val);?>"><?php echo $tmp==1?'<span class="tags_big">'.$val.'</span>':($tmp==2?'<span class="tags_middle">'.$val.'</span>':$val);?></a> 
<?php
				$taglist[] = $val;
			endif;
		endforeach;
	endforeach;
?>
</div>
<?php endif;?>

<?php if($links['content']):?>
<h4><?php echo LINKS;?></h4>
<div id="links">
<?php echo $links['content'];?>
</div>
<?php endif;?>
</div>
<script type="text/javascript">
<!--
	function show_sidebar(){
			$('menu').style.display = 'block';
	}
	function hidden_sidebar(){
			$('menu').style.display = 'none';
	}
	var originalTop = parseInt($("float_nav").style.top);
	function floatNav(){
		var diffY = document.body.scrollTop||document.documentElement.scrollTop;
		var percent = (diffY + originalTop - $("float_nav").offsetTop)/10;
		percent = percent>0? Math.ceil(percent):Math.floor(percent);
		$("float_nav").style.top =  $("float_nav").offsetTop + percent + "px";
		$("menu").style.minHeight =  $("float_nav").offsetTop + percent + 40 + "px";
		$("menu").style.right =  '0px';
	}
	window.setInterval(floatNav,3);
//-->
</script>
</div>
</div>