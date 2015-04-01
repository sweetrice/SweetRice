<?php
/**
 * Password management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 if(dashboard_signin()){
	 _goto('./');
 }
	$mode = $_GET['mode'];
	switch($mode){
		case 'get':
			$email = $_POST['email'];
			if($email == $global_setting['admin_email'] && $email){
				$rand_code = md5(time());
				$content = serialize(array('rand_code'=>$rand_code,'ip'=>$_SERVER['REMOTE_ADDR'],'date'=>time()));
				setOption('getpassword',$content);
				$mail_text = vsprintf(_t('
Hi %s,
Please click the links below to reset your password for SweetRice:
%s%s/?type=password&mode=re&r=%s
if this is not your request,just remove the email.



Goodluck!
SweetRice
				'),array($global_setting['author'],BASE_URL,DASHBOARD_DIR,$rand_code));
				$mail_html = vsprintf(_t('
Hi %s,<br />
Please click the links below to reset your password for SweetRice:<br />
<a href="%s%s/?type=password&mode=re&r=%s">%s%s/?type=password&mode=re&r=%s</a><br />
if this is not your request,just remove the email.<br />
<br />
<br />
<br />
Goodluck!<br />
SweetRice
				'),array($global_setting['author'],BASE_URL,DASHBOARD_DIR,$rand_code,BASE_URL,DASHBOARD_DIR,$rand_code));
				$result = my_post($global_setting['admin_email'],_t('Please reset your password.'),$mail_text,$mail_html,'noreply@'.$_SERVER['HTTP_HOST'],_t('SweetRice Dashboard'),$global_setting['name'].md5(time()),'UTF-8');
				output_json(array('status'=>$result,'msg'=>$result?_t('Please visit your email,and reset your passowrd.'):_t('Notice email sent failed')));
			}else{
				output_json(array('status'=>0,'msg'=>_t('Wrong email.')));
			}
		break;
		case 're':
			$r = $_GET['r'];
			$row = getOption('getpassword');
			$content = array();
			if($row['content']){
				$content = unserialize($row['content']);
			}
			if($r == $content['rand_code'] && $content['rand_code']){
				include('lib/reset_password.php');
				exit();
			}else{
				alert(_t('Wrong secret code.'),'./');
			}
		break;
		case 'resetok':
			$content = array();
			$row = getOption('getpassword');
			if($row['content']){
				$content = unserialize($row['content']);
			}
			$rand_code = $content['rand_code'];
			if($_POST['p1'] == $_POST['p2'] && $_POST['p1'] && $global_setting['admin_email'] == $_POST['email'] && $global_setting['admin_email'] && $rand_code == $_POST['r']){
				$row = getOption('global_setting');
				if($row['content']){
					$row = unserialize($row['content']);
				}
				foreach($row as $key=>$val){
					if($key == 'passwd'){
						$data[$key] = md5($_POST['p1']);
					}else{
						$data[$key] = $val;
					}
				}
				setOption('global_setting',serialize($data));
				$mail_text = vsprintf(_t('
Hi,%s :
Your password has been reset succesfully,please login your dashboard and manage your website.
%s%s/


Goodluck!
SweetRice	
				'),array($global_setting['author'],BASE_URL,DASHBOARD_DIR));
				$mail_html = vsprintf(_t('
Hi,%s :<br />
Your password has been reset succesfully,please login your dashboard and manage your website.<br />
<a href="%s%s/">%s%s/</a><br />
<br />
<br />
Goodluck!<br />
SweetRice	
				'),array($global_setting['author'],BASE_URL,DASHBOARD_DIR,BASE_URL,DASHBOARD_DIR));
				$result = my_post($global_setting['admin_email'],_t('Your password has been reset succesfully.'),$mail_text,$mail_html,'noreply@'.$_SERVER['HTTP_HOST'],'SweetRice Dashboard',$global_setting['name'].md5(time())	,'UTF-8');
				output_json(array('status'=>$result,'msg'=> $result ? _t('Your password has been reset succesfully.'):_t('Notice email sent failed')));
			}else{			
				output_json(array('status'=>0,'msg'=>_t('Invalid password or Email.')));
			}
		break;
		default:
			include('lib/forgot_password.php');
			exit();
	}
 ?>