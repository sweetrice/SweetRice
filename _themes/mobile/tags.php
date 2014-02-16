<?php
/**
 * Template Name:Tags page template.
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
<script type="text/javascript" src="js/init.js"></script>
<div id="content">
	<div id="nav"><a href="<?php echo BASE_URL;?>"><?php echo HOME;?></a> &raquo; <?php echo TAG;?> <a href="<?php echo show_link_tag($tag);?>"><?php echo $tag;?></a></div>
	<div id="posts">
<?php	
	if(count($rows)==0):
		echo '<div align="center">'.NO_ENTRY.'</div>';
	else:
		foreach($rows as $row):
			echo _posts($row,$post_output);
		endforeach;
	endif;
?>
</div>
<div align="center"> <?php echo $pager['list_put'];?> </div>
		 <div id="pins_loader"></div>
<script type="text/javascript">
<!--
	var query = new Object();
	query.action = 'tags';
	query.tag = '<?php echo $tag;?>';
	query.p = <?php echo $pager['page'];?>;
	var bodyId = 'posts';
//-->
</script>
<script type="text/javascript" src="js/pins.js"></script>
</div>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);	
	}
?>