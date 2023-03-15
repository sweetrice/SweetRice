<?php
/**
 * Template Name:Comment page template.
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
<div id="div_center">
	<div id="div_right">
	<div id="nav"><a href="<?php echo BASE_URL; ?>"><?php _e('Home');?></a> &raquo; <a href="<?php echo show_link_page($categories[$row['category']]['link'], $row['sys_name']); ?>"><?php echo $row['name']; ?></a> &raquo; <a href="<?php echo $comment_link; ?>"><?php _e('User Comments');?></a> <a href="<?php echo show_link_page_xml($row['sys_name']); ?>"><img src="images/xmlrss.png"></a></div>
	<div id="comment_content"><?php echo $row['body']; ?></div>
<?php if (count($rows)): ?>
<div id="comment_list">
<?php
foreach ($rows as $k => $val):
    $k += 1;
    echo _comment($val, $k, $k, $comment_link, $comment_output);
endforeach;
?>
</div>
<?php endif;?>
<div align="center"><?php echo $pager['list_put']; ?> </div>
<div id="pins_loader"></div>
<script type="text/javascript">
<!--
	var query = new Object();
	query.action = 'comment';
	query.post = '<?php echo $post; ?>';
	query.last_no = '<?php echo $k; ?>';
	query.p = <?php echo $pager['page']; ?>;
	var bodyId = 'comment_list';
//-->
</script>
<script type="text/javascript" src="js/pins.js"></script>
<div id="comment">
<?php
if ($row['allow_comment']) {
    include THEME_DIR . $page_theme['comment_form'];
} else {
    _e('Comment Off');
}
?>
</div>
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