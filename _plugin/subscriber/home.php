<?php
/**
 * Home template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	define('PLUGIN_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	include(PLUGIN_DIR.'shareFunction.php');
	$plugin_mod = $_GET["plugin_mod"];
	$plugin_inc = null;
	switch($plugin_mod){
		case 'delete':
			$email = $_GET["email"];
			if($email){
				db_query("DELETE FROM `".DB_LEFT_PLUGIN."_maillist` WHERE `email` = '$email'");
				output_json(array('status'=>1));
			}
			output_json(array('status'=>0));
		break;
		case 'bulk_delete':
			$ids = implode('\',\'',explode(',',urldecode($_GET["ids"])));
			if(count($ids)){
				db_query("DELETE FROM `".DB_LEFT_PLUGIN."_maillist` WHERE `email` IN ('$ids')");
				output_json(array('status'=>1,'data'=>$ids));
			}
			output_json(array('status'=>0));
		break;
		case 'delete_body':
			$id = $_GET["id"];
			db_query("DELETE FROM `".DB_LEFT_PLUGIN."_mailbody` WHERE `id` = '$id'");
			_goto($_SERVER["HTTP_REFERER"]);
		break;
		case 'post':
			$plugin_inc = 'post.php';
		break;
		case 'postok':
			$subject = $_POST["subject"];
			$mail_html = $_POST["body"];
			$from = $_POST["from"]?$_POST["from"]:$global_setting['admin_email'];
			$mail_html = $mail_html;
			$mail_text = str_replace('\\','',$_POST["text_body"]);
			$mime_boundary = md5($global_setting['name'].time());
			if($subject && $mail_html){
				$ml = db_arrays("SELECT * FROM `".DB_LEFT_PLUGIN."_maillist`");
				foreach($ml AS $mls){
					my_post($mls['email'],$subject,$mail_text,$mail_html,$from ,$global_setting['name'],$mime_boundary);	
					$total += 1;
					$to .= $mls['email'].',';
					$content .= vsprintf(EMAIL_POST_STATUS,array($mls['email'])).'<br />';
				}
				$to = rtrim($to,',');			
				db_insert(DB_LEFT_PLUGIN."_mailbody",array('id',''),array('subject' ,'body' ,'text_body','date' ,'total' ,'to'),array($subject,$mail_html,$mail_text,time(),$total,$to));
			}
			alert($content);
		break;
		case 'list':
			$data = db_fetch(array(
				'table' => "`".DB_LEFT_PLUGIN."_maillist`",
				'field' => "*",
				'pager' => array('p_link'=>pluginDashboardUrl(THIS_PLUGIN,array('plugin_mod'=>'list')).'&','page_limit'=>30),
				'pager_function'=>'_pager'
			));
			$pager = $data['pager'];
			$ml = $data['rows'];
			$plugin_inc = 'maillist.php';
		break;
		case 'bodylist':
			$ml = db_arrays("SELECT * FROM `".DB_LEFT_PLUGIN."_mailbody`");
			$plugin_inc = 'bodylist.php';
		break;
		case 'viewbody':
			$id = intval($_GET["id"]);
			$mailbody = db_array("SELECT * FROM `".DB_LEFT_PLUGIN."_mailbody` WHERE `id` = '$id'");
			$plugin_inc = 'viewbody.php';
		break;
		case 'links':
			$data['id'] = $_POST['id'];
			$data['url'] = $_POST["url"];
			$data['reqs'] = array('action'=>'pluginHook','plugin'=>THIS_PLUGIN);
			$data['plugin'] = THIS_PLUGIN;
			$result = links_insert($data);
			_goto(pluginDashboardUrl(THIS_PLUGIN));
		break;
		default:
			$body_total = db_total("SELECT COUNT(*) FROM `".DB_LEFT_PLUGIN."_mailbody`");
			$mail_total = db_total("SELECT COUNT(*) FROM `".DB_LEFT_PLUGIN."_maillist`");			
			$linkRow = getLink(THIS_PLUGIN);
			$plugin_inc = 'main.php';
	}
	if($plugin_inc){
		$plugin_page[] = PLUGIN_DIR."inc/nav.php";
		$plugin_page[] = PLUGIN_DIR."inc/".$plugin_inc;
	}		
?>