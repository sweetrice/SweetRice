<?php
/**
 * Template Name:Comment output page template.
 *
 * @package SweetRice
 * @Default template
 * @since 1.3.0
 */
 defined('VALID_INCLUDE') or die();
?>
<dl>
<dt><a name="comment_<?php echo $k;?>"></a><a href="<?php echo $comment_link,'#comment_',$k;?>">#<?php echo $last_no;?></a></dt>
<dd class="comment_man"><p><?php echo $val['website']?'<a href="'.$val['website'].'" rel="external nofollow">'.$val['name'].'</a>':$val['name'];?></p> 
<p><?php echo date(POST_DATE_FORMAT,$val['date']);?></p></dd>
<dd class="comment_info"><?php echo nl2br($val['info']);?></dd>
</dl>
<div class="div_clear"></div>