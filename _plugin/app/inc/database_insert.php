<?php
/**
 * Database management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="post" id="database_form" action="./?type=plugin&plugin=<?php echo THIS_APP;?>&app_mode=database&mode=insert">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl;?>"/>
<input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
<fieldset><legend><?php _e('Content');?></legend>
<input type="text" name="content" id="content" value="<?php echo $row['content'];?>">
</fieldset>
<input type="submit" value=" <?php _e('Done');?>"> 
</form>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('#database_form').bind('submit',function(event){
			if (!_('#content').val()){
				alert('<?php _e('Content is required');?>');
				_().stopevent(event);
			}
		});
		
	});
//-->
</script>