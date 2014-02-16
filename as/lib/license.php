<?php
/**
 * SweetRice license template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>	
<div id="license">
<?php
$content = file_get_contents('lib/license.txt');
preg_match("/\<lang=\"".str_replace('.php','',$lang)."\"\>([\s\S]+?)\<\/lang\>/u",$content,$matches);
	echo nl2br($matches[1]);
?>
</div>
<p>
<input type="button" class="input_submit" value="<?php echo ACCEPT;?>" onclick='location.href="./?action=install";'> <input type="button" value="<?php echo NOTACCEPT;?>" onclick='location.href="./";'></p>