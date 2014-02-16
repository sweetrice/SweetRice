<?php	
/**
 * Entry Template:Entry page template.
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
	  <div id="nav"><a href="<?php echo BASE_URL;?>"><?php echo HOME;?></a> &raquo; <a href="<?php echo show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>"><?php echo $row['name'];?></a></div>
		<div class="blog_title"><?php echo $row['name'];?></div>
<div class="blog_text">
<?php echo $row['body'];?>
<?php
	if(count($att_rows)>0):
	foreach($att_rows as $att_row):
?>
		<div><span class="attachment" alt="<?php echo $att_row['file_name'];?>" ></span><a href="<?php echo show_link_attachment($att_row['id']);?>" ><?php echo end(explode('/',$att_row['file_name']));?></a> &raquo; <?php echo filesize2print($att_row['file_name']);?> (<?php echo $att_row['downloads'];?>)</div>
		<div class="div_clear"></div>
<?php
	endforeach;
	endif;
?>
<fieldset><legend><?php echo POST_DETAIL;?></legend>
<div><?php echo CATEGORY;?> <a href="<?php echo show_link_cat($categories[$row['category']]['link'],'');?>"><u><?php echo $categories[$row['category']]['name'];?></u></a>
	<?php echo LAST_UPDATE;?> <?php echo date(POST_DATE_FORMAT,$row['date']);?> <?php echo VIEWS;?> :<?php echo $row['views'];?></div>
	<div><?php echo PERMALINK;?><a href="<?php echo BASE_URL,show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>"><?php echo BASE_URL,show_link_page($categories[$row['category']]['link'],$row['sys_name']);?></a></div>
<div>
	<?php echo TAGS;?>
	<?php
		$tags = explode(',',$row['tags']);	
		foreach($tags as $key=>$val):
			if(trim($val)):
				echo '<a href="'.show_link_tag($val).'">'.$val.'</a> ';
			endif;
		endforeach;
	?></div>
</fieldset>
</div>
<div class="div_clear"></div>
<div class="slice_title"><span><?php echo USER_COMMENTS;?></span> 
<?php	if($comment_total>0):?>
<?php echo TOTAL;?> <?php echo intval($comment_total);?> <a href="<?php echo $comment_link;?>"><?php echo VIEW_ALL_COMMENTS;?></a>
<?php	endif;?>
</div>
<?php	if($row['allow_comment']):
include(ROOT_DIR.'inc/comment_form.php');	
else:	
echo COMMENTS_OFF;	
endif;?>

<?php	if(count($relate_entry)):?>
<div class="slice_title"><?php echo RELATED_ENTRY;?></div>
<div id="relate_entry"> 
<ul>
<?php foreach($relate_entry as $key=>$val):?>
	<li><a href="<?php echo show_link_page($categories[$val['category']]['link'],$val['sys_name']);?>"><?php echo $val['name'];?></a></li>
<?php endforeach;?>
</ul>
</div>
<?php endif;?>
</div>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);	
	}
?>