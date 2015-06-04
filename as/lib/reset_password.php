<?php
/**
 * Reset password template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	include('lib/head.php');
?>
<div id="div_center">
<div class="sign_form">
<h2><?php _e('Please reset your password.');?></h2>
		<input type="hidden" name="r" value="<?php echo $r;?>"/>
		<fieldset><legend><?php _e('Admin Email');?></legend>
		<input type="text" id="email" name="email"/></fieldset>
		<fieldset><legend><?php _e('Password');?></legend>
		<input type="password" id="p1" name="p1"/></fieldset>
		<fieldset><legend><?php _e('Repeat Password');?></legend>
		<input type="password" id="p2" name="p2"/></fieldset>
		<input type="submit" class="submit_btn" value=" <?php _e('Done');?> " />  <a href="./"><?php _e('Login');?></a> <span id="tips"></span>
</div>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.submit_btn').bind('click',function()  {
			var query = new Object();
			query.r = '<?php echo $r;?>';
			query.email = _('#email').val();
			if (!query.email){
				_('#email').addClass('required').run('focus');
				return ;
			}else{
				_('#email').removeClass('required');
			}
			query.p1 = _('#p1').val();
			if (!query.p1){
				_('#p1').addClass('required').run('focus');
				return ;
			}else{
				_('#p1').removeClass('required');
			}
			query.p2 = _('#p2').val();
			if (!query.p2 || query.p1 != query.p2){
				_('#p2').addClass('required').run('focus');
				return ;
			}else{
				_('#p2').removeClass('required');
			}
			_('#tips').html('<img src="../images/ajax-loader.gif">');
			_.ajax({
				type:'POST',
				data:query,
				url:'./?type=password&mode=resetok',
				success:function(result){
						if (typeof(result) == 'object'){
							_('#tips').html(result.msg);
							if (result.status == 1)
							{
								setTimeout(function(){location.href = './';},3000);
							}
						}
				}
			});
		});
	});
//-->
</script>
<?php
	include('lib/foot.php');	
?>