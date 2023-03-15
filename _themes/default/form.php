<?php
/**
 * Template Name:App form template.
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
	<h1><?php echo _t('Please complete form') . ' ' . $row['name'] ?></h1>
	<?php pluginApi('App', 'form_front', 'data', array($row));?>
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