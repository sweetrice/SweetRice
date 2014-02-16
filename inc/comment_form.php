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
<script type="text/javascript" src="js/comment_form.js"></script>
<span id="action_tip"></span>
<input type="hidden" id="postID" value="<?php echo $row['id'];?>" />
<div id="comment_body">
<fieldset><legend><?php echo YOUR_NAME;?> 
<?php
	$_REQUEST["uid"] = intval($_COOKIE["uid"]);
	if($_REQUEST["uid"]>0){
		$user_info = pluginApi('member','getMemberInfo',false);
	}
	if($user_info['account']){
?>
<input type="hidden" id="name" value="<?php echo $user_info['account'];?>"/><strong><?php echo $user_info['name']?$user_info['name']:$user_info['account'];?></strong>
<?php
	}else{
?>
<input type="text" id="name" value="<?php echo $_COOKIE["cname"];?>"/> * 
<?php
	}
?> <input type="checkbox" id="remember" value="1" <?php echo $_COOKIE["cname"]?'checked':''?>/> <?php echo REMEMBER_ME;?> </legend>
<?php
	if($user_info['email']){
?>
<input type="hidden" id="email" value="<?php echo $user_info['email'];?>"/>
<?php
	}else{
?>
<label><?php echo YOUR_EMAIL;?> <input type="text" id="email" value="<?php echo $_COOKIE["cemail"];?>"/> *</label>
<?php
	}
?>
<label><?php echo YOUR_WEBSITE;?> <input type="text" id="website" value="<?php echo $_COOKIE["cwebsite"]?$_COOKIE["cwebsite"]:'http://';?>"></label>
<div><textarea id="info" class="comment_text"></textarea></div>
<div><?php echo VERIFICATION_CODE;?> 
<input type="text" id="code" size="6" maxlength="5" onfocus='if($("captcha").src=="<?php echo BASE_URL;?>images/captcha.png"){$("captcha").src="images/captcha.php?timestamp="+new Date().getTime();}'/> * <img id="captcha" onclick="this.src='images/captcha.php?timestamp='+new Date().getTime();" src="images/captcha.png" align="absmiddle" title="Click to get"/> <input type="button" id="comment_button" value=" <?php echo LEAVE_COMMENT;?> "/></div>
</fieldset>
</div>
<script type="text/javascript">
<!--
	var cmt_tip_enter_name = '<?php echo CMT_TIP_ENTER_NAME;?>';
	var cmt_tip_enter_email = '<?php echo CMT_TIP_ENTER_EMAIL;?>';
	var cmt_tip_enter_code = '<?php echo CMT_TIP_ENTER_CODE;?>';
	var cmt_tip_enter_comment = '<?php echo CMT_TIP_ENTER_COMMENT;?>';
	var cmt_tip_noresponse = '<?php echo CMT_TIP_NORESPONSE;?>';
//-->
</script>