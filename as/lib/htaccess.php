<?php
/**
 * .htaccess management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
	if(file_exists('../inc/htaccess.txt')){
		$contents = file_get_contents('../inc/htaccess.txt');
	}else{
		$contents = '';
	}
?>
<form method="post" action="./?type=htaccess&mode=save">
<fieldset><legend><?php _e('Edit .htaccess');?> - <?php _e('this setting only available for Apache server');?></legend>
<textarea id="content" class="link"><?php echo $contents;?></textarea>
<div class="tip"><?php _e('Tips: please don\'t modify "RewriteBase %--%",it will be automatically set to the real path.');?></div>
</fieldset>
<input type="button" class="btn_submit" value="<?php _e('Done');?>"/>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_submit').bind('click',function(){
			_.ajax({
				type:'POST',
				data:{content:_('#content').val()},
				url:'./?type=htaccess&mode=save',
				success:function(result){
					if (result.status != 1){
						 _.dialog({
							'content':result.status_code
						});
					}else{
						window.location.reload();
					}
				}
			});
		});
	});
//-->
</script>