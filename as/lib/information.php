<?php
/**
 * SweetRice install information template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<p><?php echo INSTALL_INFORMATION;?></p>
<?php echo LANG;?>:
<select name="lang" onchange='location.href="./?action=lang&lang="+this.options[this.selectedIndex].value;'>
	<option value="" selected="selected"><?php echo DEFAULT_TIP;?></option>
<?php
	foreach($langs as $key=>$val){
?>
<option value="<?php echo $key;?>.php" <?php echo $s_lang[$key.'.php'];?> ><?php echo $val;?></option>
<?php
	}	
?>
</select>