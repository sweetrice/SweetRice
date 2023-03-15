<?php
/**
 * Template Name:Tags page template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
if (is_array($page_theme) && file_exists(THEME_DIR . $page_theme['head'])) {
    include THEME_DIR . $page_theme['head'];
}
?>
<script type="text/javascript" src="js/init.js"></script>
<div id="div_center">
	<div id="div_right">
	<div id="nav"><a href="<?php echo BASE_URL; ?>"><?php _e('Home');?></a> &raquo; <?php _e('Tag');?> <a href="<?php echo show_link_tag($tag); ?>"><?php echo $tag; ?></a></div>
	<div id="posts">
<?php
if (count($rows) == 0):
    echo '<div align="center">' . _t('No Entry') . '</div>';
else:
    foreach ($rows as $row):
        echo _posts($row, $post_output);
    endforeach;
endif;
?>
</div>
     <div align="center">	<?php echo $pager['list_put']; ?></div>
			<div id="pins_loader"></div>
<script type="text/javascript">
<!--
	var query = new Object();
	query.action = 'tags';
	query.tag = '<?php echo $tag; ?>';
	query.p = <?php echo $pager['page']; ?>;
	var bodyId = 'posts';
//-->
</script>
<script type="text/javascript" src="js/pins.js"></script>
</div>
<?php
if (is_array($page_theme) && file_exists(THEME_DIR . $page_theme['sidebar'])) {
    include THEME_DIR . $page_theme['sidebar'];
}
?>
<div class="div_clear"></div></div>
<?php
if (is_array($page_theme) && file_exists(THEME_DIR . $page_theme['foot'])) {
    include THEME_DIR . $page_theme['foot'];
}
?>