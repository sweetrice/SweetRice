<?php
/**
 * Site management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.1
 */
defined('VALID_INCLUDE') or die();
?>
<form method="post" action="./?type=permalinks&mode=system&submode=save">
<fieldset><legend><?php _e('Permalinks');?></legend>
<div id="permalinks">
<div class="form_split"><span class="w100"><?php _e('Attachment');?></span></div>
<div class="form_split"><input type="text" name="attachment_alias" value="<?php echo $permalinks['attachment']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['attachment']; ?></span>/fileName.ext
</div>
<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php _e('RSSFeed');?></span></div>
<div class="form_split"><input type="text" name="rssfeed_alias" value="<?php echo $permalinks['rssfeed']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['rssfeed']; ?></span>.xml
</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php echo _t('Category') . ' ' . _t('RSSFeed'); ?></span></div>
<div class="form_split"><input type="text" name="rssfeedCat_alias" value="<?php echo $permalinks['rssfeedCat']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['rssfeedCat']; ?></span>/categoryName.xml</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php echo _t('Post') . ' ' . _t('RSSFeed'); ?></span></div>
<div class="form_split"><input type="text" name="rssfeedPost_alias" value="<?php echo $permalinks['rssfeedPost']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['rssfeedPost']; ?></span>/postName.xml</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php echo _t('Sitemap') . ' ' . 'XML'; ?></span></div>
<div class="form_split"><input type="text" name="sitemapXml_alias" value="<?php echo $permalinks['sitemapXml']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['sitemapXml']; ?></span>.xml</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php echo _t('Sitemap') . ' ' . 'HTML'; ?></span></div>
<div class="form_split"><input type="text" name="sitemapHtml_alias" value="<?php echo $permalinks['sitemapHtml']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['sitemapHtml']; ?></span>/</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php _e('Comment');?></span></div>
<div class="form_split"><input type="text" name="comment_alias" value="<?php echo $permalinks['comment']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['comment']; ?></span>/postName/[0-9]/</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php _e('Tag');?></span></div>
<div class="form_split"><input type="text" name="tag_alias" value="<?php echo $permalinks['tag']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['tag']; ?></span>/tagName/[0-9]/
</div>

<div class="div_clear mb10"></div>
<div class="form_split"><span class="w100"><?php _e('Ads');?></span></div>
<div class="form_split"><input type="text" name="ad_alias" value="<?php echo $permalinks['ad']; ?>"></div>
<div class="form_split"><?php _e('Example Link');?> : <?php echo BASE_URL; ?><span><?php echo $permalinks['ad']; ?></span>/adName.js</div>
</div>
</fieldset>
<input type="submit" class="input_submit" value="<?php _e('Done');?>"> <input type="button" value="<?php _e('Back');?>" url="./" class="input_submit back">
</form>