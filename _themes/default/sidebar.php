<?php
/**
 *  Template Name:Sidebar template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	$category_list = getCategoryPosts($global_setting['nums_setting']['postCategories']);
	$uncategory_list = getUncategoryPosts($global_setting['nums_setting']['postUnCategories']);
	$t = getTagLists($global_setting['nums_setting']['tags']);
?>
<div id="div_left">
<div>
<a href="<?php echo BASE_URL;?>"><?php echo HOME;?></a>
<a href="<?php echo show_link_sitemapHtml();?>"><?php echo SITEMAP;?></a>
</div>
<?php if(count($uncategory_list)):?>
<div class="entry_section">
<h4><?php echo UNCATEGORY;?></h4>		
<div class="entry_list">
<ul>
<?php foreach($uncategory_list as $post_row):?>
	<li><a href="<?php echo show_link_page(null,$post_row['sys_name']);?>"><?php echo $post_row['name'];?></a></li>
<?php endforeach;?>
</ul></div>
</div>
<?php endif;?>
<?php foreach($category_list as $val):?>
<div class="entry_section">
<h4>
<?php for($i=0; $i<$val['category_level']; $i++):
		echo '-- ';
	endfor;
?><a href="<?php echo show_link_cat($categories[$val['category_id']]['link'],'');?>"><?php echo $categories[$val['category_id']]['name'];?></a> (<?php echo $val['total_post'];?>)</h4>
<div class="entry_list">
<?php if(is_array($val['post_rows'])):?>
<ol>
<?php foreach($val['post_rows'] as $post_row):?>
	<li><a href="<?php echo show_link_page($categories[$post_row['category']]['link'],$post_row['sys_name']);?>"><?php echo $post_row['name'];?></a></li>
<?php endforeach;?>
</ol>
<?php endif;?>
</div>
</div>
<?php endforeach;?>

<?php if(count($t)):?>
<div class="entry_section"><h4><?php echo TAGS;?></h4>
<div id="tags">
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
?></div>
</div>
<?php endif;?>
<?php if($links['content']):?>
<div class="entry_section">
<h4><?php echo LINKS;?></h4>
<?php echo $links['content'];?>
</div>
<?php endif;?>
</div>