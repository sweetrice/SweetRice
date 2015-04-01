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
<form method="post" action="./?type=permalinks&linkType=system&mode=save">
<fieldset><legend><?php _e('Permalinks');?></legend>
<div id="permalinks">
<dl><dt><?php _e('Attachment');?></dt><dd><input type="text" name="attachment_alias" value="<?php echo $permalinks['attachment'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['attachment'];?></span>/fileName.ext
</dd></dl>
<dl><dt><?php _e('RSSFeed');?></dt><dd><input type="text" name="rssfeed_alias" value="<?php echo $permalinks['rssfeed'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['rssfeed'];?></span>.xml
</dd></dl>
<dl><dt><?php echo _t('Category').' '._t('RSSFeed');?></dt><dd><input type="text" name="rssfeedCat_alias" value="<?php echo $permalinks['rssfeedCat'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['rssfeedCat'];?></span>/categoryName.xml</dd></dl>
<dl><dt><?php echo _t('Post').' '._t('RSSFeed');?></dt><dd><input type="text" name="rssfeedPost_alias" value="<?php echo $permalinks['rssfeedPost'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['rssfeedPost'];?></span>/postName.xml</dd></dl>
<dl><dt><?php echo _t('Sitemap').' '.'XML';?></dt><dd><input type="text" name="sitemapXml_alias" value="<?php echo $permalinks['sitemapXml'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['sitemapXml'];?></span>.xml</dd></dl>
<dl><dt><?php echo _t('Sitemap').' '.'HTML';?></dt><dd><input type="text" name="sitemapHtml_alias" value="<?php echo $permalinks['sitemapHtml'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['sitemapHtml'];?></span>/</dd></dl>
<dl><dt><?php _e('Comment');?></dt><dd><input type="text" name="comment_alias" value="<?php echo $permalinks['comment'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['comment'];?></span>/postName/[0-9]/</dd></dl>
<dl><dt><?php _e('Tag');?></dt><dd><input type="text" name="tag_alias" value="<?php echo $permalinks['tag'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['tag'];?></span>/tagName/[0-9]/
</dd></dl>
<dl><dt><?php _e('Ads');?></dt><dd><input type="text" name="ad_alias" value="<?php echo $permalinks['ad'];?>"> <?php _e('Example Link');?> : <?php echo BASE_URL;?><span><?php echo $permalinks['ad'];?></span>/adName.js</dd></dl>
</div>
</fieldset>
<input type="submit" class="input_submit" value="<?php _e('Done');?>"> <input type="button" value="<?php _e('Back');?>" url="./" class="input_submit back">
</form>