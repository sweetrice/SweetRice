<?php
/**
 * Subscriber.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
	define('PLUGIN_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	include(PLUGIN_DIR.'shareFunction.php');
	$plugin_mod = $_GET["plugin_mod"];
	switch($plugin_mod){
		case 'subscriber':
			$email = js_unescape($_POST["email"]);
			if(!checkemail($email)){output_json(array('status'=>1,'status_code'=>INVALID_EMAIL));}
			$total = db_total("SELECT COUNT(*) FROM `".DB_LEFT_PLUGIN."_maillist` WHERE `email` = '$email'");
			if($total!=0){
				output_json(array('status'=>1,'status_code'=>EMAIL_EXISTS));
			}
			$regDate = time();
			$res = db_insert(DB_LEFT_PLUGIN."_maillist",array(false,false),array('email','date'),array($email,$regDate),true);
			if($res){
				$unsubscriber_link = BASE_URL.pluginHookUrl(THIS_PLUGIN,array('plugin_mod'=>'unsubscriber','email'=>$email,'t'=>$regDate));
				$subject = vsprintf(SUBSCRIBER_SUBJECT,array($global_setting['name']));
				$mail_html = vsprintf(SUBSCRIBER_HTMLBODY,array($global_setting['name'],$global_setting['name'],$unsubscriber_link,$unsubscriber_link,$global_setting['name']));;
				$from = $global_setting['admin_email'];
				$mail_text = vsprintf(SUBSCRIBER_TEXTBODY,array($global_setting['name'],$global_setting['name'],$unsubscriber_link,$global_setting['name']));
				$mime_boundary = $global_setting['name'].md5(time());	
				my_post($email,$subject,$mail_text,$mail_html,$from ,$global_setting['name'],$mime_boundary);	
				output_json(array('status'=>1,'status_code'=>SUBSCRIBER_SUCCESS));
			}else{
				output_json(array('status'=>0,'status_code'=>SUBSCRIBER_FAILED));
			}
		break;
		case 'unsubscriber':
			$email = $_GET["email"];
			$t = intval($_GET["t"]);
			if(!checkemail($email)||$t<=0){
				alert(INVALID_SUBSCRIBER,BASE_URL);
			}
			$total = db_total("SELECT COUNT(*) FROM `".DB_LEFT_PLUGIN."_maillist` WHERE `email` = '$email' AND `date` = '$t'");
			if($total==0){
				alert(UNSUBSCRIBER_EMAIL,BASE_URL);
			}
			$res = db_query("DELETE FROM `".DB_LEFT_PLUGIN."_maillist` WHERE `email` = '$email' AND `date` = '$t'");
			if(!$res){
				$result['status'] = 1;
				alert(UNSUBSCRIBER_SUCCESS,BASE_URL);
			}else{
				alert(UNSUBSCRIBER_FAILED.':'.db_error(),BASE_URL);
			}
		break;
		default:
			$title = SUBSCRIBER.' - '.$global_setting['name'];
			$description = SUBSCRIBER.' - '.$global_setting['name'];
			$keywords = SUBSCRIBER;
			$inc = PLUGIN_DIR.'inc/subscriber.php';
	}
?>