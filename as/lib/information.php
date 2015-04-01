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
<p><?php _e('Welcome to SweetRice install form.');?></p>
<?php _e('Language');?>:
<select name="lang" class="lang">
	<option value="" selected="selected"><?php _e('Default');?></option>
<?php
	foreach($langs as $key=>$val){
?>
<option value="<?php echo $key;?>.php" <?php echo $s_lang[$key.'.php'];?> ><?php echo $val;?></option>
<?php
	}	
?>
</select>
<input type="button" value="<?php _e('Install');?>" class="btn_license">
<script type="text/javascript">
<!--
_().ready(function(){
	_('.lang').bind('change',function(){
		location.href = './?action=lang&lang='+_(this).val();
	});
	_('.btn_license').bind('click',function(){
		location.href = './?action=license';
	});
});
//-->
</script>