<?php
/**
 * Reset password template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	include("./lib/head.php");
?>
<div id="div_center">
<div class="sign_form">
<h2><?php echo TIP_INPUT_PASSWORD;?></h2>
	<dl><dt><?php echo EMAIL;?></dt><dd><input type="text" name="email" id="email" class="w_100"/></dd></dl>
	<dl><dt></dt><dd><input type="button" value="<?php echo DONE;?>"  class="input_submit"/> <a href="./"><?php echo LOGIN;?></a> <span id="tips"></span></dd></dl>
	<div class="div_clear"></div>
</div>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.input_submit').bind('click',function()  {
			var query = new Object();
			query.email = _('email').val();
			if (!query.email){
				_('email').addClass('required');
				return ;
			}else{
				_('email').removeClass('required');
			}
			ajaxd_post(
				query,
				'?type=password&mode=get',
				function(result){
						if (typeof(result) == 'object'){
							_('tips').html(result.msg);
						}
				}
			);
		});
	});
//-->
</script>
<?php
	include("lib/foot.php");	
?>