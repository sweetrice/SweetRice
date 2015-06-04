<?php
/**
 * Template Name:Comment form template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
?>
<span id="action_tip"></span>
<input type="hidden" id="postID" value="<?php echo $row['id'];?>" />
<div id="comment_body">
<fieldset><legend><?php _e('Your name');?> 
<input type="text" id="name" value="<?php echo $_COOKIE['cname'];?>"/> * 
<input type="checkbox" id="remember" value="1" <?php echo $_COOKIE['cname']?'checked':''?>/> <?php _e('Remember Me');?> </legend>
<?php
	if($user_info['email']){
?>
<input type="hidden" id="email" value="<?php echo $user_info['email'];?>"/>
<?php
	}else{
?>
<label><?php _e('Your Email');?> <input type="text" id="email" value="<?php echo $_COOKIE['cemail'];?>"/> *</label>
<?php
	}
?>
<label><?php _e('Your Website');?> <input type="text" id="website" value="<?php echo $_COOKIE['cwebsite']?$_COOKIE['cwebsite']:'http://';?>"></label>
<div><textarea id="info" class="comment_text"></textarea></div>
<div><?php _e('Verification Code');?> 
<input type="text" id="code" size="6" maxlength="5"/> * <img id="captcha" src="images/captcha.png" align="absmiddle" title="Click to get"/> <input type="button" class="comment_button" value=" <?php _e('Leave Comment');?> "/></div>
</fieldset>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
	_('#code').bind('focus',function(){
		if(_('#captcha').attr('src').indexOf('captcha.png') != -1){
			_('#captcha').attr('src','images/captcha.php?timestamp='+new Date().getTime());
		}
	});
	_('#captcha').bind('click',function(){
		_(this).attr('src','images/captcha.php?timestamp='+new Date().getTime());
	});
	_('.comment_button').bind('click',function(){
		if (_(this).attr('_ing')){
			return ;
		}
		var name = _('#name').val();
		if(!name){
			_.ajax_untip('<?php _e('Please enter your name!');?>');
			_('#name').run('focus');
			return ;
		}
		var email =_('#email').val();
		if(!CheckEmail(email)){
			_.ajax_untip('<?php _e('Please enter a valid email!');?>');
			_('#email').run('focus');
			return ;
		}
		var website = _('#website').val();
		if(!website == 'http://'){
			website = '';
		}
		var code = _('#code').val();
		if(!code){
			_.ajax_untip('<?php _e('Please enter verification code!');?>');
			_('#code').run('focus');
			return ;
		}
		var info = _('#info').val();
		if(info == '' || info==null){
			_.ajax_untip('<?php _e('Please enter your comment!');?>');
			_('#info').run('focus');
			return ;
		}
		if (_('#remember').prop('checked')){
			var remember = 1;
		}else{
			var remember = 0;
		}

		var postID = _('#postID').val();
		_(this).attr('_ing',1);
		var query = new Object();
		query.email = escape(email);
		query.name = escape(name);
		query.website = escape(website);
		query.info = escape(info);
		query.postID = escape(postID);
		query.code = escape(code);
		query.remember = remember;
		var ajax_dlg = _.dialog({'content':'<img src="images/ajax-loader.gif">','name':'ajax_tip'});
		_.ajax({
			'type':'POST',
			'data':query,
			'url':'./?action=comment&mode=insert',
			'success':function(result){
					_('.comment_button').removeAttr('_ing');
					ajax_dlg.remove();
					if (typeof(result) == 'object'){
						switch (result['status']){
							case '0':
								_.ajax_untip(result['status_code']);
							break;
							case '1':
								_('#info').val('');
								_('#code').val('');
								_('#captcha').attr('src','images/captcha.png');
								_.ajax_untip(result['status_code']);
							break;
							default:
								_.ajax_untip('<?php _e('Sorry,connect error,please try later!')?>');
						}
					}
			}
		});
	});
	});
//-->
</script>