<?php
/**
 * Reset password template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	include('./lib/head.php');
?>
<div id="div_center">
<div class="sign_form">
<h2><?php _e('Please input your administrator\'s email.');?></h2>
<fieldset><legend><?php _e('Email');?></legend>
<dd><input type="text" name="email" id="email"/></fieldset>
<input type="button" value="<?php _e('Done');?>"  class="input_submit"/> <a href="./"><?php _e('Login');?></a> <span id="tips"></span>
</div>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.input_submit').bind('click',function()  {
			var query = new Object();
			query.email = _('#email').val();
			if (!query.email){
				_('#email').addClass('required').run('focus');
				return ;
			}else{
				_('#email').removeClass('required');
			}
			_('#tips').html('<img src="../images/ajax-loader.gif">');
			_.ajax({
				type:'POST',
				data:query,
				url:'?type=password&mode=get',
				success:function(result){
						if (typeof(result) == 'object'){
							_('#tips').html(result.msg);
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