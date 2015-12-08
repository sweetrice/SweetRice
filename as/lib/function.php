<?php	
/**
 * All function for dashboard.
 *
 * @package SweetRice
 * @SweetRice core.
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
function sweetrice_version(){
	$lastest = trim(get_data_from_url('http://www.basic-cms.org/lastest.html'));
	return $lastest;
}

function update_automatically($upgrade_dir){
	eval("\$str = '<p>"._t('Update')." SweetRice</p>';");
	$content = get_data_from_url('http://www.basic-cms.org/download/17/');
	if($content){
		file_put_contents(ROOT_DIR.'SweetRice_core.zip',$content);
		eval("\$str .= '<p>"._t('Download')." SweetRice_core.zip ("._t('File size:')." ".filesize(ROOT_DIR.'SweetRice_core.zip').") "._t('successfully')."</p>';");
	}else{
		eval("\$str .= '<p>"._t('Update failed - cannot connect update server.')."</p>';");
		return $str;
	}
	if(!file_exists(ROOT_DIR.$upgrade_dir)){
		mkdir(ROOT_DIR.$upgrade_dir);
	}
	if(extractZIP(ROOT_DIR.'SweetRice_core.zip',ROOT_DIR.$upgrade_dir.'/')){
		eval("\$str .= '<p>"._t('Extract')." SweetRice_core.zip "._t('successfully')."</p>';");
	}else{
		eval("\$str .= '<p>"._t('Extract').". SweetRice_core.zip "._t('Failed')."</p>';");
		return $str;
	}
	$sweetrice_files = sweetrice_files(ROOT_DIR.$upgrade_dir.'/');
	foreach($sweetrice_files as $val){
		$target_entry = str_replace(ROOT_DIR.$upgrade_dir.'/',ROOT_DIR,$val);
		$target_entry = str_replace(ROOT_DIR.'as/',ROOT_DIR.DASHBOARD_DIR.'/',$target_entry);
		if($target_entry == ROOT_DIR.'as'){
			$target_entry = ROOT_DIR.DASHBOARD_DIR;
		}
		if(is_dir($val)){
			if(!is_dir($target_entry)&&!mkdir($target_entry)){
				eval("\$str .= '<p>"._t('Update')." SweetRice "._t('Files')." "._t('Aborted')."</p>';");
				return $str;
			}
		}else{
			if(is_file($target_entry)){
				if(md5_file($val) != md5_file($target_entry)&&!copy($val,$target_entry)){
					eval("\$str .= '<p>"._t('Update')." SweetRice "._t('Files')." "._t('Aborted')."</p>';");
					return $str;
				}
			}else{
				if(!copy($val,$target_entry)){
					eval("\$str .= '<p>"._t('Update')." SweetRice "._t('Files')." "._t('Aborted')."</p>';");
					return $str;
				}
			}
		}
	}
	eval("\$str .= '<p>"._t('Update')." SweetRice "._t('Files')." "._t('successfully')."</p>';");
	if(file_exists(ROOT_DIR.$upgrade_dir.'/upgrade_db.php')){
		if(!file_exists(ROOT_DIR.'upgrade_db.php')){
			copy(ROOT_DIR.$upgrade_dir.'/upgrade_db.php',ROOT_DIR.'/upgrade_db.php');
		}
		$upgrade_db = get_data_from_url(BASE_URL.'upgrade_db.php');
		if($upgrade_db == 'Successfully'){
			eval("\$str .= '<p>"._t('Database')." "._t('Upgrade')." "._t('successfully')."</p>';");
			if(file_exists(ROOT_DIR.'upgrade_db.php')){
				unlink(ROOT_DIR.'upgrade_db.php');
			}
		}else{
			eval("\$str .= '<p>"._t('Database')." "._t('Upgrade')." "._t('Failed')." </p><p>$upgrade_db</p>';");
		}
	}
	if(file_exists(ROOT_DIR.'inc/lastest_update.txt')){
		rename(ROOT_DIR.'inc/lastest_update.txt',ROOT_DIR.'inc/lastest.txt');
		$lastest = file_get_contents(ROOT_DIR.'inc/lastest.txt');
	}else{
		$lastest = sweetrice_version();
		file_put_contents(ROOT_DIR.'inc/lastest.txt',$lastest);
	}
	if(un_(ROOT_DIR.$upgrade_dir.'/')&&unlink(ROOT_DIR.'SweetRice_core.zip')){
		eval("\$str .= '<p>"._t('Clean')." "._t('temporary')." "._t('Files')." "._t('successfully')."</p>';");
	}else{
		eval("\$str .= '<p>"._t('Clean')." "._t('temporary')." "._t('Files')." "._t('Failed')."</p>';");
		return $str;
	}
	eval("\$str .= '<p>"._t('Upgrade')." SweetRice to $lastest "._t('successfully')."</p>';");
	return $str;
}
function un_($_dir,$_rmdir = true){
	if(!is_dir($_dir)){
		return true;
	}
	if(substr($_dir,-1) != '/'){
		$_dir .= '/';
	}
	$d = dir($_dir);
	while (false !== ($entry = $d->read())) {
		if($entry!='.'&&$entry!='..'){
			if(is_dir($_dir.$entry)){
				un_($_dir.$entry.'/');
			}else{
				unlink($_dir.$entry);
			}
		} 
	}
	$d->close();
	if($_rmdir){
		rmdir(substr($_dir,0,-1));
	}
	return true;
}

function copyFiles($source,$destination){
	if(!is_dir($source)){
		return false;
	}
	if(!is_dir($destination)){
		mkdir($destination);
	}
	$d = dir($source);
	while($entry = $d->read()) {
		if($entry != '.' && $entry != '..' ){
			if(is_dir($source.'/'.$entry)){
				copyFiles($source.'/'.$entry,$destination.'/'.$entry);
			}else{
				copy($source.'/'.$entry,$destination.'/'.$entry);
			}
		}
	}
	$d->close();
	return true;
}

function extractZIP($file_name,$dest_dir){
	if(substr($dest_dir,-1) != '/'){
		$dest_dir .= '/';
	}
	$data = array();
	if((extension_loaded('zlib')||extension_loaded('ZZIPlib')) && 1>1){
		$zip = zip_open($file_name);
		if (is_resource($zip)){
			while ($zip_entry = zip_read($zip)) {
				if (zip_entry_open($zip, $zip_entry, 'r')) {
					$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
					if(substr(zip_entry_name($zip_entry),-1)=='/'){
						if(!file_exists($dest_dir.zip_entry_name($zip_entry))){
							mkdir($dest_dir.zip_entry_name($zip_entry));
						}
					}else{
						$handle = fopen($dest_dir.zip_entry_name($zip_entry),'wb');
						fwrite($handle,$buf);
						fclose($handle);
						$data[] = $dest_dir.zip_entry_name($zip_entry);
					}
					zip_entry_close($zip_entry);
				}
			}
			zip_close($zip);
		}
	}else{
		$zip = new ZipArchive();
		if ($zip->open($file_name) === TRUE) {
			$temp_dir = 'temp'.time().rand(1000,9999);
			$zip->extractTo($dest_dir.$temp_dir.'/');
			$zip->close();
			$_data = sweetrice_files($dest_dir.$temp_dir.'/');
			foreach($_data as $val){
				rename($val,$dest_dir.substr($val,strlen($dest_dir.$temp_dir.'/')));
				$data[] = $dest_dir.substr($val,strlen($dest_dir.$temp_dir.'/'));
			}
			rmdir($dest_dir.$temp_dir);
		}
	}
	return count($data)?$data:false;
}

function get_template($theme_dir,$type){
		$theme_config = file($theme_dir.'theme.config');
		foreach($theme_config as $val){
			if(trim($val)){
				$tmp = explode('|',$val);
				$templates[trim($tmp[1])] = $tmp[0];
			}
		}
		$d = dir($theme_dir);
		while (false !== ($entry = $d->read())) {
			 if($entry!='.'&&$entry!='..'&&$entry!='theme.config'&&!is_dir($theme_dir.$entry)){
				$str = file_get_contents($theme_dir.$entry);
				$pos_no = strpos($str,' * '.$type.' Template:');
				if($pos_no!==false){
					$tmp = substr($str,$pos_no+strlen(' * '.$type.' Template:'));
					$tmp = trim(substr($tmp,0,strpos($tmp,'*')));
					$template[$templates[$entry]?'default':substr($theme_dir.$entry,strlen(SITE_HOME))] = $tmp;
				}
			 }
		}
		$d->close();
		return $template;
	}

	function get_AllTemplate($theme_dir){
		$theme_config = file($theme_dir.'theme.config');
		foreach($theme_config as $val){
			if(trim($val)){
				$tmp = explode('|',$val);
				$templates[substr($theme_dir,strlen(SITE_HOME)).trim($tmp[1])] = $tmp[0];
			}
		}
		$d = dir($theme_dir);
		while (false !== ($entry = $d->read())) {
			 if($entry!='.'&&$entry!='..'&&$entry!='theme.config'&&!is_dir($theme_dir.$entry)&&!$templates[$theme_dir.$entry]){
				$str = file_get_contents($theme_dir.$entry,null,null,0,200);
				$is_template = preg_match('/\s\*\s*[\sa-zA-Z0-9\-\_]*Template\s*(Name)?:(.+)/i',$str,$matches);
				if($is_template){
					$templates[substr($theme_dir,strlen(SITE_HOME)).$entry] = trim($matches[2]);
				}
			 }
		}
		$d->close();
		$templates = array_flip($templates);
		return $templates;
	}

	function sr_file_type($filename){
		$filetypes = array('GIF'=>'image/gif','JPEG'=>'image/jpge','JPG'=>'image/jpeg','PNG'=>'image/png','SWF'=>'application/x-shockwave-flash','PSD'=>'image/psd','BMP'=>'image/bmp','TIFF'=>'image/tiff','JPC'=>'application/octet-stream','JP2'=>'image/jp2','JPX'=>'application/octet-stream','JB2'=>'application/octet-stream','SWC'=>'application/x-shockwave-flash','IFF'=>'image/iff','WBMP'=>'image/vnd.wap.wbmp','XBM'=>'image/xbm','MP4'=>'video/mp4','WEBM'=>'video/webm','OGG'=>'video/ogg','MP3'=>'audio/mpeg','WAV'=>'audio/wav');
		$exts = explode('.',$filename);
		if(is_array($exts)){
			$file_ext = strtoupper(end($exts));
		}
		if($filetypes[$file_ext]){
			return $filetypes[$file_ext];
		}else{
			return 'application/octet-streams';
		}
	}

	function completeInsert($yes_url,$no_url,$tip){
		if(!$tip){
			$tip = _t('Create another one?');
		}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head><script type="text/javascript">
<!--
	if(confirm('<?php echo $tip;?>')){
		location.href = '<?php echo $yes_url;?>';
	}else{
		location.href = '<?php echo $no_url;?>';
	}
//-->
</script>
<body>
</body>
</html>
<?php
	exit();
	}
	function sweetrice_files($_dir){
		if(substr($_dir,-1) != '/'){
			$_dir .= '/';
		}
		$filelist = array();
		$d = dir($_dir);
		while (false !== ($entry = $d->read())) {
		if($entry!='.'&&$entry!='..'){
			$filelist[] = $_dir.$entry;
			if(is_dir($_dir.$entry)){
				$filelist = array_merge ($filelist,sweetrice_files($_dir.$entry.'/'));
			}
		}
		}
		$d->close();
		return $filelist;
	}

	function dashboard_signin(){
		global $global_setting;
		if(!$global_setting){
			return false;
		}
		switch($global_setting['admin_priority']){
			case 1:
				return signin_as_sweetrice();
			break;
			case 2:
				return signin_as_member_plugin()?true:signin_as_sweetrice();
			break;
			case 3:
				return signin_as_member_plugin();	
			break;
			default:
				return signin_as_sweetrice()?true:signin_as_member_plugin();	
		}
	}
	
	function signin_as_sweetrice($data = array()){
		global $global_setting;
		if(!$global_setting){
			return false;
		}
		if($_COOKIE['admin'] == $global_setting['admin'] && $_COOKIE['passwd'] == $global_setting['passwd']){
			return true;
		}
		if(!$data){
			$data = $_POST;
		}
		if($data['user'] == $global_setting['admin'] && md5($data['passwd']) == $global_setting['passwd'] ){
			$login_expired = null;
			if($data['rememberme']){
				$login_expired = time()+31536000;
			}
			setcookie('admin',$global_setting['admin'],$login_expired,BASE_DIR.DASHBOARD_DIR.'/');
			setcookie('passwd',$global_setting['passwd'],$login_expired,BASE_DIR.DASHBOARD_DIR.'/');
			return true;
		}
		return false;
	}

	function signin_as_member_plugin(){
		$orow = getOption('pluginWithDashboard');
		if($orow['content']){
			$orow = unserialize($orow['content']);
			if($orow['plugin']){
				$pluginCookie = $orow['pluginCookie'];
				$pluginSession = $orow['pluginSession'];
				if(!$pluginSession && !$pluginCookie){
					return false;
				}
				if(is_array($pluginCookie)){
					foreach($pluginCookie as $key=>$val){
						if(!preg_match($val,$_COOKIE[$key])){
							return false;
						}
					}
				}
				if(is_array($pluginSession)){
					foreach($pluginSession as $key=>$val){
						if(!preg_match($val,$_SESSION[$key])){
							return false;
						}
					}
				}
				if($orow['dashboard_role']){
					$_SESSION['dashaboard_role'] = clean_quotes($_COOKIE[$orow['dashboard_role']]);
				}
				$acts = unserialize(stripslashes($_COOKIE['acts']));
				if(!in_array('dashboard',$acts)){
					return false;
				}
				return true;
			}
		}
		return false;
	}

	function dashboardSignin(){
		if(dashboard_signin()){
			output_json(array('status'=>1,'statusInfo'=>_t('Login success')));
		}else{
			output_json(array('status'=>0,'statusInfo'=>_t('Login failed')));
		}
	}

	function dashboard_signout(){
		if($_COOKIE['admin'] && $_COOKIE['passwd']){
			setcookie('admin','',time()-60,BASE_DIR.DASHBOARD_DIR.'/');
			setcookie('passwd','',time()-60,BASE_DIR.DASHBOARD_DIR.'/');
		}else{
			$orow = getOption('pluginWithDashboard');
			if($orow['content']){
				$orow = unserialize($orow['content']);
				if($orow['plugin']){
					$pluginCookie = $orow['pluginCookie'];
					$pluginSession = $orow['pluginSession'];
					if(!$pluginSession && !$pluginCookie){
						return ;
					}
					$cookie_path = str_replace(DASHBOARD_DIR.'/','',str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/'));
					if(is_array($pluginCookie)){
						foreach($pluginCookie as $key=>$val){
							setcookie($key,'',time()-60,$cookie_path);
						}
					}
					if(is_array($pluginSession)){
						foreach($pluginSession as $key=>$val){
							unset($_SESSION[$key]);
						}
					}
					_goto(BASE_URL.pluginHookUrl($orow['plugin']));
				}
			}
		}
		_goto('./');		
	}

	function dashboard_role($role,$mustBase=false){
		$dashboard_role = $_SESSION['dashaboard_role']?unserialize($_SESSION['dashaboard_role']):array();
		if($mustBase && BASE_URL!=SITE_URL){
			return false;
		}
		return in_array($role,$dashboard_role)||isAdmin();
	}

	function isAdmin(){
		global $global_setting;
		return $_COOKIE['admin'] == $global_setting['admin'];
	}


	function _pager($total,$page_limit,$p_link,$curr_page = 1,$source_url = false){
		$page = intval($_GET['p']);
		if($page == 0){
			$page = $curr_page > 0 ? $curr_page:1;
		}
		if(!$page_limit){
			$page_limit = 30;
		}
		$page_start = ($page-1)*$page_limit;
		$page_total = ceil($total/$page_limit);		
		$list_put = '';
		if ($page == 1 && $page_total>1) {
			$list_put='<a href="'.$p_link.'p=1" class="pageCurrent" >1</a> <a href="'.$p_link.'p='.($page_total).'">'.$page_total.'</a>   <a href="'.$p_link.'p='.($page+1).'">'._t('Next').'&raquo; </a>';
		} elseif($page == $page_total && $page_total>1) {
			 $list_put='<a href="'.$p_link.'p='.($page-1).'" >&laquo;'._t('Previous').'</a> <a href="'.$p_link.'p=1">1</a> <a href="'.$p_link.'p='.($page_total).'" class="pageCurrent">'.$page_total.'</a>';
		} elseif ($page > 1 && $page < $page_total) {
			 $list_put='<a href="'.$p_link.'p='.($page-1).'" >&laquo;'._t('Previous').'</a> <a href="'.$p_link.'p=1">1</a><a href="'.$p_link.'p='.$page.'" class="pageCurrent">'.$page.'</a><a href="'.$p_link.'p='.$page_total.'">'.$page_total.'</a> <a href="'.$p_link.'p='.($page+1).'">'._t('Next').'&raquo; </a>';
		}
		$list_put = '<div class="PageList">'.($list_put?$list_put.' ':'')._t('Page Limit').': <input type="text" class="page_limit" value="'.$_COOKIE['page_limit'].'"/> <input type="button" value="'._t('Done').'" class="btn_limit"></div><script type="text/javascript">_(\'.page_limit\').bind(\'keydown\',function(event){event = event || window.event;if(event.keyCode == 13){_(this).parent().find(\'.btn_limit\').run(\'click\');}});_(\'.btn_limit\').bind(\'click\',function(){_.setCookie({name:\'page_limit\',value:_(this).parent().find(\'.page_limit\').val()});location.href="'.$p_link.'";});</script>';
		return array('page_start'=>$page_start,'list_put'=>$list_put);
	}

	function initSiteDB(){
		global $db,$global_setting;
		$site_config = $_POST['site_config'];
		$error_db = $message = null;
		$host = $_POST['host'];
		if(!$host){
			return array('message'=>_t('Host name is required'));
		}
		if($host){
			if(!is_dir(ROOT_DIR.'_sites/'.$host)){
				mkdir(ROOT_DIR.'_sites/'.$host);
			}elseif(file_exists(ROOT_DIR.'_sites/'.$host.'/inc/db.php')){
				return array('error_db'=>$error_db?_t('Host name exists'):'','message'=>$message);
			}
			$site_root = ROOT_DIR.'_sites/'.$host.'/';
			if(!is_dir(ROOT_DIR.'_sites/'.$host.'/inc')){
				mkdir(ROOT_DIR.'_sites/'.$host.'/inc');
			}
			if($_POST['attachment_type'] == 1 && $_POST['attachment_dir']){
				if(!is_dir($site_root.$_POST['attachment_dir'])){
					mkdir($site_root.$_POST['attachment_dir']);
				}
				$tmp = '<?php'."\n";
				$tmp .= '$attachment_dir = \''.$_POST['attachment_dir'].'\';'."\n";
				$tmp .= '?>';
				file_put_contents($site_root.'inc/site_config.php',$tmp);
			}
			$themes = $_POST['themes'];
			if(is_array($themes)){
				if(!is_dir($site_root.'_themes')){
					mkdir($site_root.'_themes');
				}
				foreach($themes as $val){
					copyFiles(ROOT_DIR.'_themes/'.$val,$site_root.'_themes/'.$val);
				}
			}
			$plugins = $_POST['plugins'];
			if(is_array($plugins)){
				if(!is_dir($site_root.'_plugin')){
					mkdir($site_root.'_plugin');
				}
				foreach($plugins as $val){
					copyFiles(ROOT_DIR.'_plugin/'.$val,$site_root.'_plugin/'.$val);
				}
			}
		}
		$db_type = $site_config['db_type'];
		switch($db_type){
			case 'sqlite':
				if($site_config['db_name']){
					$dbname = $site_root.'inc/'.$site_config['db_name'].'.db';
					if(extension_loaded('pdo_sqlite')){
						$sqlite_driver = 'pdo_sqlite';
					}else{
						$sqlite_driver = 'sqlite';
					}
					if(file_exists($dbname)){
						unlink($dbname);
					}
					$db = sqlite_dbhandle($dbname);
				}
				if(!$db){
					$error_db = true;
				}else{
					$sql = file_get_contents('./lib/app_sqlite.sql');
					$sql = str_replace('%--%',$site_config['db_left'],$sql);
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
					$db_str .= '$database_type = \''.$site_config['db_type'].'\';'."\n";
					$db_str .= '$db_left = \''.$site_config['db_left'].'\';'."\n";
					$db_str .= '$db_name = \''.$site_config['db_name'].'\';'."\n";
					$db_str .= '$sqlite_driver = \''.$sqlite_driver.'\';'."\n";
					$db_str .= '?>';
					file_put_contents($site_root.'inc/db.php',$db_str);
				}
			break;
			case 'pgsql':
				$conn  = pg_connect('host='.$site_config['db_url'].' port='.$site_config['db_port'].' dbname='.$site_config['db_name'].' user='.$site_config['db_username'].' password='.$site_config['db_passwd']);
				if($conn){
					$sql = file_get_contents('./lib/app_pgsql.sql');
					$sql = str_replace('%--%',$site_config['db_left'],$sql);
					$sql = explode(';',$sql);
					foreach($sql as $key=>$val){
						if(trim($val)){
							if(!pg_query($val)){
								$message .= pg_last_error().'<br>';
							}
						}
					}
					$db_str = "<?php \n";
					$db_str .= '$database_type = \''.$site_config['db_type'].'\';'."\n";
					$db_str .= '$db_left = \''.$site_config['db_left'].'\';'."\n";
					$db_str .= '$db_url = \''.$site_config['db_url'].'\';'."\n";
					$db_str .= '$db_port = \''.$site_config['db_port'].'\';'."\n";
					$db_str .= '$db_name = \''.$site_config['db_name'].'\';'."\n";
					$db_str .= '$db_username = \''.$site_config['db_username'].'\';'."\n";
					$db_str .= '$db_passwd = \''.$site_config['db_passwd'].'\';'."\n";
					$db_str .= '?>';
					file_put_contents($site_root.'inc/db.php',$db_str);
				}else{
					$error_db = true;
				}
			break;
			default:
				$GLOBALS['mysql_lib'] = new mysql_lib(array('url'=>$site_config['db_url'],'port'=>$site_config['db_port'],'username'=>$site_config['db_username'],'passwd'=>$site_config['db_passwd'],'name'=>$site_config['db_name'],'newlink'=>true));
				if($GLOBALS['mysql_lib']->stat()){
					$sql = file_get_contents('./lib/app.sql');
					$sql = str_replace('%--%',$site_config['db_left'],$sql);
					$sql = explode(';',$sql);
					foreach($sql as $key=>$val){
						if(trim($val)){
							if(!$GLOBALS['mysql_lib']->query($val)){
								$message .= $GLOBALS['mysql_lib']->error().'<br>';
							}
						}
					}
					$db_str = "<?php \n";
					$db_str .= '$database_type = \''.$site_config['db_type'].'\';'."\n";
					$db_str .= '$db_left = \''.$site_config['db_left'].'\';'."\n";
					$db_str .= '$db_url = \''.$site_config['db_url'].'\';'."\n";
					$db_str .= '$db_port = \''.$site_config['db_port'].'\';'."\n";
					$db_str .= '$db_name = \''.$site_config['db_name'].'\';'."\n";
					$db_str .= '$db_username = \''.$site_config['db_username'].'\';'."\n";
					$db_str .= '$db_passwd = \''.$site_config['db_passwd'].'\';'."\n";
					$db_str .= '?>';
					file_put_contents($site_root.'inc/db.php',$db_str);
				}else{
					$error_db = true;
				}			
		}
		if(!$error_db && !$message){
			$setting = serialize(array('name'=>escape_string($global_setting['name']), 'author'=>escape_string($global_setting['author']) ,'title'=>escape_string($global_setting['title']) , 'keywords'=>escape_string($global_setting['keywords']) , 'description'=>escape_string($global_setting['description']) ,  'admin'=>$_POST['admin'] , 'passwd'=>md5($_POST['passwd']),'close'=>1 ,'close_tip'=>_t('<p>Welcome to SweetRice - Thank your for install SweetRice as your website management system.</p><h1>This site is building now , please come late.</h1><p>If you are the webmaster,please go to Dashboard -> General -> Website setting </p><p>and uncheck the checkbox "Site close" to open your website.</p><p>More help at <a href="http://www.basic-cms.org/docs/5-things-need-to-be-done-when-SweetRice-installed/">Tip for Basic CMS SweetRice installed</a></p>'),'cache'=>0,'cache_expired'=>0,'user_track'=>0,'url_rewrite'=>0,'logo'=>'','theme'=>'','lang'=>'','admin_email'=>''));
			$setting_id = db_insert($site_config['db_left'].'_options',array('id',null),array('name','content','date'),array('global_setting',db_escape($setting),time()),false,$db_type);
			if(!$setting_id){
				$message .= db_error().'<br />';
			}
			$categories_id = db_insert($site_config['db_left'].'_options',array('id',null),array('name','content','date'),array('categories','',time()),false,$db_type);
			if(!$categories_id){
				$message .= db_error().'<br />';
			}
			$links_id = db_insert($site_config['db_left'].'_options',array('id',null),array('name','content','date'),array('links','',time()),false,$db_type);
			if(!$links_id){
				$message .= db_error().'<br />';
			}
		}
		return array('error_db'=>$error_db?_t('Database Error'):'','message'=>$message);
	}

	function rmSite($host){
		if(!$host || !is_dir(ROOT_DIR.'_sites/'.$host)){
			return ;
		}
		$host_home = ROOT_DIR.'_sites/'.$host.'/';
		return un_($host_home);
	}

	function dashboardLang(){
		$ltmp = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		switch(strtolower($ltmp[0])){
			case 'zh-cn':
				$lang = 'zh-cn.php';
			break;
			case 'en-us':
				$lang = 'en-us.php';
			break;
			default:
				if(strpos($ltmp[0],'zh-')){
					$lang = 'big5.php';
				}else{
					$lang = 'en-us.php';
				}
		}
		return $lang;
	}

	function dashboard_menu($type,$bgnav){
		global $global_setting;
?>
<div id="dashboard_nav">
<ul>
  <li <?php echo $type?'':' class="currency_nav"';?>><a href="./"><?php _e('Dashboard');?></a><br /><?php echo vsprintf(_t('Current version : %s'),array(SR_VERSION));?></li>
<?php
 foreach(dashboard_acts() as $key=>$val):
	if(!dashboard_role($key,$val['mustBase'])){continue;}
	switch($val['type']):
		case 1:
?>
<li <?php echo $bgnav[$key];?>>
<div><?php echo $val['title'];?>
<div class="hidden_ pl10">
<?php $cCount = count($val['child']);
foreach($val['child'] as $v):
	if($v['mustBase'] && BASE_URL != SITE_URL){continue;}
$str = null;
foreach($v['request'] as $kk=>$vv){$str .= '&'.$kk.'='.$vv;}?>
<?php if($cCount>2)echo '<p>';?>
<a href="./?<?php echo substr($str,1);?>"<?php echo $v['ncr']?' class="ncr '.($_GET['type'] == $v['request']['type'] && $_GET['mode'] == $v['request']['mode']?'menu_child_nav_curr':'').'"':($_GET['type'] == $v['request']['type'] && $_GET['mode'] == $v['request']['mode']?' class="menu_child_nav_curr"':'');?>><?php echo $v['title'];?></a> 
<?php if($cCount>2)echo '</p>';?>
<?php endforeach;?>
</div></div>
</li>
<?php
		break;
		case 2:
?>
<li <?php foreach($val['child'] as $v){echo $bgnav[$v['request']['type']];}?>>
<div><?php echo $val['title'];?>
<div class="hidden_ pl10">
<?php foreach($val['child'] as $v):if(!dashboard_role($v['request']['type'],$v['mustBase'])){continue;}$str=null;foreach($v['request'] as $kk=>$vv){$str .= '&'.$kk.'='.$vv;}?>
<p><a href="./?<?php echo substr($str,1);?>"<?php echo $v['ncr']?' class="ncr '.($_GET['type'] == $v['request']['type'] && $_GET['mode'] == $v['request']['mode']?'menu_child_nav_curr':'').'"':($_GET['type'] == $v['request']['type'] && $_GET['mode'] == $v['request']['mode']?' class="menu_child_nav_curr"':'');?>><?php echo $v['title'];?></a></p>
<?php endforeach;?>
</div></div>
</li>
<?php
		break;
		case 3:
			foreach($val['request'] as $kk=>$vv){$str=null;$str .= '&'.$kk.'='.$vv;}
?>
<li <?php echo $bgnav[$key];?>><a href="./?<?php echo substr($str,1);?>"<?php echo $val['ncr']?' class="ncr"':'';?>><?php echo $val['title'];?></a></li>
<?php
		break;
		case 5:
?>
<li <?php echo $bgnav['plugins']?$bgnav['plugins']:$bgnav['plugin'];?>>
<div>
<a href="./?type=<?php echo $key;?>"<?php echo $val['ncr']?' class="ncr"':'';?>><?php echo $val['title'];?></a>
<div class="hidden_ pl10">
<?php foreach($val['child'] as $v):if(!dashboard_role($v['name'],$v['mustBase'])){continue;}$str=null;foreach($v['request'] as $kk=>$vv){$str .= '&'.$kk.'='.$vv;}?>
<p><a href="./?<?php echo substr($str,1);?>"<?php echo $v['ncr']?' class="ncr '.($_GET['type'] == 'plugin' && $_GET['plugin'] == $v['request']['plugin']?'menu_child_nav_curr':'').'"':($_GET['type'] == 'plugin' && $_GET['plugin'] == $v['request']['plugin']?' class="menu_child_nav_curr"':'');?>><?php echo $v['title'];?></a></p>
<?php if($_GET['plugin'] == $v['request']['plugin']):?>
<ul class="menu_child_nav">
<?php foreach(pluginApi($v['request']['plugin'],'app_navs') as $app_nav):?>
<li><a href="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>$app_nav['app_mode']));?>" <?php echo $_GET['type'] == 'plugin' && $_GET['plugin'] == $v['request']['plugin'] && $_GET['app_mode'] == $app_nav['app_mode']?' class="menu_child_nav_curr"':'';?>><?php echo $app_nav['name'];?></a></li>
<?php endforeach;?>
</ul>
<?php endif;?>
<?php endforeach;?>
</div>
</div>
</li>
<?php
		break;
 endswitch;
 endforeach;
 ?>
<li><a href="../"><?php _e('Home');?></a></li>
<li><?php echo vsprintf(_t('Server Time : %s'),array(date(_t('M d Y H:i'))));?> 
<?php echo $global_setting['timeZone']?_t('Time zone').':'.$global_setting['timeZone']:'';?></li>
</ul>
</div>
<?php
	}

	function plugin_install($plugin){
		$plugin_list = pluginList();
		if(!$plugin_list[$plugin]['name']){
			return array('status'=>0,'status_code'=>_t('Invalid plugin - missing plugin name'));
		}
		$plugin_directory = $plugin_list[$plugin]['directory'];
		$optionRow = getOption('plugin_installed');
		$plugin_installed = unserialize($optionRow['content']);
		if($plugin_installed[$plugin]){
			return array('status'=>0,'status_code'=>_t('Plugin already exists.'));
		}
		if(file_exists(SITE_HOME.'_plugin/'.$plugin_directory.'/pluginInstaller.php')){
			include_once(SITE_HOME.'_plugin/'.$plugin_directory.'/pluginInstaller.php');
			if(class_exists('pluginInstaller')){
				$pluginInstaller = new pluginInstaller($plugin_list[$plugin]);
			}
		}
		if(is_object($pluginInstaller) && method_exists($pluginInstaller,'beforeInstall')){
			$pluginInstaller->beforeInstall();
		}
		if($plugin_directory && file_exists(SITE_HOME.'_plugin/'.$plugin_directory.'/plugin_config.php')){
			include(SITE_HOME.'_plugin/'.$plugin_directory.'/plugin_config.php');
			switch(DATABASE_TYPE){
				case 'sqlite':
					if($plugin_config['install_sqlite']){
						$sql = file_get_contents(SITE_HOME.'_plugin/'.$plugin_directory.'/'.$plugin_config['install_sqlite']);
						preg_match_all('/CREATE\s+TABLE\s+"%--%_(.+)"\s+\(/i',$sql, $tables);
						foreach($tables[1] as $val){
							$val = trim($val);
							if($val){
								dropTable(DB_LEFT_PLUGIN.'_'.$val);
							}
						}
						$sql = str_replace('%--%',DB_LEFT_PLUGIN,$sql);
						$sql = explode(';',$sql);
					}
				break;
				case 'pgsql':
					if($plugin_config['install_pgsql']){
						$sql = file_get_contents(SITE_HOME.'_plugin/'.$plugin_directory.'/'.$plugin_config['install_pgsql']);
						$sql = str_replace('%--%',DB_LEFT_PLUGIN,$sql);
						$sql = explode(';',$sql);
					}
				break;
				default:
					if($plugin_config['install_sql']){
						$sql = file_get_contents(SITE_HOME.'_plugin/'.$plugin_directory.'/'.$plugin_config['install_sql']);
						$sql = str_replace('%--%',DB_LEFT_PLUGIN,$sql);
						$sql = explode(';',$sql);
					}		
			}
			if($sql){
				foreach($sql as $key=>$val){
					if(trim($val)){
						$error = db_query(trim($val));
						if($error){
							$message .= $error.'<br>';
						}
					}
				}					
			}
			if(!$message){
				$plugin_installed[$plugin_config['name']] = time();
				setOption('plugin_installed',serialize($plugin_installed));
				if(is_object($pluginInstaller) && method_exists($pluginInstaller,'beforeInstall')){
					$pluginInstaller->afterInstall();
				}
				return array('status'=>1,'status_code'=>vsprintf(_t('%s has been install successfully.'),array($plugin_config['name'])));
			}else{
				return array('status'=>0,'status_code'=>$message);
			}
		}
	}

	function plugin_deinstall($plugin){
		$plugin_list = pluginList();
		if(!$plugin_list[$plugin]['name']){
			alert(_t('Invalid plugin - missing plugin name'),'./?type=plugins');
		}
		$plugin_directory = $plugin_list[$plugin]['directory'];
		if(file_exists(SITE_HOME.'_plugin/'.$plugin_directory.'/pluginInstaller.php')){
			include_once(SITE_HOME.'_plugin/'.$plugin_directory.'/pluginInstaller.php');
			if(class_exists('pluginInstaller')){
				$pluginInstaller = new pluginInstaller($plugin_list[$plugin]);
			}
		}
		if(is_object($pluginInstaller) && method_exists($pluginInstaller,'beforeDeInstall')){
			$pluginInstaller->beforeDeInstall();
		}
		$rows = db_arrays("SELECT * FROM `".DB_LEFT."_item_plugin` WHERE `plugin` = '".$plugin."'");
		foreach($rows as $val){
			switch($val['item_type']){
				case 'post':
					db_query("DELETE FROM `".DB_LEFT."_posts` WHERE `id` = '".$val['item_id']."'");
					db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` = '".$val['item_id']."' AND `item_type` = 'post' ");
					db_query("DELETE FROM `".DB_LEFT."_comment` WHERE `post_id` = '".$val['item_id']."'");
					db_query("DELETE FROM `".DB_LEFT."_attachment` WHERE `post_id` = '".$val['item_id']."'");	
				break;
				case 'category':
					db_query("DELETE FROM `".DB_LEFT."_category` WHERE `id` = '".$val['item_id']."'");
					db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` = '".$val['item_id']."' AND `item_type` = 'category' ");
					db_query("UPDATE `".DB_LEFT."_posts` SET `category` = '0' WHERE `category` = '".$val['item_id']."'");
					$Crows = db_arrays("SELECT * FROM `".DB_LEFT."_category`");
					setOption('categories',db_escape(serialize($Crows)));
				break;
			}
		}
		if($plugin_directory && file_exists(SITE_HOME.'_plugin/'.$plugin_directory.'/plugin_config.php')){
			include(SITE_HOME.'_plugin/'.$plugin_directory.'/plugin_config.php');
			switch(DATABASE_TYPE){
				case 'sqlite':
					if($plugin_config['deinstall_sqlite']){
						$sql = file_get_contents(SITE_HOME.'_plugin/'.$plugin_directory.'/'.$plugin_config['deinstall_sqlite']);
						$sql = str_replace('%--%',DB_LEFT_PLUGIN,$sql);
						$sql = explode(';',$sql);
					}
				break;
				case 'pgsql':
					if($plugin_config['deinstall_pgsql']){
						$sql = file_get_contents(SITE_HOME.'_plugin/'.$plugin_directory.'/'.$plugin_config['deinstall_pgsql']);
						$sql = str_replace('%--%',DB_LEFT_PLUGIN,$sql);
						$sql = explode(';',$sql);
					}
				break;
				default:
					if($plugin_config['deinstall_sql']){
						$sql = file_get_contents(SITE_HOME.'_plugin/'.$plugin_directory.'/'.$plugin_config['deinstall_sql']);
						$sql = str_replace('%--%',DB_LEFT_PLUGIN,$sql);
						$sql = explode(';',$sql);
					}
			}
			if($sql){
				foreach($sql as $key=>$val){
					if(trim($val)){
						$error = db_query(trim($val));
						if($error){
							$message .= $error.'<br>';
						}
					}
				}
			}
			if(!$message){
				$plugin_old = $plugin_installed = array();
				$optionRow = getOption('plugin_installed');
				if($optionRow['content']){
					$plugin_old = unserialize($optionRow['content']);
				}
				foreach($plugin_old as $k=>$v){
					if($k != $plugin_config['name']){
						$plugin_installed[$k] = $v;
					}
				}
				setOption('plugin_installed',serialize($plugin_installed));
				if(is_object($pluginInstaller) && method_exists($pluginInstaller,'afterDeInstall')){
					$pluginInstaller->afterDeInstall();
				}
				return array('status'=>1,'status_code'=>vsprintf(_t('%s has been deinstall successfully.'),array($plugin_config['name'])));
			}else{
				return array('status'=>0,'status_code'=>$message);
			}
		}
	}

?>