<?php
/**
 * Template Name:Comment page template.
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
	<div id="nav"><a href="<?php echo BASE_URL;?>"><?php echo HOME;?></a> &raquo; <a href="<?php echo show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>"><?php echo $row['name'];?></a> &raquo; <a href="<?php echo $comment_link;?>"><?php echo USER_COMMENTS;?></a> </div>
	<div class="post_info"><?php echo $row['body'];?></div>
	<div id="comment_list">
<?php	
	foreach($rows as $k=>$v):
		$k += 1;
		echo _comment($v,$k,$k,$comment_link,$comment_output);
	endforeach;
?>
</div>
<div align="center"> <?php echo $pager['list_put'];?> </div>
<div id="pins_loader"></div>
<script type="text/javascript">
<!--
	var query = new Object();
	query.action = 'comment';
	query.post = '<?php echo $post;?>';
	query.last_no = '<?php echo $k;?>';
	query.p = <?php echo $pager['page'];?>;
	var bodyId = 'comment_list';
//-->
</script>
<script type="text/javascript" src="js/pins.js"></script>
<div id="comment">
<?php
	if($row['allow_comment']):
		include(ROOT_DIR.'inc/comment_form.php');
	else:
		echo COMMENTS_OFF;
	endif;
?>
</div>
</div>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>