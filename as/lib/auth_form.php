<?php
/**
 * Administrator sign in template.
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
<h2><?php echo LOGIN_TIPS;?></h2>
	<input type="hidden" id="returnUrl" value="<?php echo $_SERVER["REQUIRE_URI"];?>"/>
	<dl><dt><?php echo ADMIN_ACCOUNT;?></dt><dd><input type="text" id="user"/></dd></dl>
	<dl><dt><?php echo ADMIN_PASSWORD;?></dt><dd><input type="password" id="passwd"/></dd></dl>
	<dl><dt></dt><dd><input type="checkbox" id="rememberme" value="1"/> <?php echo REMEMBER_ME;?>
	<input type="button" value="<?php echo LOGIN;?>" class="input_submit" /> <div id="signTip"></div></dd></dl>
	<div class="div_clear"></div>
	<div class="tr"><a href="./?type=password"><?php echo FORGOT_PASSWORD;?>?</a></div>
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
		_('#user').addClass('required');
		return ;
	}else{
		_('#user').removeClass('required');
	}
	if (!passwd){
		_('#passwd').addClass('required');
		return ;
	}else{
		_('#passwd').removeClass('required');
	}
	_('#signTip').html('<img src="../images/ajax-loader.gif">');
	var query = new Object();
	query.user = escape(user);
	query.passwd = passwd;
	query.rememberMe = rememberMe;
	_.ajax({
		'type':'POST',
		'data':query,
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
	include("lib/foot.php");	
?>