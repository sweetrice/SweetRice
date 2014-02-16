<?php
/**
 * Template Name:Post output page template.
 *
 * @package SweetRice
 * @Default template
 * @since 1.3.0
 */
 defined('VALID_INCLUDE') or die();
?>
	<h2 class="blog_title"><a href="<?php echo show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>"><?php echo $row['name'];?></a></h2>
	<div class="blog_text">
	<div class="post_info">
	<div class="list_info"><p class="list_date"><?php echo date(POST_DATE_FORMAT,$row['date']);?></p><p class="list_views"><?php echo $row['views'];?> <?php echo VIEWS;?></p><p class="div_clear"></p></div>	
	<?php echo postPreview($row['body']);?> <span title="<?php echo BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>" class="readmore"><?php echo READ_MORE;?></span></div>
	<div><?php echo TAGS;?>
		<?php
			$tags = explode(',',$row['tags']);
			foreach($tags as $key=>$val):
				if(trim($val)):
					echo '<a href="'.show_link_tag($val).'">'.$val.'</a> ';
				endif;
			endforeach;
	?></div>
	</div>