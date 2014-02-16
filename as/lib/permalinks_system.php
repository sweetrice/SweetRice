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
<fieldset><legend><?php echo PERMALINKS;?></legend>
<div id="permalinks">
<ul>
<li><?php echo ATTACHMENT;?> : <input type="text" name="attachment_alias" value="<?php echo $permalinks['attachment'];?>"> 
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['attachment'];?></span>/fileName.ext</p>
</li>
<li><?php echo RSSFEED;?> : <input type="text" name="rssfeed_alias" value="<?php echo $permalinks['rssfeed'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['rssfeed'];?></span>.xml</p>
</li>
<li><?php echo CATEGORY.' '.RSSFEED;?> : <input type="text" name="rssfeedCat_alias" value="<?php echo $permalinks['rssfeedCat'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['rssfeedCat'];?></span>/categoryName.xml</p></li>
<li><?php echo POST.' '.RSSFEED;?> : <input type="text" name="rssfeedPost_alias" value="<?php echo $permalinks['rssfeedPost'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['rssfeedPost'];?></span>/postName.xml</p></li>
<li><?php echo SITEMAP.' '.XML;?> : <input type="text" name="sitemapXml_alias" value="<?php echo $permalinks['sitemapXml'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['sitemapXml'];?></span>.xml</p></li>
<li><?php echo SITEMAP.' '.HTML;?> : <input type="text" name="sitemapHtml_alias" value="<?php echo $permalinks['sitemapHtml'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['sitemapHtml'];?></span>/</p></li>
<li><?php echo COMMENT;?> : <input type="text" name="comment_alias" value="<?php echo $permalinks['comment'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['comment'];?></span>/postName/[0-9]/</p></li>
<li><?php echo TAG;?> : <input type="text" name="tag_alias" value="<?php echo $permalinks['tag'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['tag'];?></span>/tagName/[0-9]/</p>
</li>
<li><?php echo AD;?> : <input type="text" name="ad_alias" value="<?php echo $permalinks['ad'];?>">
<p><?php echo EXAMPLE_LINK;?> : <?php echo BASE_URL;?><span><?php echo $permalinks['ad'];?></span>/adName.js</p>
</li>
</ul>
</div>
</fieldset>
<input type="submit" class="input_submit" value="<?php echo DONE;?>"> <input type="button" value="<?php echo BACK;?>" onclick="location.href='./';" class="input_submit">
</form>