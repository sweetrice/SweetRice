<?php
/**
 * Entry Template:Entry page template.
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
	<div class="blog_text">
		<div id="nav">
	<a href="<?php echo BASE_URL; ?>"><?php _e('Home')?></a><?php echo $row['category'] ? ' &raquo; <a href="' . show_link_cat($categories[$row['category']]['link'], '') . '">' . $categories[$row['category']]['name'] . '</a>' : ''; ?> &raquo; <a href="<?php echo BASE_URL, show_link_page($categories[$row['category']]['link'], $row['sys_name']); ?>"><?php echo $row['name']; ?></a> <a href="<?php echo show_link_page_xml($row['sys_name']); ?>"><img src="images/xmlrss.png" alt="<?php echo vsprintf(_t('Entry RSSFeed of %s'), array($row['name'])); ?>"></a></div>
	  <h1 class="blog_title"><a href="<?php echo show_link_page($categories[$row['category']]['link'], $row['sys_name']); ?>"><?php echo $row['name']; ?></a></h1>
<div class="post_info" id="post-<?php echo $row['id']; ?>">
<?php
if ($pager_pagebreak) {
    output_content($post_contents[$pager_pagebreak['page'] - 1]);
    echo $pager_pagebreak['list_put'];
} else {
    echo $row['body'];
}
?>
</div>
<?php
if (count($att_rows) > 0):
    foreach ($att_rows as $att_row):
        $att_row_path = explode('/', $att_row['file_name']);
        ?>
				<div><span class="attachment" alt="<?php echo $att_row['file_name']; ?>" ></span><a href="<?php echo show_link_attachment($att_row['id']); ?>" ><?php echo end($att_row_path); ?></a> &raquo; <?php echo filesize2print($att_row['file_name']); ?> (<?php echo $att_row['downloads']; ?>)</div>
				<div class="div_clear"></div>
		<?php
    endforeach;
endif;
?>
<fieldset><legend><?php _e('Post Detail');?></legend>
<div><?php _e('Category');?> <a href="<?php echo show_link_cat($categories[$row['category']]['link'], ''); ?>"><u><?php echo $categories[$row['category']]['name']; ?></u></a>
	<?php _e('Last Update');?> <?php echo date(_t('F,jS Y'), $row['date']); ?> <?php _e('Views');?> : <?php echo $row['views']; ?></div>
	<div><?php _e('Permalinks');?> <a href="<?php echo BASE_URL, show_link_page($categories[$row['category']]['link'], $row['sys_name']); ?>"><?php echo BASE_URL, show_link_page($categories[$row['category']]['link'], $row['sys_name']); ?></a></div>
<div>
	<?php _e('Tag');?>
	<?php
$tags = explode(',', $row['tags']);
foreach ($tags as $key => $val):
    if (trim($val)):
        echo '<a href="' . show_link_tag($val) . '">' . $val . '</a> ';
    endif;
endforeach;
?>
</div>
</fieldset>
</div>
<div class="blog_title">
<?php _e('User Comments');?>
<?php	if ($comment_total > 0): ?>
<?php _e('Total');?> <?php echo intval($comment_total); ?> <a href="<?php echo $comment_link; ?>"><?php _e('View All Comments');?></a>
<?php endif;?>
</div>
<?php
if ($row['allow_comment']):
    include THEME_DIR . $page_theme['comment_form'];
else:
    _e('Comment Off');
endif;
?>
<?php	if (count($relate_entry)): ?>
<div id="relate_entry">
<h2><?php _e('Related Entry');?></h2>
<ol>
<?php	foreach ($relate_entry as $key => $val): ?>
	<li><a href="<?php echo show_link_page($categories[$val['category']]['link'], $val['sys_name']); ?>"><?php echo $val['name']; ?></a></li>
<?php endforeach;?>
</ol>
</div>
<?php endif;?>
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