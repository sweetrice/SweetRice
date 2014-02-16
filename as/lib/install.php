<?php
/**
 * SweetRice install.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	$lang = $_SESSION["lang"]?$_SESSION["lang"]:dashboardLang();
	include("lang/".$lang);
	$action = $_GET["action"];
	$main_page = null;
	switch($action){
		case 'lang':
			if($_GET["lang"]){
				$_SESSION["lang"] = $_GET["lang"];
			}else{
				unset($_SESSION["lang"]);
			}
			_goto($_SERVER["HTTP_REFERER"]);
		break;
		case 'license':$main_page = 'license.php';
		break;
		case 'install':
			if(!is_writable(INCLUDE_DIR)){
				$message .= SITE_URL.INC_ISWRITE.'<br />';
			}
			if(!is_writable(ROOT_DIR)){
				$message .= SITE_URL.ROOT_ISWRITE.' <br />';
			}
			if(!is_writable(ROOT_DIR.'attachment')){
				$message .= SITE_URL.ATTACHMENT_ISWRITE.'<br />';
			}
			if(!is_writable('lib')){
				$message .= SITE_URL.LIBDIR_ISWRITE.'<br />';
			}
			$message .= $message?INSTALL_PERMISSIONS:'';
			$main_page = 'install_form.php';
		break;
		case 'ok':
			$database_type = $_POST["database_type"];
			define('DATABASE_TYPE',$database_type);
			define('DB_LEFT',$_POST["db_left"]);
			switch(DATABASE_TYPE){
				case 'sqlite':
					if($_POST["db_name"]){
						$dbname = INCLUDE_DIR.$_POST["db_name"].'.db';
						if(extension_loaded('pdo_sqlite')){
							$sqlite_driver = 'pdo_sqlite';
						}elseif(class_exists('SQLite3')){
							$sqlite_driver = 'sqlite3';
						}elseif(function_exists('sqlite_open')){
							$sqlite_driver = 'sqlite';
						}
						if(is_file($dbname)){
							unlink($dbname);
						}
						$db = sqlite_dbhandle($dbname);					
					}
					if(!$db){
						$error_db = true;
					}else{
						$sql = file_get_contents('lib/blog_sqlite.sql');
						preg_match_all('/CREATE\s+TABLE\s+"%--%_(.+)"\s+\(/i',$sql, $tables);
						foreach($tables[1] as $val){
							$val = trim($val);
							if($val){
								dropTable(DB_LEFT_PLUGIN.'_'.$val);
							}
						}
						$sql = str_replace('%--%',$_POST["db_left"],$sql);
						$sql = explode(';',$sql);
						foreach($sql as $key=>$val){
							if(trim($val)){
								$error = sqlite_dbquery($db,trim($val));
								if($error){
									$message .= $error.'<br>';
								}
							}
						}
						$db_str = "<?php \n";
						$db_str .= '$database_type = \''.$_POST["database_type"].'\';'."\n";
						$db_str .= '$db_left = \''.$_POST["db_left"].'\';'."\n";
						$db_str .= '$db_name = \''.$_POST["db_name"].'\';'."\n";
						$db_str .= '$sqlite_driver = \''.$sqlite_driver.'\';'."\n";
						$db_str .= "?>";
						file_put_contents('../inc/db.php',$db_str);
					}
				break;
				case 'pgsql':
					$conn  = pg_connect("host=".$_POST["db_url"]." port=".$_POST["db_port"]." dbname=".$_POST["db_name"]." user=".$_POST["db_username"]." password=".$_POST["db_passwd"]);
					if($conn){
						$sql = file_get_contents('lib/blog_pgsql.sql');
						$sql = str_replace('%--%',$_POST["db_left"],$sql);
						$sql = explode(';',$sql);
						foreach($sql as $key=>$val){
							if(trim($val)){
								if(!pg_query($val)){
									$message .= pg_last_error().'<br>';
								}
							}
						}
						$db_str = "<?php \n";
						$db_str .= '$database_type = \''.$_POST["database_type"].'\';'."\n";
						$db_str .= '$db_left = \''.$_POST["db_left"].'\';'."\n";
						$db_str .= '$db_url = \''.$_POST["db_url"].'\';'."\n";
						$db_str .= '$db_port = \''.$_POST["db_port"].'\';'."\n";
						$db_str .= '$db_name = \''.$_POST["db_name"].'\';'."\n";
						$db_str .= '$db_username = \''.$_POST["db_username"].'\';'."\n";
						$db_str .= '$db_passwd = \''.$_POST["db_passwd"].'\';'."\n";
						$db_str .= "?>";
						file_put_contents('../inc/db.php',$db_str);
					}else{
						$error_db = true;
					}
				break;
				default:
					$conn  = mysql_connect($_POST["db_url"],$_POST["db_username"],$_POST["db_passwd"]);
					if($conn &&	mysql_select_db($_POST["db_name"],$conn)){
					$sql = file_get_contents('lib/blog.sql');
					$sql = str_replace('%--%',$_POST["db_left"],$sql);
					$sql = explode(';',$sql);
					foreach($sql as $key=>$val){
						if(trim($val)){
							if(!mysql_query($val)){
								$message .= mysql_error().'<br>';
							}
						}
					}
					$db_str = "<?php \n";
					$db_str .= '$database_type = \''.$_POST["database_type"].'\';'."\n";
					$db_str .= '$db_left = \''.$_POST["db_left"].'\';'."\n";
					$db_str .= '$db_url = \''.$_POST["db_url"].'\';'."\n";
					$db_str .= '$db_port = \''.$_POST["db_port"].'\';'."\n";
					$db_str .= '$db_name = \''.$_POST["db_name"].'\';'."\n";
					$db_str .= '$db_username = \''.$_POST["db_username"].'\';'."\n";
					$db_str .= '$db_passwd = \''.$_POST["db_passwd"].'\';'."\n";
					$db_str .= "?>";
					file_put_contents('../inc/db.php',$db_str);
				}else{
					$error_db = true;
				}			
			}
			if(!$error_db&&!$message){
				$global_setting = serialize(array('name'=>escape_string($_POST["name"]) , 'author'=>escape_string($_POST["author"]) ,'title'=>escape_string($_POST["title"]) , 'keywords'=>escape_string($_POST["keyword"]) , 'description'=>escape_string($_POST["description"]) ,  'admin'=>$_POST["admin"] , 'passwd'=>md5($_POST["passwd"]),'close'=>1 ,'close_tip'=>'<p>Welcome to SweetRice - Thank your for install SweetRice as your website management system.</p><h1>This site is building now , please come late.</h1><p>If you are the webmaster,please go to Dashboard -> Setting -> General</p><p>and uncheck the checkbox "Site close" to open your website.</p><p>More help at <a href="http://www.basic-cms.org/docs/5-things-need-to-be-done-when-SweetRice-installed/">Tip for Basic CMS SweetRice installed</a></p>','cache'=>0,'cache_expired'=>0,'user_track'=>0,'url_rewrite'=>0,'logo'=>'','theme'=>'','lang'=>$lang,'admin_email'=>$_POST["admin_email"]));
				$setting_id = setOption('global_setting',db_escape($global_setting));
				if(!$setting_id){
					$message .= db_error().'<br />';
				}
				$categories_id = setOption('categories','');
				if(!$categories_id){
					$message .= db_error().'<br />';
				}
				$links_id = setOption('links','');
				if(!$links_id){
					$message .= db_error().'<br />';
				}
				if(!$message){
					file_put_contents('../inc/install.lock.php','<?php $installLock = \''.date('H:i:s m/d/Y').'\';?>');
					_goto('./');
				}else{
					$message = '<h1>'.DB_ERROR.'</h1>'.$message;
					$s_dtype[$_POST["database_type"]] = 'selected';
					$main_page = 'install_form.php';
				}
			}else{
				$message = '<h1>'.DB_ERROR.'</h1>'.$message;
				$s_dtype[$_POST["database_type"]] = 'selected';
				$main_page = 'install_form.php';
			}
		break;
		default:
			$langs = getLangTypes('lang/');
			$s_lang[$lang] = 'selected';
			$main_page = 'information.php'; 
	}
 $top_height = in_array($_COOKIE["top_height"],array('small','normal'))?$_COOKIE["top_height"]:'';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo SR_INSTALLER;?></title>
<link rel="stylesheet" type="text/css" href="site.css">
<script type="text/javascript" src="<?php echo SITE_URL;?>js/public.js"></script>
<script type="text/javascript" src="js/function.js"></script>
</head>
<body >
<div id="div_top">
<div id="top_image"><a href="<?php echo SITE_URL;?>" target="_blank"><img src="<?php echo SITE_URL;?>images/<?php echo $top_height!='normal'?'sweetrice.png':'sweetrice.jpg';?>" alt="SweetRice installer" id="logo"></a></div>
<div id="top_word">
<h1><?php echo 'Welcome to SweetRice!';?></h1>
</div>
<div class="div_clear"></div>
</div>
<div id="top_line" onclick="show_top('<?php echo $top_height;?>');">......</div>
<div id="div_center">
<div id="admin_left">
<div><?php echo DASHBOARD;?></div>
<div><a href="./?action=license"><?php echo INSTALL;?></a></div>
</div>
<div id="admin_right">
<?php	include('lib/'.$main_page);?>
</div>
<div class="div_clear"></div>
</div>
<?php include("./lib/foot.php");?>