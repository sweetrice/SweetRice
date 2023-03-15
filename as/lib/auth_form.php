<?php
/**
 * Administrator sign in template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
include './lib/head.php';
?>
<div id="div_center">
<div class="sign_form">
<h2><?php _e('Please login');?></h2>
	<input type="hidden" id="returnUrl" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
	<fieldset><legend><?php _e('Account');?></legend>
	<input type="text" id="user"/></fieldset>
	<fieldset><legend><?php _e('Password');?></legend>
	<input type="password" id="passwd"/></fieldset>
	<input type="checkbox" id="rememberme" value="1"/> <?php _e('Remember Me');?>
	<input type="button" value="<?php _e('Login');?>" class="input_submit" /> <div id="signTip"></div>
	<div class="div_clear"></div>
	<div class="tr"><a href="./?type=password"><?php _e('Forgot Password');?>?</a></div>
</div>
</div>

<script type="text/javascript">
<!--
	_().ready(function(){
		_('#user').bind('keydown',function(event){
			event = event || window.event;
			if(event.keyCode == 13 && _('#user').val() && _('#passwd').val()){
				dashboardSignin();
			}
		});
		_('#passwd').bind('keydown',function(event){
			event = event || window.event;
			if(event.keyCode==13 && _('#user').val() && _('#passwd').val()){
				dashboardSignin();
			}
		});

		_('#rememberme').bind('keydown',function(event){
			event = event || window.event;
			if(event.keyCode==13 && _('#user').val() && _('#passwd').val()){
				dashboardSignin();
			}
		});

		_('.input_submit').bind('click',function()  {
			dashboardSignin();
		});
	});

function dashboardSignin(){
	var user = _('#user').val();
	var passwd = _('#passwd').val();
	var rememberMe = _('#rememberme').attr('checked');
	if (!user){
		_('#user').addClass('required').run('focus');
		return ;
	}else{
		_('#user').removeClass('required');
	}
	if (!passwd){
		_('#passwd').addClass('required').run('focus');
		return ;
	}else{
		_('#passwd').removeClass('required');
	}
	_('#signTip').html('<img src="../images/ajax-loader.gif">');
	_.ajax({
		'type':'POST',
		'data':{'user':user,'passwd':passwd,'rememberme':rememberme,'_tkv_':_('#_tkv_').attr('value')},
		'url':'./?type=signin',
		'success':function(result){
				if (typeof(result) == 'object'){
					_('#signTip').html(result['statusInfo']);
					if (result['status']==1){
						location.href = _('#returnUrl').val()?_('#returnUrl').val():'./';
					}
				}
		}
	});

}
//-->
</script>
<?php
include 'lib/foot.php';
?>