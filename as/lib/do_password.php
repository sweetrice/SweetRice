<?php
/**
 * Password management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 if(dashboard_signin()){_goto('./');}
	$mode = $_GET["mode"];
	if($mode=='get'){
		$email = $_POST["email"];
		if($email==$global_setting['admin_email']&&$email){
			$rand_code = md5(time());
			$content = serialize(array('rand_code'=>$rand_code,'ip'=>getenv("REMOTE_ADDR"),'date'=>time()));
			setOption('getpassword',$content);
			$mail_text = vsprintf(MAIL_TEXT_RESET_PASSWORD,array($global_setting['author'],BASE_URL,DASHBOARD_DIR,$rand_code));
			$mail_html = vsprintf(MAIL_HTML_RESET_PASSWORD,array($global_setting['author'],BASE_URL,DASHBOARD_DIR,$rand_code,BASE_URL,DASHBOARD_DIR,$rand_code));

			my_post($global_setting['admin_email'],TIP_RESET_PASSWORD,$mail_text,$mail_html,'noreply@'.$_SERVER["HTTP_HOST"],'SweetRice Dashboard',$global_setting['name'].md5(time()),'UTF-8');
			output_json(array('status'=>1,'msg'=>TIP_VISIT_EMAIL));
		}else{
			output_json(array('status'=>0,'msg'=>TIP_WRONG_EMAIL));
		}
	}elseif($mode=='re'){
		$r = $_GET["r"];
		$row = getOption('getpassword');
		$content = array();
		if($row['content']){
			$content = unserialize($row['content']);
		}
		if($r == $content['rand_code']&&$content['rand_code']){
			include('lib/reset_password.php');
			exit();
		}else{
			alert(TIP_WRONG_SECRET_CODE,'./');
		}

	}elseif($mode=='resetok'){
		$content = array();
		$row = getOption('getpassword');
		if($row['content']){
			$content = unserialize($row['content']);
		}
		$rand_code = $content['rand_code'];
		if($_POST["p1"]==$_POST["p2"]&&$_POST["p1"]&&$global_setting['admin_email']==$_POST["email"]&&$global_setting['admin_email']&&$rand_code==$_POST["r"]){
			$row = getOption('global_setting');
			if($row['content']){
				$row = unserialize($row['content']);
			}
			foreach($row as $key=>$val){
				if($key=='passwd'){
					$data[$key] = md5($_POST["p1"]);
				}else{
					$data[$key] = $val;
				}
			}
			setOption('global_setting',db_escape(serialize($data)));
			$mail_text = vsprintf(MAIL_TEXT_NOTICE_RESET_PASSWORD_OK,array($global_setting['author'],BASE_URL,DASHBOARD_DIR));
			$mail_html = vsprintf(MAIL_HTML_NOTICE_RESET_PASSWORD_OK,array($global_setting['author'],BASE_URL,DASHBOARD_DIR,BASE_URL,DASHBOARD_DIR));
			my_post($global_setting['admin_email'],TIP_RESET_PASSWORD_OK,$mail_text,$mail_html,'noreply@'.$_SERVER["HTTP_HOST"],'SweetRice Dashboard',$global_setting['name'].md5(time())	,'UTF-8');
			output_json(array('status'=>1,'msg'=>TIP_RESET_PASSWORD_OK));
		}else{			
			output_json(array('status'=>0,'msg'=>TIP_INVALID_RESET_PASSWORD));
		}
	}
	include('lib/forgot_password.php');
	exit();
 ?>