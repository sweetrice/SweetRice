<?php
/**
 * Reset password template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	include("lib/head.php");
?>
<div id="div_center">
<div class="sign_form">
<h2><?php echo TIP_RESET_PASSWORD;?></h2>
		<input type="hidden" name="r" value="<?php echo $r;?>"/>
		<dl><dt><?php echo ADMIN_EMAIL;?></dt><dd><input type="text" id="email" name="email" /></dd></dl>
		<dl><dt><?php echo PASSWORD;?></dt><dd><input type="password" id="p1" name="p1" /></dd></dl>
		<dl><dt><?php echo REPEAT_PASSWORD;?></dt><dd><input type="password" id="p2" name="p2" /></dd></dl>
		<dl><dt></dt><dd><input type="submit" class="submit_btn" value=" <?php echo DONE;?> " /> <span id="tips"></span></dd></dl>
<div class="div_clear"></div>
</div>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.submit_btn').bind('click',function()  {
			var query = new Object();
			query.r = '<?php echo $r;?>';
			query.email = _('email').val();
			if (!query.email){
				_('email').addClass('required');
				return ;
			}else{
				_('email').removeClass('required');
			}
			query.p1 = _('p1').val();
			if (!query.p1){
				_('p1').addClass('required');
				return ;
			}else{
				_('p1').removeClass('required');
			}
			query.p2 = _('p2').val();
			if (!query.p2 || query.p1 != query.p2){
				_('p2').addClass('required');
				return ;
			}else{
				_('p2').removeClass('required');
			}
			ajaxd_post(
				query,
				'./?type=password&mode=resetok',
				function(result){
						if (typeof(result) == 'object'){
							_('tips').html(result.msg);
							if (result.status == 1)
							{
								setTimeout(function(){location.href = './';},3000);
							}
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