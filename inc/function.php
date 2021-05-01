<?php
/**
 * All function.
 *
 * @package SweetRice
 * @SweetRice core.
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	function do_data($a,$filterData = 'public'){
		foreach($a as $key=>$val){
			if(!is_array($val)){
				if ($filterData == 'strict') {
					$a[$key] = trim(clean_quotes(htmlspecialchars(strip_tags($val))));
				}else{
					$a[$key] = trim(clean_quotes($val));
				}				
			}else{
				$a[$key] = do_data($val,$filterData);
			}
		}
		return $a;
	}
	function escape_string($str){
		return htmlspecialchars($str,ENT_QUOTES);
	}
	function clean_quotes($str){
		return $str;
	}
	if(!function_exists('sqlite_escape_string')){
		function sqlite_escape_string($str){
			return str_replace('\'','\'\'',$str);
		}	
	}
	function db_escape($str){
		return $GLOBALS['db_lib']->db_escape($str);
	}

	function db_unescape($str){
		return $GLOBALS['db_lib']->db_unescape($str);
	}
	if (!function_exists('htmlspecialchars_decode')) {
		function htmlspecialchars_decode($str,$quote_style){
			$_str = array('&amp;','&quot;','&#039;','&lt;','&gt;');
			$str_ = array('&','"','\'','<','>');
			return str_replace($_str,$str_,$str);
		}
	}
	function initPermalinks(){
		$row = getOption('permalinks_system');
		if($row['content']){
			$permalinks = unserialize($row['content']);
		}else{
			$permalinks['attachment'] = 'download';
			$permalinks['rssfeed'] = 'rssfeed';
			$permalinks['rssfeedCat'] = 'cat_rssfeed';
			$permalinks['rssfeedPost'] = 'rssfeed';
			$permalinks['sitemapXml'] = 'sitemap';
			$permalinks['sitemapHtml'] = 'sitemap';
			$permalinks['comment'] = 'comment';
			$permalinks['tag'] = 'tags';
			$permalinks['ad'] = 'ads';
		}
		return $permalinks;
	}

	function initlinks($url){
		if(!$url){return false;}
		$row = db_array_nocache("SELECT * FROM `".DB_LEFT."_links` WHERE `url` = '$url'");
		return unserialize($row['request']);
	}

	function parseUrl($url){
		$row = getOption('parseList');
		if(!$row['content']){
			return false;
		}
		$parseList = unserialize($row['content']);
		foreach($parseList as $key=>$val){
			$key = ltrim($key,'[');
			$key = rtrim($key,']');
			if(preg_match($key,$url,$matches)){
				parse_str($val,$data);
				foreach($data as $k=>$v){
					if(preg_match('/^\$([1-9]+)$/',$v,$tmp)){
						$data[$k] = $matches[$tmp[1]];
					}
				}
				return $data;
			}
		}
	}
	
	function redirectUrl($url){
		$row = getOption('redirectList');
		if(!$row['content']){
			return false;
		}
		$redirectList = unserialize($row['content']);
		foreach($redirectList as $key=>$val){
			if(preg_match($key,$url,$matches)||$key==$url){
				preg_match_all('/(\$[1-9])+/',$val,$data);
				foreach($data[1] as $k=>$v){
					$ndata[$k] = $matches[$k+1];
				}
				$toUrl = str_replace($data[1],$ndata,$val);
				if(!preg_match("|^https?://.+$|",$toUrl)){
					$toUrl = BASE_URL.$toUrl;
				}
				_301($toUrl);
				break;
			}
		}
	}

	//init Url data to SweetRice.
	function initUrl(){
		global $global_setting;
		$permalinks = initPermalinks();
		$load_dir = str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/');
		$url = substr(preg_replace("/(\?.*)?$/",'',$_SERVER['REQUEST_URI']),strlen($load_dir));
		$redirectList = array();
		$row = getOption('redirectList');
		if($row['content']){
			$redirectList = unserialize($row['content']);
		}
		$index_setting = getOption('index_setting');
		$index_setting = unserialize($index_setting['content']);
		if($index_setting['url'] && $index_setting['url'] == $url){
			_301(BASE_URL);
		}
		redirectUrl($url);
		$url_data = array();
		$url_data = initlinks($url);
		if($url_data){
			return $url_data;
		}
		$url_data = parseUrl($url);
		if($url_data){
			return $url_data;
		}
		if(deny_url($url)){
			$url_data['action'] = 'Forbidden';
		}elseif(preg_match("/^".$permalinks['attachment']."\/([0-9]+)\/$/",$url,$matches)){
			$url_data['action'] = 'attachment';
			$url_data['id'] = $matches[1];
		}elseif(preg_match("/^".$permalinks['ad']."\/([a-z0-9A-Z-_]+)\.js$/",$url,$matches)){
			$url_data['action'] = 'ads';
			$url_data['adname'] = $matches[1];
		}elseif($url == $permalinks['rssfeed'].'.xml'){
			$url_data['action'] = 'rssfeed';
		}elseif(preg_match("/^".$permalinks['rssfeedCat']."\/([a-zA-Z0-9\-_]+)\.xml$/",$url,$matches)){
			$url_data['action'] = 'rssfeed';
			$url_data['type'] = 'category';
			$url_data['c'] = $matches[1];
		}elseif(preg_match("/^".$permalinks['rssfeedPost']."\/([a-zA-Z0-9\-_]+)\.xml$/",$url,$matches)){
			$url_data['action'] = 'rssfeed';
			$url_data['type'] = 'entry';
			$url_data['post'] = $matches[1];
		}elseif(preg_match('|^'.$permalinks['sitemapXml'].'/?(.+?)?/?([0-9]+)?\.xml$|',$url,$match)){
			$url_data['action'] = 'sitemap';
			$url_data['type'] = 'xml';
			$url_data['mode'] = $match[1];
			$url_data['p'] = $match[2];
		}elseif(preg_match('|^'.$permalinks['sitemapHtml'].'/(.+?)?/?([0-9]+)?/?$|',$url,$match)){
			$url_data['action'] = 'sitemap';
			$url_data['mode'] = $match[1];
			$url_data['p'] = $match[2];
		}elseif(preg_match("/^".$permalinks['comment']."\/([a-zA-Z0-9\-_]+)\/(([0-9]{0,3})\/)?$/",$url,$matches)){
			$url_data['action'] = 'comment';
			$url_data['post'] = $matches[1];
			$url_data['p'] = $matches[3];
		}elseif(preg_match("/^".$permalinks['tag']."\/([^\/]+)\/(([0-9]{0,3})\/)?$/",$url,$matches)){
			$url_data['action'] = 'tags';
			$url_data['tag'] = rawurldecode($matches[1]);
			$url_data['p'] = $matches[3];
		}elseif(preg_match("/^([a-zA-Z0-9\-_]+)\.html$/",$url,$matches)){
			$url_data['action'] = 'entry';
			$url_data['post'] = $matches[1];
		}elseif($global_setting['pagebreak'] && preg_match("/^([a-zA-Z0-9\-_]+):([0-9]+|all)\.html$/",$url,$matches)){
			$url_data['action'] = 'entry';
			$url_data['post'] = $matches[1];
			$url_data['p'] = $matches[2];
		}elseif(preg_match("/^([a-zA-Z0-9\-_]+)\/(([0-9]{0,3})\/)?$/",$url,$matches)){
			$url_data['action'] = 'category';
			$url_data['c'] = $matches[1];
			$url_data['p'] = $matches[3];
		}elseif($global_setting['pagebreak'] && preg_match("/^([a-zA-Z0-9\-_]+)\/([a-zA-Z0-9\-_]+):([0-9]+|all)\/$/",$url,$matches)){
			$url_data['action'] = 'entry';
			$url_data['cateName'] = $matches[1];
			$url_data['post'] = $matches[2];
			$url_data['p'] = $matches[3];
		}elseif(preg_match("/^([a-zA-Z0-9\-_]+)\/([a-zA-Z0-9\-_]+)\/$/",$url,$matches)){
			$url_data['action'] = 'entry';
			$url_data['cateName'] = $matches[1];
			$url_data['post'] = $matches[2];
		}elseif($url){
			$prefix = $url;
			$prefix = explode('/',$prefix);
			if(count($prefix) >= 1 ){
				$prefix = end($prefix);
			}
			if(strpos($prefix,'.')){
				$url_data['rtype'] = 'wp';
			}else{
				$url_data['rtype'] = 'wop';
			}
			$url_data['action'] = 'Unknow';
			$url_data['url'] = $url;
		}elseif(!$_SERVER['QUERY_STRING']){
			if($index_setting['req']){
				parse_str(ltrim($index_setting['req'],'?'),$reqs);
				foreach($reqs as $key=>$val){
					$url_data[$key] = $val;
				}
			}
		}
		return $url_data;
	}

	function deny_url($url){
		$dirs = array('inc',DASHBOARD_DIR.'/lib');
		foreach($dirs as $val){
			if(preg_match("/^".str_replace('/','\\/',$val)."\/.*/",$url)){
				return true;
			}
		}
		return false;
	}

	function formatUrl($str){
		parse_str($str,$reqs);
		ksort($reqs);
		foreach($reqs as $key=>$val){
			$output .= '&'.$key.'='.$val;
		}
		return '?'.ltrim($output,'&');
	}

	function show_link_ads($adname){
		if(URL_REWRITE){
			$permalinks = initPermalinks();
			return $permalinks['ad'].'/'.$adname.'.js';
		}else{
			return formatUrl('action=ads&adname='.$adname);
		}
	}

	function show_link_page($cat_link,$post,$original_url=false){
		if(URL_REWRITE && !$original_url){
			if($cat_link){
				return $cat_link.'/'.$post.'/';
			}else{
				return $post.'.html';
			}
		}else{
			return formatUrl('action=entry&post='.$post.'&catName='.$cat_link);
		}
	}
	
	function show_link_pagebreak($cat_link,$post,$pb,$original_url=false){
		if(URL_REWRITE && !$original_url){
			if($cat_link){
				return $cat_link.'/'.$post.($pb > 1 || $pb == 'all' ?':'.$pb:'').'/';
			}else{
				return $post.($pb > 1 || $pb == 'all' ?':'.$pb:'').'.html';
			}
		}else{
			return formatUrl('action=entry&post='.$post.'&catName='.$cat_link.'&p='.$pb);
		}
	}

	function show_link_page_xml($post,$original_url=false){
		if(URL_REWRITE && !$original_url){
			$permalinks = initPermalinks();
			return $permalinks['rssfeedPost'].'/'.$post.'.xml';
		}else{
			return formatUrl('action=rssfeed&type=entry&post='.$post);
		}
	}
	function show_link_cat($category,$p = '',$original_url=false){
		if(!$category){
			return 'javascript:void(0);';
		}
		if(URL_REWRITE && !$original_url){
			return $category.'/'.($p>1?$p.'/':'');
		}else{
			return formatUrl('action=category&c='.$category.($p>1?'&p='.$p:''));
		}
	}
	function show_link_cat_xml($category,$original_url=false){
		if(URL_REWRITE && !$original_url){
			$permalinks = initPermalinks();
			return $permalinks['rssfeedCat'].'/'.$category.'.xml';
		}else{
			return formatUrl('action=rssfeed&type=category&c='.$category);
		}
	}
	function show_link_tag($tag,$original_url=false){
		if(URL_REWRITE && !$original_url){
			$permalinks = initPermalinks();
			return $permalinks['tag'].'/'.rawurlencode($tag).'/';
		}else{
			return formatUrl('action=tags&tag='.rawurlencode($tag));
		}
	}
	function show_link_comment($post,$p=false,$original_url=false){
		if(URL_REWRITE && !$original_url){
			$permalinks = initPermalinks();
			return $permalinks['comment'].'/'.$post.'/'.($p>1?$p.'/':'');
		}else{
			return formatUrl('action=comment&post='.$post.($p>1?'&p='.$p:''));
		}
	}
	function show_link_attachment($fileID,$original_url=false){
		$permalinks = initPermalinks();
		if(URL_REWRITE && !$original_url){
			return $permalinks['attachment'].'/'.$fileID.'/';
		}else{
			return formatUrl('action=attachment&id='.$fileID);
		}
	}
	function show_link_sitemapHtml($original_url=false,$mode = '',$page = 0){
		$permalinks = initPermalinks();
		if(URL_REWRITE && !$original_url){
			return $permalinks['sitemapHtml'].'/'.($mode?$mode.'/':'').($page?$page.'/':'');
		}else{
			return formatUrl('action=sitemap'.($mode ? '&mode='.$mode:'').($page?'&p='.$page:''));
		}
	}
	function show_link_sitemapXml($original_url=false,$mode = '',$page = 0){
		$permalinks = initPermalinks();
		if(URL_REWRITE && !$original_url){
			return $permalinks['sitemapXml'].($mode?'/'.$mode:'').($page?'/'.$page:'').'.xml';
		}else{
			return formatUrl('action=sitemap&type=xml'.($mode ? '&mode='.$mode:'').($page?'&p='.$page:''));
		}
	}

	function show_link_rssfeed($original_url=false){
		$permalinks = initPermalinks();
		if(URL_REWRITE && !$original_url){
			return $permalinks['rssfeed'].'.xml';
		}else{
			return formatUrl('action=rssfeed');
		}
	}

	function postPreview($content){
		preg_match_all("/(<p[^>]*>([^<]+)<\/p>).*/",$content,$matchs);
		foreach($matchs[1] as $key=>$val){
			if($val !='<p>&nbsp;</p>' && $val!='<p></p>'){
				preg_match("/<p[^>]*>([^<]+)<\/p>/",$val,$out);
				$previewContent = $out[1];
				break;
			}
		}
		if(!$previewContent){
			$previewContent = mb_substr(strip_tags($content),0,300,'UTF-8');
		}
		return $previewContent;
	}

	if(!function_exists('mb_substr')){
		function mb_substr($str,$start,$len,$charcode){
			$str_len = strlen($str);
			$tmpstr = '';
			for($i = 0; $i < $start; $i++){
				 if(ord(substr($str, $i, 1)) > 127){
					 if(ord(substr($str, $i, 2)) > 127){
						$i += 2;
					 }else{
						$i++;
					 }					
				 }
			}
			$start = $i;
			$strlen = ($start + $len)>$str_len?$str_len:($start + $len);
			for($i = $start; $i < $strlen; $i++){
				 if(ord(substr($str, $i, 1)) > 127){
					 if(ord(substr($str, $i, 2)) > 127){
						$tmpstr .= substr($str, $i, 3);
						$i += 2;
					 }else{
						$tmpstr .= substr($str, $i, 2);
						$i++;
					 }					
				 }else
					$tmpstr .= substr($str, $i, 1);
			}
			return $tmpstr;
		}
	}

	function checkemail($email){
		$email = strtolower($email);
		return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]*\.)+[a-zA-Z]{2,3}$/",$email);
	}

	function my_post($to,$subject,$mail_text,$mail_html,$from_mail,$from_name,$mime_boundary = '',$charset = 'UTF-8',$attachments = array()){		
		if(!$mime_boundary){
			$mime_boundary = md5(time());
		}
		$attach_content = '';
		if(count($attachments)){
			foreach($attachments as $val){
				if(!file_exists($val)){
					continue;
				}
				$tmp = explode('/',$val);
				$fp = fopen($val, "r");
				$data = fread($fp, filesize($val)); 
				$attach_source = chunk_split (base64_encode($data));
				$attach_content .= "\n\n";
				$attach_content .= "--$mime_boundary\n";
				$attach_content .= "Content-Type: ".sr_file_type($tmp[count($tmp)-1])."; name=".'=?'.$charset.'?B?'.base64_encode($tmp[count($tmp)-1]).'?='."\n";
				$attach_content .= "Content-disposition: attachment;filename=".'=?'.$charset.'?B?'.base64_encode($tmp[count($tmp)-1]).'?='."\n";
				$attach_content .= "Content-transfer-encoding: base64\n\n";
				$attach_content .= $attach_source;
			}
		}
		if($attach_content){
			$content_type = 'multipart/mixed';
		}else{
			$content_type = 'multipart/alternative';
		}
		$from_name = '=?'.$charset.'?B?'.base64_encode($from_name).'?=';
		$subject = '=?'.$charset.'?B?'.base64_encode($subject).'?=';
		$headers = 'From: '.$from_name.' <'.($from_mail?$from_mail:'noreply@'.$_SERVER['HTTP_HOST']).">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: ".$content_type."; boundary=\"$mime_boundary\"\n";
		$message = "--$mime_boundary\n";
		$message .= "Content-Type: text/plain; charset=".$charset."\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= $mail_text;
		$message .= "\n\n";
		$message .= "--$mime_boundary\n";
		$message .= "Content-Type: text/html; charset=".$charset."\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= $mail_html;
		$message .= "\n";
		if($attach_content){
			$message .= $attach_content;
		}
		$message .= "--$mime_boundary--\n\n";
		$mail_sent = mail( $to, $subject, $message, $headers );
		return $mail_sent;
	}

	function js_unescape($str){
		return rawurldecode($str);
	}

	function sweetrice_cache($cache_link,$data,$cache_type){
		global $global_setting;
		if(!$global_setting['cache']){return ;}
		switch(true){
			case extension_loaded('redis') && $global_setting['redis_setting']['enable']:
				return redis_cache($cache_link,$data,$cache_type);
			break;
			case extension_loaded('leveldb'):
				return leveldb_cache($cache_link,$data,$cache_type);
			break;
			case extension_loaded('dba'):
				return dba_cache($cache_link,$data,$cache_type);
			break;
			default:
			if(!file_exists(SITE_HOME.'inc/cache/')){
				mkdir(SITE_HOME.'inc/cache/');
			}
			file_put_contents(SITE_HOME.'inc/cache/'.$cache_link,data2cache($data,$cache_type));
			return ;
		}
	}

	function sweetrice_cached($cache_link,$cache_type){
		global $global_setting;
		if(!$global_setting['cache']){return false;}
		switch(true){
			case extension_loaded('redis') && $global_setting['redis_setting']['enable']:
				return redis_cached($cache_link,$cache_type);
			break;
			case extension_loaded('leveldb'):
				return leveldb_cached($cache_link,$cache_type);
			break;
			case extension_loaded('dba'):
				return dba_cached($cache_link,$cache_type);
			break;
			default:
				if(file_exists(SITE_HOME.'inc/cache/'.$cache_link)&&(time()-@filemtime(SITE_HOME.'inc/cache/'.$cache_link) <= $global_setting['cache_expired'] || $global_setting['cache_expired']==0)){
					$cache_data = file_get_contents(SITE_HOME.'inc/cache/'.$cache_link);
					return cache2data($cache_data,$cache_type);
				}else{
					return false;
				}
		}
	}

	function data2cache($data,$cache_type){
		$cache_data = '';
		switch($cache_type){
			case 'db_array':
			case 'db_arrays':
				if(is_array($data)){
					$cache_data = serialize($data);	
				}
			break;
			case 'db_total':
				$cache_data = $data;
			break;
		}
		return $cache_data;
	}

	function cache2data($cache_data,$cache_type){
		switch($cache_type){
			case 'db_array':
			case 'db_arrays':
				return unserialize($cache_data);
			break;
			case 'db_total':
				return $cache_data;
			break;	
		}
	}

	function dba_cache($cache_link,$data,$cache_type){
		if(!extension_loaded('dba')){
			return ;
		}
		if(!is_dir(SITE_HOME.'inc/cache/')){
			mkdir(SITE_HOME.'inc/cache/');
		}
		if(!file_exists(SITE_HOME.'inc/cache/cache.db')){
			$dba = dba_open(SITE_HOME.'inc/cache/cache.db', 'c', 'db4');
		}else{
			$dba = dba_open(SITE_HOME.'inc/cache/cache.db', 'w', 'db4');
		}
		dba_replace($cache_link, time().'/'.data2cache($data,$cache_type), $dba);
		dba_close($dba);
		return ;
	}

	function dba_cached($cache_link,$cache_type){
		if(!extension_loaded('dba')){
			return false;
		}
		global $global_setting;
		if(!file_exists(SITE_HOME.'inc/cache/cache.db')){
			return false;
		}
		$cache_data = null;
		$dba = dba_open(SITE_HOME.'inc/cache/cache.db', 'r', 'db4');
		if(dba_exists($cache_link,$dba)){
			$data = dba_fetch($cache_link,$dba);
			if(time()-substr($data,0,10) <= $global_setting['cache_expired'] || $global_setting['cache_expired']==0){				
				$cache_data = cache2data(substr($data,11),$cache_type);
			}
		}
		dba_close($dba);
		return isset($cache_data)?$cache_data:false;
	}

	function leveldb_cache($cache_link,$data,$cache_type){
		if(!extension_loaded('leveldb')){
			return ;
		}
		$db_dir = SITE_HOME.'inc/cache/leveldb';
		if(!is_dir(SITE_HOME.'inc/cache/')){
			mkdir(SITE_HOME.'inc/cache/');
		}
		if(!is_dir($db_dir)){
			mkdir($db_dir);
		}
		$db = new LevelDb($db_dir);
		if(!is_object($db)){
			LevelDB::repair($db_dir);
			$db = new LevelDb($db_dir);
		}
		if(!is_object($db)){
			return ;
		}
		$db->set($cache_link, time().'/'.data2cache($data,$cache_type));
		$db->close();
		return ;
	}


	function leveldb_cached($cache_link,$cache_type){
		if(!extension_loaded('leveldb')){
			return false;
		}
		global $global_setting;
		$db_dir = SITE_HOME.'inc/cache/leveldb';
		$db = new LevelDb($db_dir);
		if(!is_object($db)){
			return false;
		}
		$cache_data = false;
		$data = $db->get($cache_link);
		if(time()-substr($data,0,10) <= $global_setting['cache_expired'] || $global_setting['cache_expired']==0){				
			$cache_data = cache2data(substr($data,11),$cache_type);
		}
		$db->close();
		return isset($cache_data)?$cache_data:false;
	}

	
 	function init_redis($param = array('server' => '127.0.0.1','port' => 6379,'passwd' => '')){
		$redis = new Redis();
		$redis->pconnect($param['server'], $param['port']);
		$redis->auth($param['passwd']);
		return $redis;
	}

	function redis_cache($cache_link,$data,$cache_type){
		global $global_setting;
		if(!extension_loaded('redis') || !$global_setting['redis_setting']['enable']){
			return ;
		}
		$redis = init_redis($global_setting['redis_setting']);
		$cache_data = $redis->get($cache_link);
		if ($cache_data) {
			return ;
		}
		if ($global_setting['cache_expired'] > 0) {
			$redis->setex($cache_link,$global_setting['cache_expired'],data2cache($data,$cache_type));
		}else{
			$redis->set($cache_link,data2cache($data,$cache_type));
		}
		return ;
	}

	function redis_cached($cache_link,$cache_type){
		global $global_setting;
		if(!extension_loaded('redis') || !$global_setting['redis_setting']['enable']){
			return false;
		}
		$redis = init_redis($global_setting['redis_setting']);
		$cache_data = $redis->get($cache_link);
		if ($cache_data) {
			$cache_data = cache2data($cache_data,$cache_type);
		}
		return isset($cache_data)?$cache_data:false;
	}

	function db_insert($table,$_id,$_key,$_val){
		$_key = db_escape($_key);
		$_val = db_escape($_val);
		return $GLOBALS['db_lib']->db_insert($table,$_id,$_key,$_val);
	}

	function db_error(){
		if (is_object($GLOBALS['db_lib'])) {
			return $GLOBALS['db_lib']->error();
		}
	}

	function db_query($sql,$return_result = false){
		if ($return_result) {
			return $GLOBALS['db_lib']->query($sql);
		}
		$GLOBALS['db_lib']->query($sql);
		return db_error();
	}

	function db_arrays($sql,$type = 'ASSOC'){
		$cache_link = 'db_arrays_'.md5($sql);
		$cache_data = sweetrice_cached($cache_link,'db_arrays');
		if($cache_data){
			return $cache_data;
		}else{
			$rows = $GLOBALS['db_lib']->db_arrays($sql,$type);
			sweetrice_cache($cache_link,$rows,'db_arrays');
			return is_array($rows)?$rows:array();
		}
	}

	function db_array($sql,$type = 'ASSOC'){
		$cache_link = 'db_array_'.md5($sql);
		$cache_data = sweetrice_cached($cache_link,'db_array');
		if($cache_data){
			return $cache_data;
		}else{
			$row = $GLOBALS['db_lib']->db_array($sql,$type);
			sweetrice_cache($cache_link,$row,'db_array');
			return is_array($row)?$row:array();
		}
	}

	function db_total($sql){
		$cache_link = 'db_total_'.md5($sql);
		$cache_data = sweetrice_cached($cache_link,'db_total');
		if($cache_data){
			return $cache_data;
		}else{
			$total = $GLOBALS['db_lib']->db_total($sql);
			sweetrice_cache($cache_link,$total,'db_total');
			return $total;			
		}
	}

	function db_fetch($query){
		$table = $query['table'];
		$where = $query['where'];
		$pager = $query['pager'];
		$pager_function = $pager['pager_function'];
		if(!$pager_function){
			$pager_function = 'pager';
		}
		$limit = $query['limit']?get_limit_sql($query['limit'][0],$query['limit'][1]):'';
		$group = $query['group'];
		$order = $query['order'];
		$field = $query['field'];
		if(!$field){
			$field = '*';
		}
		if(is_array($where)){
			$where = " WHERE 1=1 AND ".implode(' AND ',$where)." ";
		}elseif(trim($where)){
			$where = " WHERE ".$where." ";
		}
		if($pager['page_limit'] && $pager['p_link']){
			$page_limit = $pager['page_limit'];
			$total = db_total("SELECT COUNT(*) FROM $table ".$where);
			if($pager['curr_page'] == 'last'){
				$pager['curr_page'] = ceil($total/$page_limit);
			}
			$pager = call_user_func_array($pager_function,array($total,$page_limit,$pager['p_link'],$pager['curr_page'],$pager['source_url']));
			$pager['total'] = $total;
			if($pager['outPage']){
				return false;
			}
			$limit = get_limit_sql($pager['page_start'],$page_limit);
		}
		$rows = db_arrays("SELECT ".$field." FROM ".$table.$where.($group?" GROUP by ".$group." ":"").($order?" ORDER by ".$order." ":"").$limit);
		if($query['fetch_one']){
			return $rows[0];
		}
		if($query['debug']){
			return array('rows'=>$rows,'pager'=>$pager,'sql'=>"SELECT ".$field." FROM ".$table.$where.($group?" GROUP by ".$group." ":"").($order?" ORDER by ".$order." ":"").$limit,'db_error'=>db_error());
		}
		return array('rows'=>$rows,'pager'=>$pager);
	}
	
	function db_fetchOne($param){
		$param['fetch_one'] = true;
		return db_fetch($param);
	}

	function clean_dbData($row){
		if(!$row){
			$row = array();
		}
		foreach($row as $key=>$val){
			$tmp = explode('.',str_replace('"','',$key));
			if(count($tmp) > 1){
				$rows[$tmp[1]] = $val;
			}else{
				$rows[$tmp[0]] = $val;
			}
		}
		return $rows;
	}
	
	function db_list($database_type = false){
		return $GLOBALS['db_lib']->db_list();
	}
	
	function createTable($table,$sql,$replace = false){
		$db_list = db_list();
		if(in_array($table,$db_list)){
			if(!$replace){
				return ;
			}
			db_query("DROP TABLE `$table`");
		}
		db_query($sql);
	}

	function dropTable($table){
		$db_list = db_list();
		if(in_array($table,$db_list)){
			db_query("DROP TABLE `$table`");
		}
	}

	function alterTable($table,$sql){
		$db_list = db_list();
		if(in_array($table,$db_list)){
			db_query($sql);
		}
	}

	function db_total_nocache($sql){
		global $global_setting;
		if(!$global_setting['cache']){
			return db_total($sql);
		}
		$cache = $global_setting['cache'];
		$global_setting['cache'] = false;
		$total = db_total($sql);
		$global_setting['cache'] = $cache;
		return $total;
	}

	function db_array_nocache($sql,$type = 'ASSOC'){
		global $global_setting;
		if(!$global_setting['cache']){
			return db_array($sql,$type);
		}
		$cache = $global_setting['cache'];
		$global_setting['cache'] = false;
		$row = db_array($sql,$type);
		$global_setting['cache'] = $cache;
		return $row;
	}
	
	function db_arrays_nocache($sql,$type = 'ASSOC'){
		global $global_setting;
		if(!$global_setting['cache']){
			return db_arrays($sql,$type);
		}
		$cache = $global_setting['cache'];
		$global_setting['cache'] = false;
		$rows = db_arrays($sql,$type);
		$global_setting['cache'] = $cache;
		return $rows;
	}

	function _out(){
		if(!headers_sent()&&extension_loaded('zlib') && strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')!==false){
			ob_start('ob_gzhandler');
		}else{
			ob_start();
		}
	}

	function _flush(){
		ob_end_flush();
	}

	function upload_($f,$dest_dir,$new_file,$old_file,$always_new = false){
		$file_type = '.php';
		if(!$f['name']){
			return $old_file;
		}
		if(substr($dest_dir,-1) != '/'){
			$dest_dir .= '/';
		}
		$tmp = explode('.',$f['name']);
		if(count($tmp)){
			$fileext = '.'.end($tmp);
		}
		if(preg_match("/[^a-zA-Z0-9_\-\.\s]+/",$new_file)){
			$new_file  = md5($new_file).$fileext;
		}
		if($always_new){
			$new_file  = md5($new_file.time()).$fileext;
		}
		if($f['name'] && strtolower($fileext) != $file_type){
			$dest = $dest_dir.$new_file;
			$r = move_uploaded_file($f['tmp_name'],$dest);
			if($old_file && file_exists($dest_dir.$old_file) && $old_file != $new_file){
				unlink($dest_dir.$old_file);
			}
			return $new_file;
		}else{
			return $old_file;
		}
	}

	function subCategory($sql='',$id=0,$level=0){
		$subCategorys = array();
		$row = db_arrays("SELECT c.`id` FROM `".DB_LEFT."_category` AS c LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON c.`id` = ip.`item_id` WHERE c.`parent_id` = '$id' AND ip.`item_type` = 'category' ".$sql);
		foreach($row as $val){
			$val['level'] = $level;
			$subCategorys[] = $val;
			$subCategorys = array_merge ($subCategorys,subCategory($sql,$val['id'],$level+1));
		}
		return $subCategorys;
	}
	
	function mod_date($d){
		if(!$d){
			$d = time();
		}
		if(intval(phpversion())>=5){
			$mod_date = date('c',$d);
		}else{
			$mod_date = date('Y-m-d',$d).'T'.date('HisO',$d);
		}
		return $mod_date;
	}

	function init_browsers($t=1){
		$browsers = array('Edge','MSIE 11.0','MSIE 10.0','MSIE 9.0','MSIE 8.0','MSIE 7.0','MSIE 6.0','Firefox','Opera','Chrome','Safari','Google','Yahoo','Bing','Baidu','Other');
		$bg_browsers = array('#280f0c','#280cfc','#2896fc','#286ea2','#285880','#0a9682','#ee7907','#e11625','#6aa0c8','#63b143','#009900','#ff11dd','#FF9900','#6655ff','#648282');
		if($t==2){
			return $bg_browsers;
		}else{
			return $browsers;
		}
	}

	function user_track(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$user_from = $_SERVER['HTTP_REFERER'];
		$this_page = $_SERVER['REQUEST_URI'];
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		$browsers = init_browsers(1);
		foreach($browsers as $val){
			if(strpos(strtoupper($user_browser),strtoupper($val)) !== false){
				$user_browser = $val;
				$is_browser = true;
				break;
			}
		}
		if(!$is_browser){
			$user_browser = 'Other';
		}
		if($user_from==''){
			$user_from = 'Directly access';
		}
		$dbname = SITE_HOME.'inc/user_track.db';
		if(!file_exists($dbname)){
			$new_track = true;
		}
		$db_track = new sqlite_lib(array('name'=>$dbname));
		if($new_track){
			$db_track->query("CREATE TABLE user_agent (id INTEGER PRIMARY KEY ,ip varchar(39) ,user_from varchar(255) ,this_page varchar(255),user_browser varchar(255),time integer)");
			$db_track->query("CREATE TABLE agent_month (id INTEGER PRIMARY KEY ,user_browser varchar(255),record_date date,total int(10),UNIQUE(user_browser,record_date))");
		}
		$db_track->db_insert('user_agent',array('id',null),array('ip','user_from','this_page','user_browser','time'),array($ip,$user_from,$this_page,$user_browser,time()));
		return ;
	}

	function get_limit_sql($page_start,$page_limit){
		if(DATABASE_TYPE=='pgsql'){
			return "LIMIT $page_limit OFFSET $page_start";
		}else{
			return "LIMIT $page_start , $page_limit ";
		}
	}

	function get_page_themes(){
		if (!defined('THEME_DIR')) {
			return array();
		}
		$theme = file(THEME_DIR.'theme.config');
		foreach($theme as $key=>$val){
			if(trim($val)){
				$tmp = explode('|',$val);
				$page_theme[trim($tmp[0])] = trim($tmp[1]);
			}
		}
		return $page_theme;
	}

 	function filesize2print($filename){
		if(substr($filename,0,strlen(SITE_URL)) != SITE_URL && preg_match('/https?:\/\/.+/',$filename)){
			return _t('Remote File');
		}
		if(substr($filename,0,strlen(SITE_URL)) == SITE_URL){
			$filename = str_replace(SITE_URL,SITE_HOME,$filename);
		}else{
			$filename = SITE_HOME.$filename;
		}
		if(!file_exists($filename)){
			return 'Missing file';
		}
		$fs = filesize($filename);
		if($fs>1073741824){
			return number_format(($fs/1073741824),2,'.','').'G';
		}elseif($fs>1048576){
			return number_format(($fs/1048576),2,'.','').'M';
		}elseif($fs>1024){
			return number_format(($fs/1024),2,'.','').'k';
		}else{
			return $fs.'B';
		}
	}

	function getAttachmentUrl($filename){
		if(!$filename){
			return ;
		}
		if(substr($filename,0,strlen(SITE_URL)) != SITE_URL && preg_match('/https?:\/\/.+/',$filename)){
			return $filename;
		}
		if(substr($filename,0,strlen(SITE_URL)) == SITE_URL){
			return $filename;
		}
		return SITE_URL.$filename;
	}
	function filterXMLContent($content){
		$content = preg_replace("/[(\\x00-\\x08)(\\x0b-\\x0c)(\\x0e-\\x1f)]*/",'',$content);
		return $content;
	}

	function _404($tip_404){
		global $global_setting;
		header('HTTP/1.1 404 Page Not Found');
		$page_theme = get_page_themes();
		switch($tip_404){
			case 'entry':
				$tip_404 = _t('Sorry ,the entry does not exists.');
			break;
			case 'category':
				$tip_404 = _t('Sorry ,the category does not exists.');
			break;
			case 'attachment':
				$tip_404 = _t('Sorry ,the attachment does not exists.');
			break;
			case 'tags':
				$tip_404 = _t('Sorry ,the tag does not exists.');
			break;
			case 'home':
				$tip_404 = _t('Invalid request,please check your url.');
			break;
		}
		if($page_theme['404']){
			include(THEME_DIR.$page_theme['404']);
		}else{
			include('inc/404.php');
		}
		exit();
	}

	function getLangTypes($lang_dir = false){
		if(!$lang_dir){
			$lang_dir = LANG_DIR;
		}
		$d = dir($lang_dir);
		while (false !== ($entry = $d->read())) {
			 if($entry!='.'&&$entry!='..'&&!is_dir($lang_dir.$entry)){
				 preg_match("/\* (.+) language file/",file_get_contents($lang_dir.$entry),$matches);
				$lang_types[basename($entry,'.php')] = $matches[1];
			 }
		}
		$d->close();
		return $lang_types;
	}
	
	function getThemeTypes(){
		if(is_dir(SITE_HOME.'_themes/')){
			$d = dir(SITE_HOME.'_themes/');
			while (false !== ($entry = $d->read())) {
				if(file_exists(SITE_HOME.'_themes/'.$entry.'/theme.config') && $entry != '.' && $entry != '..'){
						$themes[$entry] = $entry;
					}
				}
			$d->close();
			return $themes;			
		}
	}

	function _403(){
		header('HTTP/1.1 403 Forbidden'); 
		die('<!DOCTYPE html>
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Access Forbidden</h1>
<p>You don\'t have permission to access this page.</p>
</body></html>');
	}

	function _301($url){
		header('HTTP/1.1 301 Moved Permanently'); 
		header('Location: '.$url); 
		exit();
	}

	function _304($last_access,$etag,$Expires){
		header('Cache-Control: public,must-revalidate');
		header('Expires: '.gmdate('D, d M Y H:i:s', $Expires).' GMT');
		header('Last-Modified:'.gmdate('D, d M Y H:i:s', $last_access).' GMT', true, 304);
		if($etag){
			header('Etag:'.$etag,true,304);
		}
		exit(0);
	}

	function _200($last_modify,$etag,$Expires){
		header('Cache-Control: public,must-revalidate');
		header('Expires: '.gmdate('D, d M Y H:i:s', $Expires).' GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_modify).' GMT', true, 200);
		if($etag){
			header('Etag:'.$etag,true,200);
		}
	}

	function outputHeader($last_modify,$etag='',$ExpiresDate=0){
		global $global_setting;
		$last_access = $_SERVER['HTTP_IF_MODIFIED_SINCE']?strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']):0;
		$last_etag = $_SERVER['HTTP_IF_NONE_MATCH'];
		$Expires = $last_modify + $ExpiresDate;
		$Expires = $Expires > time()?$Expires:time();
		$last_modify = max($last_modify,$_COOKIE['lang_update'],$_COOKIE['theme_update'],SETTING_UPDATE,CATEGORIES_UPDATE,LINKS_UPDATE);
		if($etag&&$last_etag!=$etag){
			_200($last_modify,$etag,$Expires);
		}elseif(($last_modify>0&&$last_modify <= $last_access)||($etag&&$last_etag==$etag)){
			_304($last_access,$etag,$Expires);
		}else{
			_200($last_modify,$etag,$Expires);
		}
	}

	function pushDate($row){
		$last_modifys = array(0);
		foreach($row as $val){
			foreach($val as $v){
				$last_modifys[] = $v['date'];
			}
		}
		return max($last_modifys);
	}

	function isPluginInstall($plugin){
		$plugin_installed = array();
		$row = getOption('plugin_installed');
		if($row['content']){
			$plugin_installed = unserialize($row['content']);
		}
		return $plugin_installed[$plugin];
	}

	function themeLang(){
		global $global_setting;
		$lang = preg_replace('/[^a-zA-Z\-0-9]/','',$_COOKIE['lang']);
		if (!$lang) {
			$lang = $global_setting['theme_lang'];
		}
		if(!$lang){
			$ltmp = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			switch(strtolower($ltmp[0])){
				case 'zh-cn':
					$lang = 'zh-cn';
				break;
				case 'en-us':
					$lang = 'en-us';
				break;
				default:
					if(strpos($ltmp[0],'zh-')){
						$lang = 'big5';
					}else{
						$lang = 'en-us';
					}
			}
		}
		return $lang;
	}

	function theme(){
		global $global_setting;
		$theme = preg_replace('/[^a-zA-Z\-0-9]/','',$_COOKIE['theme']);
		if (!$theme) {
			$theme = $global_setting['theme'];
		}		
		if(is_dir(SITE_HOME.'_themes/'.$theme)){
			return $theme;
		}
	}

	function pluginApi($plugin,$apiFunction,$apiReturn=false,$param = array()){
		$plugin_list = pluginList();
		if(!$plugin||!$apiFunction||!isPluginInstall($plugin)){return false;}
			if(file_exists(SITE_HOME.'_plugin/'.$plugin_list[$plugin]['directory'].'/shareFunction.php')){
				require_once(SITE_HOME.'_plugin/'.$plugin_list[$plugin]['directory'].'/shareFunction.php');
				eval('$pluginClass = new $plugin();');
				if(!method_exists ($pluginClass,$apiFunction)){
					return false;
				}
				switch($apiReturn){
					case 'data':
						echo call_user_func_array(array($pluginClass,$apiFunction),$param);
					break;
					default:
						return call_user_func_array(array($pluginClass,$apiFunction),$param);
				}
			}else{
				return false;
			}
	}

	function pluginHook($plugin){
		$plugin_list = pluginList();
		if(!$plugin||!isPluginInstall($plugin)){return false;}
		if(file_exists(SITE_HOME.'_plugin/'.$plugin_list[$plugin]['directory'].'/index.php')){
			return SITE_HOME.'_plugin/'.$plugin_list[$plugin]['directory'].'/index.php';
		}else{
			return false;
		}
	}

	function pluginHookUrl($plugin,$args=array(),$source_url = false){
		if(!isPluginInstall($plugin)){
			return false;
		}	
		$reqs = $args;
		$reqs['action'] = 'pluginHook';
		$reqs['plugin'] = $plugin;
		ksort($reqs);
		if(URL_REWRITE && !$source_url){
			$row = getLink($plugin,$reqs);
			if($row['url']){
				return $row['url'];
			}
		}
		foreach($reqs as $key=>$val){
			$str .= '&'.$key.'='.$val;
		}
		return '?'.ltrim($str,'&');
	}

	function pluginDashboardUrl($plugin,$args=array()){
			$args['type'] = 'plugin';
			$args['plugin'] = $plugin;
			ksort($args);
			foreach($args as $key=>$val){
				$str .= '&'.$key.'='.$val;
			}
			return BASE_URL.DASHBOARD_DIR.'/?'.ltrim($str,'&');
	}

	function getOption($name,$format = false){
		if(!$name){
			return false;
		}
		$data = db_array_nocache("SELECT * FROM `".DB_LEFT."_options` WHERE `name` = '$name'");
		switch($format){
			case 'json':
				$data['output'] = json_decode($data['content']);
			break;
			case 'serialize':
				$data['output'] = unserialize($data['content']);
			break;
			case 'session':
				$data['output'] = session_decode($data['content']);
			break;
			case 'base64':
				$data['output'] = base64_decode($data['content']);
			break;
			case 'url':
				$data['output'] = urldecode($data['content']);
			break;
			case 'rawurl':
				$data['output'] = rawurldecode($data['content']);
			break;
			default:
				if($format){
					$data = $data[$format];
				}
		}
		return $data;
	}

	function setOption($name,$value,$format = false){
		if(!$name){
			return false;
		}
		$row = getOption($name,$format);
		switch($format){
			case 'json':
				$value = json_encode($value);
			break;
			case 'serialize':
				$value = serialize($value);
			break;
			case 'session':
				$value = session_encode();
			break;
			case 'base64':
				$value = base64_encode($value);
			break;
			case 'url':
				$value = urlencode($value);
			break;
			case 'rawurl':
				$value = rawurlencode($value);
			break;
		}
		$id = db_insert(DB_LEFT.'_options',array('id',$row['id']),array('name','content','date'),array($name,$value,time()));
		return $id;
	}

	function delOption($name){
		if(!$name){
			return false;
		}
		db_query("DELETE FROM `".DB_LEFT."_options` WHERE `name` = '$name'");
		return ;
	}

	function pager($total,$page_limit,$p_link,$curr_page = 1,$source_url = false){
		$page = intval($_GET['p']);
		if($page == 0){
			$page = $curr_page > 0?$curr_page:1;
		}
		if(!$page_limit){
			$page_limit = 15;
		}
		
		$page_total = ceil($total/$page_limit);
		if($page>$page_total&&$page_total){
			$outPage = true;
		}else{
			$outPage = false;
		}
		$page_start = ($page-1)*$page_limit;
		$page_last = $page_total - $page;
		
		$list_put = '';
		if($page_total<=10){
			for($i=1; $i<=$page_total; $i++){
				$tmp_link = URL_REWRITE && !$source_url?$p_link.$i:$p_link.'&p='.$i;
				$list_put .= '<a href="'.$tmp_link.(URL_REWRITE && !$source_url?'/':'').'" '.($i==$page?'class="pageCurrent"':'').'>'.$i.'</a> ';
			}
		}elseif($page == 1){
			$p_end = 0;
			for($i=0; $i<($page_last>10?10:$page_last); $i++){
				$tmp_link = URL_REWRITE && !$source_url?$p_link.($i+1):$p_link.'&p='.($i+1);
				$list_put .= '<a href="'.$tmp_link.(URL_REWRITE && !$source_url&&$i>0?'/':'').'" '.($i==0?'class="pageCurrent"':'').'>'.($i+1).'</a> ';
				$p_end +=1;
			}
			$list_put .= $page_last>=10?('<a href="'.(URL_REWRITE && !$source_url?$p_link.'2':$p_link.'&p=2').(URL_REWRITE && !$source_url?'/':'').'">'._t('Next').'&raquo;</a>'):'';
		}elseif($page == $page_total){
			$list_put .= '<a href="'.(URL_REWRITE && !$source_url?$p_link.($page_total-1):$p_link.'&p='.($page_total-1)).(URL_REWRITE && !$source_url&&$page_total>11?'/':'').'"/>&laquo;'._t('Previous').'</a> ';
			for($i=$page_total-9; $i<=$page_total; $i++){
				$tmp_link = URL_REWRITE && !$source_url?$p_link.$i:$p_link.'&p='.$i;
				$list_put .= '<a href="'.$tmp_link.(URL_REWRITE && !$source_url?'/':'').'" '.($i==$page_total?'class="pageCurrent"':'').'>'.$i.'</a> ';
			}
		}elseif($page > 1 && $page < $page_total){
			$p_end = $page-1;
			$list_put .= '<a href="'.(URL_REWRITE && !$source_url?$p_link.($page-1):$p_link.'&p='.($page-1)).(URL_REWRITE && !$source_url?'/':'').'"/>&laquo;'._t('Previous').'</a> ';
			if($page_last<10){
				for($i=10-$page_last; $i>0; $i--){
					$tmp_link = URL_REWRITE && !$source_url?$p_link.($page-$i):$p_link.'&p='.($page-$i);
					$list_put .= '<a href="'.$tmp_link.(URL_REWRITE && !$source_url&&($page-$i)>1?'/':'').'" '.($i==0?'class="pageCurrent"':'').'>'.($page-$i).'</a> ';
				}		
			}
			for($i=0; $i<($page_last>10?10:$page_last); $i++){
				$tmp_link = URL_REWRITE && !$source_url?$p_link.($page+$i):$p_link.'&p='.($page+$i);
				$list_put .= '<a href="'.$tmp_link.(URL_REWRITE && !$source_url&&($page+$i)>1?'/':'').'" '.($i==0?'class="pageCurrent"':'').'>'.($page+$i).'</a> ';
				$p_end += 1;
			}
			$list_put .= '<a href="'.(URL_REWRITE && !$source_url?$p_link.($page+1):$p_link.'&p='.($page+1)).(URL_REWRITE && !$source_url?'/':'').'">'._t('Next').'&raquo;</a>';
		}
		if($page_total>1){
			$list_put = $list_put?'<div id="PageList">'.$list_put.'</div>':'';
		}else{
			$list_put = '';
		}
		return array('page_start'=>$page_start,'list_put'=>$list_put,'outPage'=>$outPage,'page'=>$page,'page_total'=>$page_total);
	}

	function pager_pagebreak($page_total,$post,$curr_page = 1,$source_url = false){
		global $categories;
		$page = intval($_GET['p']);
		if($page == 0){
			$page = $curr_page > 0?$curr_page:1;
		}
		
		if($page > $page_total && $page_total){
			$outPage = true;
		}else{
			$outPage = false;
		}
		$page_start = ($page-1)*$page_limit;
		$page_last = $page_total - $page;
		
		$list_put = '';
		if($page_total<=10){
			for($i=1; $i<=$page_total; $i++){
				$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$i,$source_url).'" '.($i==$page?'class="pageCurrent"':'').'>'.$i.'</a> ';
			}
		}elseif($page == 1){
			$p_end = 0;
			for($i=0; $i<($page_last>10?10:$page_last); $i++){
				$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$i+1,$source_url).'" '.($i==0?'class="pageCurrent"':'').'>'.($i+1).'</a> ';
				$p_end +=1;
			}
			$list_put .= $page_last>=10?('<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],2,$source_url).'">'._t('Next').'&raquo;</a>'):'';
		}elseif($page == $page_total){
			$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$page_total - 1,$source_url).'"/>&laquo;'._t('Previous').'</a> ';
			for($i=$page_total-9; $i<=$page_total; $i++){
				$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$i,$source_url).'" '.($i==$page_total?'class="pageCurrent"':'').'>'.$i.'</a> ';
			}
		}elseif($page > 1 && $page < $page_total){
			$p_end = $page-1;
			$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$page - 1,$source_url).'"/>&laquo;'._t('Previous').'</a> ';
			if($page_last<10){
				for($i=10-$page_last; $i>0; $i--){
					$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$page - $i,$source_url).'" '.($i==0?'class="pageCurrent"':'').'>'.($page-$i).'</a> ';
				}		
			}
			for($i=0; $i<($page_last>10?10:$page_last); $i++){
				$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$page + $i,$source_url).'" '.($i==0?'class="pageCurrent"':'').'>'.($page+$i).'</a> ';
				$p_end += 1;
			}
			$list_put .= '<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],$page + 1,$source_url).'">'._t('Next').'&raquo;</a>';
		}
		if($page_total > 1){
			$list_put = $list_put?'<div id="PageList">'.$list_put.'<a href="'.show_link_pagebreak($categories[$post['category']]['link'],$post['sys_name'],'all',$source_url).'">'._t('All').'</a></div>':'';
		}else{
			$list_put = '';
		}
		return array('page_start'=>$page_start,'list_put'=>$list_put,'outPage'=>$outPage,'page'=>$page,'page_total'=>$page_total);
	}

	function generate_slug($salt = ''){
		$tmp = explode(' ', microtime());
		return $salt.base_convert($tmp[1],10,36).base_convert($tmp[0]*1000000,10,36);
	}

//API for Post insert
	function post_insert($data=array(),$without_attachment=false,$without_custome_field=false){
		if(!$data){
			$data = $_POST;
		}
		$id = intval($data['id']);
		if($id > 0){
			$row = getPosts(array('ids'=>$id,'fetch_one'=>true));
			if($row['tags']){
				$old_tags = explode(',',$row['tags']);
			}
		}
		$name = $data['name'];
		if(!$name){return false;}
		$title = $data['title'];
		$info = toggle_attachment($data['info']);
		$keyword = $data['keyword'];
		$tags = explode(',',$data['tags']);
		$taglist = array();
		foreach($tags as $val){
			$val = trim($val);
			if($val && !in_array($val,$taglist)){
				$taglist[] = $val;
			}
		}
		$tags = implode(',',$taglist);
		$description = $data['description'];
		$sys_name = $data['sys_name'];
		$category = intval($data['category']);
		$views = intval($data['views']);
		$createTime = intval($data['createTime']);
		$in_blog = intval($data['in_blog']);
		$allow_comment = intval($data['allow_comment']);
		$template = $data['template'];
		if($data['republish']){
			$createTime = false;
		}else{
			$createTime = intval($data['createTime']);
		}
		if($sys_name){
			$sysname_total = db_total_nocache("SELECT COUNT(*) FROM `".DB_LEFT."_posts` WHERE `sys_name` = '$sys_name' and `id` != '$id' ");
			if($sysname_total > 0){
				$sys_name = generate_slug();
			}
		}else{
			$sys_name = generate_slug();
		}
		$post_id = db_insert(DB_LEFT.'_posts',array('id',$id),array( 'name','title' , 'body' ,'keyword' ,'tags','description' , 'sys_name' ,'date' , 'category' ,'in_blog','views','allow_comment','template'),array(escape_string($name), escape_string($title), $info,escape_string($keyword),$tags,escape_string($description),$sys_name,($createTime?$createTime:time()), $category ,$in_blog,$views,$allow_comment,$template));
		if(!$post_id){
			return array('post_id'=>0,'sys_name'=>'');
		}
		$tag_posts = getOption('tag_posts');
		if($tag_posts['content']){
			$tag_posts = unserialize($tag_posts['content']);
		}else{
			$tag_posts = array();
		}
		foreach($taglist as $val){
			if (!is_array($tag_posts[$val])) {
				$tag_posts[$val] = array();
			}
			if(!in_array($post_id,$tag_posts[$val])){
				$tag_posts[$val][] = $post_id;
			}
		}
		foreach($old_tags as $val){
			if(is_array($taglist) && !in_array($val,$taglist)){
				$_tag_posts = array();
				foreach($tag_posts[$val] as $v){
					if($v != $post_id){
						$_tag_posts[] = $v;
					}
				}
				$tag_posts[$val] = $_tag_posts;
			}
		}
		setOption('tag_posts',serialize($tag_posts));
		if($id > 0){
			db_query("UPDATE `".DB_LEFT."_comment` SET `post_cat` = '".$categories[$category]['link']."',`post_slug` = '$sys_name' WHERE `post_id` = '$post_id'");
		}else{
			db_insert(DB_LEFT.'_item_plugin',array('id',''),array('item_id','item_type','plugin'),array($post_id,'post',$data['plugin']));
		}
		if(!$without_attachment){
			$attNos = $data['no'];
			$inlist = array();
			for($i=1;$i<=$attNos;$i++){
				$attid = intval($data['attid_'.$i]);
				if($data['att_'.$i]){
					$inlist[] = db_insert(DB_LEFT.'_attachment',array('id',$attid),array('post_id','file_name','date','downloads'),array($post_id,str_replace(BASE_URL.ATTACHMENT_DIR,ATTACHMENT_DIR,$data['att_'.$i]),$data['attdate_'.$i]?intval($data['attdate_'.$i]):time(),intval($data['atttimes_'.$i])));
				}
			}
			if (count($inlist) > 0) {
				db_query("DELETE FROM `".DB_LEFT."_attachment` WHERE `id` NOT IN (".implode(',',$inlist).") AND `post_id` = '$post_id'");
			}
		}

		if(!$without_custom_field){
			save_custom_field($data,'post',$post_id);
		}
		return array('post_id'=>$post_id,'sys_name'=>$sys_name);
	}

//API for Category insert
	function category_insert($data=array(),$without_custom_field = false){
		if(!$data){
			$data = $_POST;
		}
		if(!$data['name']){return false;}
		$id = intval($data['id']);
		$sys_name = strtolower($data['link']);
		if($sys_name){
			$sysname_total = db_total_nocache("SELECT COUNT(*) FROM `".DB_LEFT."_category` WHERE `link` = '$sys_name' and `id` != '$id' ");
			if($sysname_total > 0){
				$sys_name = generate_slug();
			}
		}else{
			$sys_name = generate_slug();
		}
		$cat_id = db_insert(DB_LEFT.'_category',array('id',$id),array('name' , 'link' , 'title' , 'description' , 'keyword' , 'sort_word','parent_id','template'),array(escape_string($data['name']), $sys_name, escape_string($data['title']), escape_string($data['description']), escape_string($data['keyword']), escape_string($data['sort_word']), intval($data['parent_id']),$data['template']));
		$rows = db_arrays_nocache("SELECT * FROM `".DB_LEFT."_category`");
		setOption('categories',serialize($rows));
		if($id == 0){
			db_insert(DB_LEFT.'_item_plugin',array('id',''),array('item_id','item_type','plugin'),array($cat_id,'category',$data['plugin']));
		}
		if(!$without_custom_field){
			save_custom_field($data,'category',$cat_id);
		}
		return array('cat_id'=>$cat_id,'sys_name'=>$sys_name);
	}

	function save_custom_field($data,$type,$item_id){
		$cfNos = $data['cfno'];
		$savelist = array();
		$inlist = array();
		for($i=1;$i<=$cfNos;$i++){
			$cfid = intval($data['cfid['.$i.']']);
			if($data['cfname'][$i]){
				switch($data['cftype'][$i]){
					case 'file':
						$cfvalue = str_replace(BASE_URL.ATTACHMENT_DIR,ATTACHMENT_DIR,$data['cfvalue'][$i]);
					break;
					case 'html':
						$cfvalue = toggle_attachment($data['cfvalue'][$i]);
					break;
					case 'select':
						$cfvalue = serialize($data['cfvalue'][$i]);
					break;
					default:
						$cfvalue = $data['cfvalue'][$i];
				}
				$inlist[] = db_insert(DB_LEFT.'_item_data',array('id',$cfid),array('item_id','item_type','data_type','name','value'),array($item_id,$type,$data['cftype'][$i],$data['cfname'][$i],$cfvalue));
				if($data['savelist'][$i]){
					$savelist[] = array('name'=>$data['cfname'][$i],'type'=>$data['cftype'][$i],'options'=>$data['cfoption'][$i]);
				}
			}
		}
		db_query("DELETE FROM `".DB_LEFT."_item_data` WHERE `id` NOT IN (".implode(',',$inlist?$inlist:array(0)).") AND `item_id` = '$item_id' AND `item_type` = '$type'");
		$cfdata = getOption('custom_'.$type.'_field');
		if($cfdata){
			$cfdata = unserialize($cfdata['content']);
		}
		if($data['deletelist']){
			$deletelist = explode(',',rtrim($data['deletelist'],','));
			foreach($cfdata as $key=>$val){
				if(!in_array($key,$deletelist)){
					$_cfdata[$key] = $val;
				}
			}
			$cfdata = $_cfdata;
		}
		if(count($savelist) > 0){
			foreach($savelist as $val){
				if(!$cfdata[$val['name']]){
					$cfdata[$val['name']] = $val;
				}
			}
		}
		setOption('custom_'.$type.'_field',serialize($cfdata));
	}

	function get_custom_field($param){
		if(!$param['item_id'] || !$param['item_type']){
			return false;
		}
		$data = array();
		$where = " WHERE `item_type` = '".$param['item_type']."' AND `item_id` = '".$param['item_id']."' ";
		if($param['name']){
			$where .= " AND `name` = '".$param['name']."'";
		}
		if($prams['name']){
			$data = db_array("SELECT `name`,`value`,`data_type` FROM `".DB_LEFT."_item_data` ".$where);
			if($data['data_type'] == 'select'){
				$data['value'] = unserialize($data['value']);
			}
		}else{
			$rows = db_arrays("SELECT `name`,`value`,`data_type` FROM `".DB_LEFT."_item_data` ".$where);
			foreach($rows as $val){
				if($val['data_type'] == 'select'){
					$val['value'] = unserialize($val['value']);
				}
				$data[$val['name']] = $val['value'];
			}
		}
		return $data;
	}

//API comment insert
	function comment_insert($data = array()){
		global $categories;
		if(!$data){
			$data = $_POST;
		}
		$comment = nl2br(escape_string(js_unescape($data['info'])));
		$post_id = intval(js_unescape($data['postID']));
		$website = escape_string(js_unescape($data['website']));
		$email = js_unescape($data['email']);
		$name = escape_string(js_unescape($data['name']));
		$remember = intval($data['remember']);
		$row = db_array_nocache("SELECT `name`,`allow_comment`,`category`,`sys_name` FROM `".DB_LEFT."_posts` WHERE `id` = '$post_id'");
		if($_SESSION['hashcode'] != md5(js_unescape($data['code']))||!checkemail($email)||!$comment||!$row['allow_comment']){
			return array('status'=>'0','status_code'=>_t('Please Check Verification Code,Email and comment and try again!'));
		}else{
			$post_name = $row['name'];
			$post_cat = $categories[$row['category']]['link'];
			$post_slug = $row['sys_name'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$id = db_insert(DB_LEFT."_comment",array('id',null),array('name','email' ,'website','info','post_id','post_name','post_cat','post_slug','date','ip','reply_date'),array($name,$email,$website,$comment,$post_id,$post_name,$post_cat,$post_slug,time(),$ip,0));
			if($remember == 1){
				setcookie('cname',$name,time()+31536000);
				setcookie('cemail',$email,time()+31536000);
				setcookie('cwebsite',$website,time()+31536000);
			}else{
				setcookie('cname',null,time()-60);
				setcookie('cemail',null,time()-60);
				setcookie('cwebsite',null,time()-60);
			}
			unset($_SESSION['hashcode']);
			if($id>0){
				return array('status'=>'1','status_code'=>_t('Submit successfully,thank you!'));
			}
			return array('status'=>'0','status_code'=>db_error());
		}
	}

//API for links insert
	function links_insert($data){
		if(!$data){
			$data = $_POST;
		}
		$id = $data['id'];
		$url = ltrim($data['url'],'/');
		$reqs = $data['reqs'];
		ksort($reqs);
		$plugin = $data['plugin'];
		$row = db_array_nocache("SELECT `lid` FROM `".DB_LEFT."_links` WHERE `url` = '$url'");
		if($id != $row['lid'] && $row['lid'] > 0){
			return array('lid'=>false);
		}
		$lid = db_insert(DB_LEFT.'_links',array('lid',$id),array('request','url','plugin'),array(serialize($reqs),$url,$plugin));
		return array('lid'=>$lid,'url'=>$url,'request'=>$reqs);
	}

	function getLink($plugin,$reqs=array()){
		if(!isPluginInstall($plugin)){
			return false;
		}	
		$reqs['action'] = 'pluginHook';
		$reqs['plugin'] = $plugin;
		ksort($reqs);
		$row = db_array_nocache("SELECT * FROM `".DB_LEFT."_links` WHERE `plugin` = '$plugin' AND `request` = '".serialize($reqs)."'");
		return $row;
	}

	function output_json($result){
		header('Content-type: application/json');
		exit(json_encode($result));
	}

	function _posts($row,$post_output_template = false){
		global $categories;
		if(!is_array($row)){
			return ;
		}
		$tags = explode(',',$row['tags']);	
		foreach($tags as $key=>$val){
			if(trim($val)){
				$tag_str .= '<a href="'.show_link_tag($val).'">'.$val.'</a> ';
			}
		}
		if($post_output_template&&file_exists($post_output_template)){
			ob_start();
			include($post_output_template);	
			$content = ob_get_contents();
			ob_clean();
			return $content;
		}else{
			return '<div class="blog_text" id="post-'.$row['id'].'"><h2 class="blog_title"><a href="'.show_link_page($categories[$row['category']]['link'],$row['sys_name']).'">'.$row['name'].'</a></h2><div class="post_info"><div class="list_info"><p class="list_date">'.date(_t('F,jS Y'),$row['date']).'</p><p class="list_views">'.$row['views'].' '._t('Views').'</p><p class="div_clear"></p></div>'.postPreview($row['body']).' <span title="'.BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']).'" class="readmore">'._t('Read More').'</span></div><p>'._t('Tag').' '.$tag_str.'</p></div>';			
		}
	}

	function _comment($val,$k,$last_no,$comment_link,$comment_output_template = false){
		if(!is_array($val)){
			return ;
		}
		if($comment_output_template&&file_exists($comment_output_template)){
			ob_start();
			include($comment_output_template);	
			$content = ob_get_contents();
			ob_clean();
			return $content;
		}else{
			if(!preg_match('|https?://.{2,}|',$val['website'])){
				$val['website'] = 'javascript:void(0);';
			}
			return '<fieldset><legend><a name="comment_'.$k.'"></a><a href="'.$comment_link.'#comment_'.$k.'" rel="nofollow">#'.$last_no.'</a> '.($val['website']?'<a href="'.$val['website'].'" rel="external nofollow">'.$val['name'].'</a>':$val['name']).' '.date(_t('F,jS Y'),$val['date']).'</legend>'.$val['info'].'</fieldset>';			
		}
	}

	function pluginList(){
		$plugin_installed = array();
		$row = getOption('plugin_installed');
		if($row['content']){
			$plugin_installed = unserialize($row['content']);
		}
		$plugin_list = array();
		if(is_dir(SITE_HOME.'_plugin/')){
			$d = dir(SITE_HOME.'_plugin/');
			while (false !== ($entry = $d->read())){
				if($entry !='.' && $entry !='..' && file_exists(SITE_HOME.'_plugin/'.$entry.'/plugin_config.php')){
					include(SITE_HOME.'_plugin/'.$entry.'/plugin_config.php');
					$plugin_config['installed'] = $plugin_installed[$plugin_config['name']]?true:false;
					$plugin_config['directory'] = $entry;
					$plugin_list[$plugin_config['name']] = $plugin_config;		
				}
			}
			$d->close();
		}
		return $plugin_list;
	}

	function siteList(){
		if(!is_dir(ROOT_DIR.'_sites')){
			mkdir(ROOT_DIR.'_sites');
			return array();
		}
		$site_list = array();
		if(is_dir(ROOT_DIR.'_sites/')){
			$d = dir(ROOT_DIR.'_sites/');
			while (false !== ($entry = $d->read())) {
				if($entry !='.' && $entry !='..' && file_exists(ROOT_DIR.'_sites/'.$entry.'/inc/db.php')){
					$site_config = array();
					include(ROOT_DIR.'_sites/'.$entry.'/inc/db.php');
					$site_list[$entry] = array('database_type'=>$database_type,'db_url'=>$db_url,'db_name'=>$db_name,'db_left'=>$db_left,'db_user'=>$db_username,'db_passwd'=>$db_passwd,'db_port'=>$db_port);		
				}
			}
			$d->close();			
		}
		return $site_list;
	}

	function get_data_from_url($url){
		if(extension_loaded('curl')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
			curl_setopt($ch, CURLOPT_MAXREDIRS,10);
			curl_setopt($ch, CURLOPT_TIMEOUT,120);
			$response = curl_exec_follow($ch,10);
			curl_close($ch);
			return $response;			
		}elseif(ini_get('allow_url_fopen')){
			return file_get_contents($url);
		}else{
			fsockopen_follow($url);
		}
	}

	function curl_exec_follow($ch,$maxredirect) {
	    $mr = max(5,intval($maxredirect));
	    if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
	        curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
	    } else {
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	        if ($mr > 0) {
	            $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	            $rch = curl_copy_handle($ch);
	            curl_setopt($rch, CURLOPT_HEADER, true);
	            curl_setopt($rch, CURLOPT_NOBODY, true);
	            curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
	            curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
	            do {
	                curl_setopt($rch, CURLOPT_URL, $newurl);
	                $header = curl_exec($rch);
	                if (curl_errno($rch)) {
	                    $code = 0;
	                } else {
	                    $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
	                    if ($code == 301 || $code == 302) {
	                        preg_match('/Location:(.*?)\n/i', $header, $matches);
	                        $newurl = trim(array_pop($matches));
	                    } else {
	                        $code = 0;
	                    }
	                }
	            } while ($code && --$mr);
	            curl_close($rch);
				if($newurl){
					curl_setopt($ch, CURLOPT_URL, $newurl);
				}else{
					return false;
				}
	        }
	    }
	    return curl_exec($ch);
	} 

	function fsockopen_follow($url,$mr=5,$cr=0){
		if($cr >= $mr){
			return false;
		}else{
			$cr += 1;
		}
		$url = str_replace('http://','',$url);
		$urls = explode('/',$url);
		$url_str = substr($url,strlen($urls[0]));
		$fp = fsockopen($urls[0], 80, $errno, $errstr, 120);
		if (!$fp) {
				echo "$errstr ($errno)<br />\n";
		} else {
				$out = "GET ".$url_str." HTTP/1.1\r\n";
				$out .= "Host: ".$urls[0]."\r\n";
				$out .= "Connection: Close\r\n\r\n";
				fwrite($fp, $out);
				while (!feof($fp)) {
						$data .= fgets($fp, 1024);
				}
				fclose($fp);
		}
		if(preg_match("|HTTP/1.1\s302.*\n|i", $data)||preg_match("|HTTP/1.1\s301.*\n|i", $data)){
			preg_match('/Location:(.*?)\n/i', $data, $matches);
    $newurl = trim(array_pop($matches));
			return fsockopen_follow($newurl,$mr,$cr);
		}else{
			preg_match("/\r\n\r\n(.+)/is", $data, $out);
			$output = $out[1];
			return $output;
		}
	}

	function alert($str,$to=false){
		ob_end_clean();
		$page_theme = get_page_themes();
		if($page_theme['alert']){
			include(THEME_DIR.$page_theme['alert']);
		}else{
			include(INCLUDE_DIR.'alert.php');
		}
		exit();
	}

	function _goto($url){
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<script type="text/javascript">
<!--
	location.href = '<?php echo $url;?>';
//-->
</script>
</body>
</html>
<?php
	exit();
	}

	function sweetrice_debug($errno, $errstr, $errfile, $errline){
		global $global_setting;
		if($errno){
			$errors = array('file'=>$errfile,'line'=>$errline,'message'=>$errstr);
		}
		ob_end_clean();
		$page_theme = get_page_themes();
		if($page_theme['error_report']){
			include(THEME_DIR.$page_theme['error_report']);
		}else{
			include(INCLUDE_DIR.'error_report.php');
		}
		exit();
	}

	function error_report(){
		global $global_setting;
		if(function_exists('error_get_last')){
			$errors = error_get_last();
			$debug = 1;
		}else{
			$errors = debug_backtrace();
			$debug = 2;
		}
		if($debug == 1 && $errors['type'] != 1){
			return ;
		}
		ob_end_clean();
		$page_theme = get_page_themes();
		if($page_theme['error_report']){
			include(THEME_DIR.$page_theme['error_report']);
		}else{
			include(INCLUDE_DIR.'error_report.php');
		}
		exit();
	}
	
	function dashboard_acts(){
		return array(
		'category'=>array('title'=>_t('Category'),'file'=>'do_category.php','type'=>1,'child'=>
			array(
				array('title'=>_t('List'),'request'=>array('type'=>'category')),
				array('title'=>_t('Create'),'request'=>array('type'=>'category','mode'=>'insert'))
			)
		),
		'post'=>array('title'=>_t('Post'),'file'=>'do_post.php','type'=>1,'child'=>
			array(
				array('title'=>_t('List'),'request'=>array('type'=>'post')),
				array('title'=>_t('Create'),'request'=>array('type'=>'post','mode'=>'insert'))
			)
		),
		'comment'=>array('title'=>_t('Comment'),'file'=>'do_comment.php','type'=>3,'request'=>array('type'=>'comment')),
		'attachment'=>array('title'=>_t('Attachment'),'file'=>'do_attachment.php','type'=>3,'request'=>array('type'=>'attachment')),
		'setting'=>array('title'=>_t('Setting'),'type'=>2,'child'=>
			array(
				array('title'=>_t('General'),'file'=>'do_setting.php','request'=>array('type'=>'setting')),
				array('mustBase'=>true,'title'=>'.htaccess','file'=>'do_htaccess.php','request'=>array('type'=>'htaccess')),
				array('title'=>_t('URL Redirect'),'file'=>'do_urlredirect.php','request'=>array('type'=>'url_redirect'))
			)
		),
		'permalinks'=>array('title'=>_t('Permalinks'),'file'=>'do_permalinks.php','type'=>1,'child'=>array(
				array('title'=>_t('System'),'request'=>array('type'=>'permalinks','mode'=>'system')),
				array('title'=>_t('Custom'),'request'=>array('type'=>'permalinks','mode'=>'custom'))
			)
		),
		'plugins'=>array('title'=>_t('Plugin list'),'file'=>'do_plugins.php','request'=>array('type'=>'plugins'),'type'=>5,'child'=>pluginChild()),
		'ad'=>array('title'=>_t('Ads'),'file'=>'do_ad.php','type'=>3,'request'=>array('type'=>'ad')),
		'password'=>array('title'=>_t('Password'),'file'=>'do_password.php','type'=>4),
		'plugin'=>array('title'=>_t('Plugin'),'file'=>'do_plugin.php','type'=>4),
		'track'=>array('title'=>_t('Track'),'file'=>'do_track.php','type'=>3,'request'=>array('type'=>'track')),
		'link'=>array('title'=>_t('Links'),'file'=>'do_link.php','type'=>3,'request'=>array('type'=>'link')),
		'sitemap'=>array('title'=>_t('Sitemap'),'file'=>'do_sitemap.php','type'=>3,'request'=>array('type'=>'sitemap')),
		'theme'=>array('title'=>_t('Theme'),'file'=>'do_theme.php','type'=>3,'request'=>array('type'=>'theme')),
		'media_center'=>array('title'=>_t('Media Center'),'file'=>'do_media_center.php','type'=>3,'request'=>array('type'=>'media_center')),
		'media'=>array('title'=>_t('Media'),'file'=>'do_media.php','type'=>4,'request'=>array('type'=>'media')),
		'image'=>array('title'=>_t('Image'),'file'=>'do_image.php','type'=>4,'request'=>array('type'=>'image')),
		'cache'=>array('title'=>_t('Cache'),'file'=>'do_cache.php','type'=>1,'child'=>
			array(
				array('title'=>_t('Expired'),'request'=>array('type'=>'cache'),'ncr'=>true),
				array('title'=>_t('Full'),'request'=>array('type'=>'cache','mode'=>'full'),'ncr'=>true)
			)	
		),
		'update'=>array('mustBase'=>true,'title'=>_t('Update'),'file'=>'do_update.php','type'=>1,'child'=>
			array(
				array('title'=>_t('Check'),'request'=>array('type'=>'update'))
			)	
		),
		'sites'=>array('title'=>_t('Sites'),'mustBase'=>true,'file'=>'do_sites.php','type'=>1,'child'=>
			array(
				array('title'=>_t('List'),'request'=>array('type'=>'sites')),
				array('title'=>_t('Create'),'request'=>array('type'=>'sites','mode'=>'insert'))
			)
		),		
		'data'=>array('title'=>_t('Data'),'file'=>'do_data.php','type'=>1,'child'=>
			array(
				array('title'=>_t('Data Backup'),'request'=>array('type'=>'data','mode'=>'db_backup')),
				array('title'=>_t('Data Import'),'request'=>array('type'=>'data','mode'=>'db_import')),
				array('title'=>_t('Data Converter'),'request'=>array('type'=>'data','mode'=>'db_converter')),
				array('title'=>_t('Data Optimizer'),'request'=>array('type'=>'data','mode'=>'db_optimizer')),
				array('title'=>_t('Sql Execute'),'request'=>array('type'=>'data','mode'=>'sql_execute')),
				array('title'=>_t('Transfer website'),'request'=>array('type'=>'data','mode'=>'transfer'))
			)	
		),
		'signout'=>array('title'=>_t('Logout'),'file'=>'do_signout.php','type'=>3,'request'=>array('type'=>'signout')),
		'signin'=>array('title'=>_t('Login'),'file'=>'do_signin.php','type'=>4)
		);
	}
	
	function pluginChild(){
		foreach(pluginList() as $key=>$val){
			if($val['installed']){
				$pluginChild[] = array('name'=>'dashboard/'.$key,'title'=>$key,'request'=>array('type'=>'plugin','plugin'=>$key));
			}
		}
		return $pluginChild;
	}

	function dashboard_actions(){
		$dashboard_acts = dashboard_acts();
		foreach($dashboard_acts as $key=>$val){
			switch($val['type']){
				case 1:case 3:case 4:case 5:
					$actions[$key] = $val;
				break;
				case 2:
					foreach($val['child'] as $v){
						$actions[$v['request']['type']] = $v;
					}
				break;
			}
		}
		return $actions;
	}

	function getCategoryPosts($limit=10,$offset=0,$post_type = 'show'){
		$category_list = array();
		foreach(subCategory() as $cs){
			$data = getPosts(array(
				'category_ids' => $cs['id'],
				'limit' => array($offset,$limit),
				'order' => ' ps.`id` DESC ',
				'custom_field' => true,
				'post_type' => $post_type
			));
			$post_rows = $data['rows'];
			$total_post = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` WHERE `in_blog` = 1 AND `category` = '".$cs['id']."'");
			$category_list[] = array('post_rows'=>$post_rows,'total_post'=>$total_post,'category_id'=>$cs['id'],'category_level'=>$cs['level']);
		}
		return $category_list;
	}

	function getUncategoryPosts($limit=20,$offset=0,$post_type = 'show'){
		$data = getPosts(array(
			'field' => 'ps.`id`,ps.`sys_name`,ps.`name`,ps.`category`',
			'category_ids' => 0,
			'limit' => array($offset,$limit),
			'order' => ' ps.`id` DESC ',
			'custom_field' => true,
			'post_type' => $post_type
		));
		$total_post = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` WHERE `in_blog` = 1 AND `category` = '0'");
		$data['total_post'] = $total_post;
		return $data;
	}

	function getTagLists($limit=100){
		return db_arrays("SELECT `tags` FROM `".DB_LEFT."_posts` WHERE `tags` !='' AND `in_blog` = 1 ".get_limit_sql(0,$limit));
	}

	function getCommentByPosts($post_ids){
		if(is_array($post_ids)){
			$post_ids = implode(',',$post_ids);
		}
		$rows = db_arrays("SELECT * FROM `".DB_LEFT."_comment` WHERE `post_id` IN ($post_id) ");
		foreach($rows as $key=>$val){
			$data[$val['post_id']] = $val;
		}
		return $data;
	}

	function getPosts($param = array()){
		switch($param['post_type']){
			case 'show':
				$where = " ps.`in_blog` = 1 ";
			break;
			case 'hide':
				$where = " ps.`in_blog` = 0 ";
			break;
			default:
				$where = " 1=1 ";	
		}
		if(is_array($param['ids']) && count($param['ids'])){
			$where .= " AND ps.`id` IN (".implode(',',$param['ids']).")";
		}elseif($param['ids']){
			$where .= " AND ps.`id` IN (".$param['ids'].")";
		}
		if(is_array($param['category_ids']) && count($param['category_ids']) > 0){
			$where .= " AND ps.`category` IN (".implode(',',$param['category_ids']).")";
		}elseif(isset($param['category_ids'])){
			$where .= " AND ps.`category` IN (".$param['category_ids'].")";
		}
		if($param['tag']){
			$tag_sql = '';
			$tag_posts = getOption('tag_posts','serialize');
			if($tag_posts['output']){
				$tag_posts = $tag_posts['output'];
			}else{
				$tag_posts = array();
			}
			$tag_ids = array();
			if(is_array($param['tag'])){
				foreach($param['tag'] as $val){
					if(is_array($tag_posts[$val])){
						$tag_ids = array($tag_ids,$tag_posts[$val]);
					}
				}
			}else{
				$tag_ids = $tag_posts[$param['tag']];
			}
			if(count($tag_ids) > 0){
				$tag_sql = " ps.`id` IN(".implode(',',$tag_ids).")";
			}
			$cf_where = '';
			$cf_sql = " `item_type` = 'post' ";
			$post_sql = '';
			$cf_sql_str = '';
			if(is_array($param['tag'])){
				foreach($param['tag'] as $val){
					$cf_sql_str .= "OR `value` LIKE '%".db_escape($val)."%' ";
					$post_sql .= "OR ps.`title` LIKE '%".db_escape($val)."%' OR ps.`keyword` LIKE '%".db_escape($val)."%' OR ps.`body` LIKE '%".db_escape($val)."%' ";
				}
				$cf_sql .= " AND (".substr($cf_sql_str,2).")";
				$post_sql = substr($post_sql,2);
			}else{
				$cf_sql .= " AND `value` LIKE '%".db_escape($param['tag'])."%'";
				$post_sql .= "ps.`title` LIKE '%".db_escape($param['tag'])."%' OR ps.`keyword` LIKE '%".db_escape($param['tag'])."%' OR ps.`body` LIKE '%".db_escape($param['tag'])."%' ";
			}
			$data = db_fetch(array(
				'table' => DB_LEFT.'_item_data',
				'where' => $cf_sql
			));
			foreach($data['rows'] as $val){
				$cf_ids[] = $val['item_id'];
			}
			if(count($cf_ids)){
				$cf_where = " ps.`id` IN(".implode(',',$cf_ids).")";
			}
			$where .= " AND (";
			if($tag_sql){
				$where .= " ".$tag_sql." ";
				$left_or = true;
			}
			if($cf_where){
				if($left_or){
					$where .= " OR ".$cf_where." ";
				}else{
					$where .= " ".$cf_where." ";
				}
			}
			if($post_sql){
				if($left_or){
					$where .= " OR ".$post_sql." ";
				}else{
					$where .= " ".$post_sql." ";
				}
			}
			$where .= " )";
		}
		if(is_array($param['ex_ids'])){
			$where .= " AND ps.`id` NOT IN (".implode(',',$param['ex_ids']).")";
		}elseif($param['ex_ids']){
			$where .= " AND ps.`id` NOT IN (".$param['ex_ids'].")";
		}
		
		if(is_array($param['ex_cids'])){
			$where .= " AND ps.`category` NOT IN (".implode(',',$param['ex_cids']).")";
		}elseif($param['ex_cids']){
			$where .= " AND ps.`category` NOT IN (".$param['ex_cids'].")";
		}

		if(is_array($param['where'])){
			$param['where'][] = $where;
			$where = $param['where'];
		}elseif($param['where'] && $where){
			$where = $param['where'].' AND '.$where;
		}
			
		$order = $param['order']?$param['order']." ":"ps.`id` DESC ";

		$filed = $param['field']?$param['field']:'ps.*';
		if(!$param['table']){
			$table = "`".DB_LEFT."_posts` AS ps ";
		}else{
			$table = $param['table'];
		}
		$data = db_fetch(array(
			'table' => $table,
			'field' => $filed,
			'where' => $where,
			'order' => $order,
			'limit' => $param['limit'],
			'pager' => $param['pager'],
			'debug' => $param['debug']
		));
		$rows = $data['rows'];
		if($param['custom_field'] && count($rows)){
			foreach($rows as $val){
				$ids[] = $val['id'];
			}
			$custom_field_rows = db_arrays("SELECT * FROM `".DB_LEFT."_item_data` WHERE `item_id` IN (".implode(',',$ids).") AND `item_type` = 'post'");
			foreach($custom_field_rows as $key=>$val){
				$cfdata[$val['item_id']][$val['name']] = $val;
			}
			foreach($rows as $key=>$val){
				$val['custom_field'] = $cfdata[$val['id']];
				$rows[$key] = $val;
			}
		}
		if($param['fetch_one']){
			return $rows[0];
		}
		$data['rows'] = $rows;
		return $data;
	}

	function getPostsOne($param){
		$param['fetch_one'] = true;
		return getPosts($param);
	}

	function removePosts($param = array()){
		$data = getPosts($param);
		foreach($data['rows'] as $val){
			$pids[] = $val['id'];
		}
		if(count($pids)){
			db_query("DELETE FROM `".DB_LEFT."_posts` WHERE `id` IN (".implode(',',$pids).")");
			db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` IN (".implode(',',$pids).") AND `item_type` = 'post'");
			db_query("DELETE FROM `".DB_LEFT."_item_data` WHERE `item_id` IN (".implode(',',$pids).") AND `item_type` = 'post'");
			db_query("DELETE FROM `".DB_LEFT."_attachment` WHERE `post_id` IN (".implode(',',$pids).")");
		}
	}

	function getCategories($param = array()){
		$where = " 1 = 1 ";
		if(is_array($param['ids']) && count($param['ids']) > 0){
			$where .= " AND c.`id` IN (".implode(',',$param['ids']).")";
		}elseif($param['ids']){
			$where .= " AND c.`id` IN (".$param['ids'].")";
		}
		
		if($param['tag']){
			$cf_where = '';
			$cf_sql = " `item_type` = 'category' ";
			$cat_sql = '';
			$cf_sql_str = '';
			if(is_array($param['tag'])){
				foreach($param['tag'] as $val){
					$cf_sql_str .= "OR `value` LIKE '%".db_escape($val)."%' ";
					$cat_sql .= "OR c.`title` LIKE '%".db_escape($val)."%' OR c.`name` LIKE '%".db_escape($val)."%' OR c.`sort_word` LIKE '%".db_escape($val)."%' ";
				}
				$cf_sql .= " AND (".substr($cf_sql_str,2).")";
				$cat_sql = substr($post_sql,2);
			}else{
				$cf_sql .= " AND `value` LIKE '%".db_escape($param['tag'])."%'";
				$cat_sql .= "c.`title` LIKE '%".db_escape($param['tag'])."%' OR c.`name` LIKE '%".db_escape($param['tag'])."%' OR c.`sort_word` LIKE '%".db_escape($param['tag'])."%' ";
			}
			$data = db_fetch(array(
				'table' => DB_LEFT.'_item_data',
				'where' => $cf_sql
			));
			foreach($data['rows'] as $val){
				$cf_ids[] = $val['item_id'];
			}
			if(count($cf_ids)){
				$cf_where = " c.`id` IN(".implode(',',$cf_ids).")";
			}
			$where .= " AND (".$cf_where." OR ".$cat_sql.")";
		}
		
		if(is_array($param['ex_ids'])){
			$where .= " AND c.`id` NOT IN (".implode(',',$param['ex_ids']).")";
		}elseif($param['ex_ids']){
			$where .= " AND c.`id` NOT IN (".$param['ex_ids'].")";
		}

		if(is_array($param['ex_pids'])){
			$where .= " AND c.`parent_id` NOT IN (".implode(',',$param['ex_pids']).")";
		}elseif($param['ex_pids']){
			$where .= " AND c.`parent_id` NOT IN (".$param['ex_pids'].")";
		}

		if(is_array($param['where'])){
			$param['where'][] = $where;
			$where = $param['where'];
		}elseif($param['where']){
			$where = $param['where']." AND ".$where;
		}
		$order = ($param['order']?" ".$param['order']." ":" c.`id` DESC ");

		$filed = $param['field']?$param['field']:'c.*';
		if(!$param['table']){
			$table = "`".DB_LEFT."_category` as c ";
		}else{
			$table = $param['table'];
		}
		$data = db_fetch(array(
			'table' => $table,
			'field' => $filed,
			'where' => $where,
			'order' => $order,
			'limit' => $param['limit'],
			'pager' => $param['pager'],
			'debug' => $param['debug']
		));

		$rows = $data['rows'];
		if($param['custom_field'] && count($rows)){
			foreach($rows as $val){
				$ids[] = $val['id'];
			}
			$custom_field_rows = db_arrays("SELECT * FROM `".DB_LEFT."_item_data` WHERE `item_id` IN (".implode(',',$ids).") AND `item_type` = 'category'");
			foreach($custom_field_rows as $key=>$val){
				$cfdata[$val['item_id']][$val['name']] = $val;
			}
			foreach($rows as $key=>$val){
				$val['custom_field'] = $cfdata[$val['id']];
				$rows[$key] = $val;
			}
		}
		if($param['fetch_one']){
			return $rows[0];
		}
		$data['rows'] = $rows;
		return $data;
	}

	function getCategoriesOne($param){
		$param['fetch_one'] = true;
		return getCategories($param);
	}

	function removeCategories($param = array()){
		$data = getCategories($param);
		foreach($data['rows'] as $val){
			$pids[] = $val['id'];
		}
		if(count($pids)){
			db_query("DELETE FROM `".DB_LEFT."_category` WHERE `id` IN (".implode(',',$pids).")");
			db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` IN (".implode(',',$pids).") AND `item_type` = 'category'");
			db_query("DELETE FROM `".DB_LEFT."_item_data` WHERE `item_id` IN (".implode(',',$pids).") AND `item_type` = 'category'");
			db_query("UPDATE `".DB_LEFT."_posts` SET `category` = '0' WHERE `category` IN (".implode(',',$pids).")");
		}
		$rows = db_arrays_nocache("SELECT * FROM `".DB_LEFT."_category`");
		setOption('categories',serialize($rows));
	}

	function initNumsSetting($setting){
		$setting['postCategories'] = $setting['postCategories']?$setting['postCategories']:10;
		$setting['postUnCategories'] = $setting['postUnCategories']?$setting['postUnCategories']:10;
		$setting['tags'] = $setting['tags']?$setting['tags']:100;
		$setting['postCategory'] = $setting['postCategory']?$setting['postCategory']:15;
		$setting['postHome'] = $setting['postHome']?$setting['postHome']:15;
		$setting['postPins'] = $setting['postPins']?$setting['postPins']:12;
		$setting['postTag'] = $setting['postTag']?$setting['postTag']:10;
		$setting['postRelated'] = $setting['postRelated']?$setting['postRelated']:10;
		$setting['postRssfeed'] = $setting['postRssfeed']?$setting['postRssfeed']:50;
		$setting['commentList'] = $setting['commentList']?$setting['commentList']:15;
		$setting['commentPins'] = $setting['commentPins']?$setting['commentPins']:12;
		$setting['category_link_per_page'] = $setting['category_link_per_page']?$setting['category_link_per_page']:50;
		$setting['post_link_per_page'] = $setting['post_link_per_page']?$setting['post_link_per_page']:50;
		$setting['custom_link_per_page'] = $setting['custom_link_per_page']?$setting['custom_link_per_page']:50;
		return $setting;
	}

	function init_lang($lang_file){
		global $lang_data;
		$langData = include_once($lang_file);
		foreach($langData as $key=>$val){
			$lang_data[md5($key)] = $val;
		}
	}

	function _t($text){
		global $lang_data;
		if($lang_data[md5($text)]){
			return $lang_data[md5($text)];
		}else{
			return $text;
		}
	}
	function _e($text){
		echo _t($text);
	}

	function toggle_attachment($content,$type = 'front'){
		$relative_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
		$absolute_link = array('src="'.BASE_URL.ATTACHMENT_DIR,'data="'.BASE_URL.ATTACHMENT_DIR,'value="'.BASE_URL.ATTACHMENT_DIR);
		if($type == 'front'){
			return str_replace($absolute_link,$relative_link,$content);
		}else{
			return str_replace($relative_link,$absolute_link,$content);
		}
	}

	function download_file($entry){
		$data = file_get_contents($entry);
		ob_end_clean();
		header('Content-Encoding: none');
		header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
		header('Content-Disposition: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="'.basename($entry).'"');
		header('Content-Length: '.strlen($data));
		header('Pragma: no-cache');
		header('Expires: 0');
		die($data);
	}

	function output_content($content,$return_data = false){
		if(substr($content,-3) == '<p>'){
			$content = substr($content,0,-3);
		}
		if(substr($content,0,4) == '</p>'){
			$content = substr($content,4);
		}
		if($return_data){
			return $content;
		}
		echo $content;
	}

	function pagebreak_description($content){
		return mb_substr(str_replace("\n",'',strip_tags($content)),0,200,'UTF-8');
	}

	class mysql_lib{
		public function __construct($db_setting = false){
			if (!$db_setting['url'] || !$db_setting['port'] || !$db_setting['username']) {
				return false;
			}
			$this->db_setting = $db_setting;
			if (MYSQL_LIB == 'mysqli') {
				$this->link = mysqli_connect($db_setting['url'],$db_setting['username'],$db_setting['passwd'],$db_setting['name'],$db_setting['port']);
				$this->connect_error = mysqli_connect_error();
			}else{
				$this->link = mysql_connect($db_setting['url'].($db_setting['port']?':'.$db_setting['port']:''),$db_setting['username'],$db_setting['passwd'],$db_setting['newlink']);
				mysql_select_db($db_setting['name'],$this->link);
				$this->connect_error = $this->error();
			}
		}

		public function error(){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_error($this->link);
			}
			return mysql_error($this->link);
		}

		public function real_escape_string($value){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_real_escape_string($GLOBALS['db_lib']->link,$value);
			}
			return mysql_real_escape_string($value);
		}

		public function query($sql){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_query($this->link,$sql);
			}
			return mysql_query($sql);
		}

		public function fetch_array($result,$result_type = null){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_fetch_array($result,$this->result_type($result_type));
			}
			return mysql_fetch_array($result,$this->result_type($result_type));
		}

		public function fetch_row($result){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_fetch_row($result);
			}
			return mysql_fetch_row($result);
		}

		public function num_fields($result){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_num_fields($result);
			}
			return mysql_num_fields($result);
		}

		public function fetch_field($result){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_fetch_field($result);
			}
			return mysql_fetch_field($result);
		}
		
		public function free_result(&$result){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_free_result($result);
			}
			return mysql_free_result($result);	
		}

		public function stat(){
			if ($this->connect_error) {
				return false;
			}
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_stat($this->link);
			}
			return mysql_stat($this->link);		
		}

		public function close(){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_close($this->link);
			}
			return mysql_close($this->link);		
		}

		public function insert_id(){
			if (MYSQL_LIB == 'mysqli') {
				return mysqli_insert_id($this->link);
			}
			return mysql_insert_id();		
		}

		public function result_type($type){
			if(MYSQL_LIB == 'mysqli'){
				return $type == 'BOTH' ? MYSQLI_BOTH : MYSQLI_ASSOC;
			}
			return $type == 'BOTH' ? MYSQL_BOTH : MYSQL_ASSOC;
		}

		public function db_total($sql){
			$res = $this->query($sql);
			$row = $this->fetch_row($res);
			$this->free_result($res);
			return $row[0];
		}

		public function db_array($sql,$result_type = null){		
			$res = $this->query($sql);
			$row = $this->fetch_array($res,$this->result_type($result_type));
			$this->free_result($res);
			return $row;
		}

		public function db_arrays($sql,$result_type = null){		
			$res = $this->query($sql);
			while($row = $this->fetch_array($res,null,$this->result_type($result_type))){
				$rows[] = $row;
			}
			$this->free_result($res);
			return $rows;
		}

		public function db_insert($table,$_id,$_key,$_val){		
			$_key = '`'.implode('`,`',$_key).'`';
			$_val = "'".implode("','",$_val)."'";
			if($_id[0] && $_id[1] > 0){
				$sql = "REPLACE INTO `".$table."`(`".$_id[0]."`,".$_key.")VALUES('".$_id[1]."',".$_val.")";
			}else{
				$sql = "REPLACE INTO `".$table."`(".$_key.")VALUES(".$_val.")";
			}
			$this->query($sql);
			if($_id[0]){
				$insert_id = $this->insert_id();
				return $insert_id > 0 ? $insert_id : $_id[1];
			}else{
				return true;
			}
		}

		public function db_escape($str){
			if(is_array($str)){
				foreach($str as $key=>$val){
					$str[$key] = $this->real_escape_string($val);
				}
				return $str;
			}
			return $this->real_escape_string($str);
		}

		public function db_unescape($str){
			if(is_array($str)){
				foreach($str as $key=>$val){
					$str[$key] = stripslashes($val);
				}
				return $str;
			}
			return stripslashes($str);
		}

		public function db_list(){
			$table_array = db_arrays_nocache("SHOW TABLES",'BOTH');
			foreach($table_array as $val){
				$val = current($val);
				if(substr($val,0,(strlen(DB_LEFT)+1)) == DB_LEFT.'_'){
					$table_list[] = $val;
				}
			}
			return $table_list;
		}
	}


	class pgsql_lib{
		public function __construct($db_setting = false){
			if (!$db_setting['url'] || !$db_setting['port'] || !$db_setting['name'] || !$db_setting['username']) {
				return false;
			}
			$this->db_setting = $db_setting;
			$this->link = pg_connect('host='.$db_setting['url'].' port='.$db_setting['port'].' dbname='.$db_setting['name'].' user='.$db_setting['username'].' password='.$db_setting['passwd']);
			$this->connect_error = pg_last_error($this->link);
		}

		public function error(){
			return pg_last_error($this->link);
		}

		public function query($sql){
			$sql = str_replace('`','"',$sql);
			return pg_query($this->link,$sql);
		}

		public function fetch_array($result,$result_type = null){
			return pg_fetch_array($result,null,$this->result_type($result_type));
		}

		public function fetch_row($result){
			return pg_fetch_row($result);
		}

		public function num_fields($result){
			return pg_num_fields($result);
		}
		
		public function free_result(&$result){
			return pg_free_result($result);
		}

		public function stat(){
			if ($this->connect_error) {
				return false;
			}
			return pg_version($this->link)?true:false;
		}

		public function close(){
			return pg_close($this->link);	
		}

		public function result_type($type = null){
			return $type == 'BOTH' ? PGSQL_BOTH : PGSQL_ASSOC;
		}

		public function db_total($sql){	
			$res = $this->query($sql);
			$row = $this->fetch_row($res);
			$this->free_result($res);
			return $row[0];
		}

		public function db_array($sql,$result_type = null){
			$res = $this->query($sql);
			$row = $this->fetch_array($res,$this->result_type($result_type));
			$this->free_result($res);
			return $row;
		}

		public function db_arrays($sql,$result_type = null){
			$res = $this->query($sql);
			while($row = $this->fetch_array($res,$this->result_type($result_type))){
				$rows[] = $row;
			}
			$this->free_result($res);
			return $rows;
		}

		public function db_insert($table,$_id,$_key,$_val){
			if($_id[0] && $_id[1] > 0){
				$total = db_total_nocache("SELECT COUNT(*) FROM \"".$table."\" WHERE \"".$_id[0]."\" = '".$_id[1]."'");
				if($total == 1){
					$_sql = " SET ";
					for($i = 0; $i < count($_key); $i++){
						if($i==0){
							$_sql .= " \"".$_key[$i]."\" = '".$_val[$i]."' ";
						}else{
							$_sql .= " , \"".$_key[$i]."\" = '".$_val[$i]."' ";
						}
					}
					$sql = "UPDATE \"".$table."\" ".$_sql." WHERE \"".$_id[0]."\" = '".$_id[1]."'";
				}else{
					$_key = '"'.implode('","',$_key).'"';
					$_val = "'".implode("','",$_val)."'";
					$sql = "INSERT INTO \"".$table."\"(\"".$_id[0]."\",".$_key.")VALUES('".$_id[1]."',".$_val.")";	
				}
				if (!$db_conn) {
					$db_conn = $GLOBALS['pgsql_lib'];
				}
				$this->query($sql);
				return $_id[1];
			}else{
				$_key = '"'.implode('","',$_key).'"';
				$_val = "'".implode("','",$_val)."'";
				if($_id[0]){
					$sql = "INSERT INTO \"".$table."\"(".$_key.")VALUES(".$_val.") RETURNING ".$_id[0];
				}else{
					$tindex = db_array_nocache("SELECT pg_constraint.conname AS pk_name,pg_attribute.attname AS colname FROM pg_constraint INNER JOIN pg_class ON pg_constraint.conrelid = pg_class.oid INNER JOIN pg_attribute ON pg_attribute.attrelid = pg_class.oid AND pg_attribute.attnum = pg_constraint.conkey[1] WHERE pg_class.relname = '".$table."' AND pg_constraint.contype='p'",'ASSOC');
					if($tindex['colname']){
						$n_key = array_flip($_key);
						$nindex = $n_key[$tindex['colname']];
						$total = db_total_nocache("SELECT COUNT(*) FROM \"$table\" WHERE \"".$tindex['colname']."\" = '".$_val[$nindex]."'",$database_type);
						if($total){
							$_sql = " SET ";
							for($i=0; $i<count($_key); $i++){
								if($i==0){
									$_sql .= " \"".$_key[$i]."\" = '".$_val[$i]."' ";
								}else{
									$_sql .= " , \"".$_key[$i]."\" = '".$_val[$i]."' ";
								}
							}
							$sql = "UPDATE \"".$table."\" ".$_sql." WHERE \"".$tindex['colname']."\" = '".$_val[$nindex]."'";
						}else{
							$sql = "INSERT INTO \"".$table."\"(".$_key.")VALUES(".$_val.")";
						}
					}else{
						$sql = "INSERT INTO \"".$table."\"(".$_key.")VALUES(".$_val.")";
					}
				}
				$row = $this->db_array($sql,'ASSOC');
				if($_id[0]){
					return $row[$_id[0]];
				}else{
					return true;
				}
			}
		}

		public function db_escape($str){
			if(is_array($str)){
				foreach($str as $key=>$val){
					$str[$key] = pg_escape_string($val);
				}
				return $str;
			}
			return pg_escape_string($str);
		}

		public function db_unescape($str){
			if(is_array($str)){
				foreach($str as $key=>$val){
					$str[$key] = str_replace(array('\\\'','\\"','\\\\','\'\''),array('\'','"','\\','\''),$val);
				}
				return $str;
			}
			return str_replace(array('\\\'','\\"','\\\\','\'\''),array('\'','"','\\','\''),$str);
		}

		public function db_list(){
			$table_array = db_arrays_nocache("SELECT `tablename` FROM `pg_tables`  WHERE `tablename` LIKE '".DB_LEFT."_%' ;");
			foreach($table_array as $val){
				$val = current($val);
				if(substr($val,0,(strlen(DB_LEFT)+1)) == DB_LEFT.'_'){
					$table_list[] = $val;
				}
			}
			return $table_list;
		}
	}

	class sqlite_lib{
		public function __construct($db_setting = false){
			if (!$db_setting['name']) {
				return false;
			}
			if (!$db_setting['sqlite_driver']) {
				if(extension_loaded('pdo_sqlite')){
					$db_setting['sqlite_driver'] = 'pdo_sqlite';
				}elseif(class_exists('SQLite3')){
					$db_setting['sqlite_driver'] = 'sqlite3';
				}elseif(function_exists('sqlite_open')){
					$db_setting['sqlite_driver'] = 'sqlite';
				}
			}
			$this->db_setting = $db_setting;
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					if(!is_file($db_setting['name'])){
						touch($db_setting['name']);
					}
					$this->link = new PDO('sqlite:'.$db_setting['name']);
				break;
				case 'sqlite3':
					$this->link = new SQLite3($db_setting['name']);
				break;
				case 'sqlite':
					$this->link = sqlite_open($db_setting['name']);
				break;
			}
			$this->connect_error = $this->error();
		}

		public function error(){
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					$error = $this->link->errorInfo();
					if($error[0] != '0000'){
						return $error[2];
					}else{
						return '';
					}
				break;
				case 'sqlite3':
					return $this->link->lastErrorMsg();
				break;
				case 'sqlite':
					return sqlite_error_string(sqlite_last_error($this->link));
				break;				
			}
		}

		public function query($sql){
			$sql = str_replace('`','"',$sql);
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					$this->link->query($sql);
					$error = $this->link->errorInfo();
					if(trim($error[0],'0')){
						return $error[2];
					}else{
						return '';
					}
				break;
				case 'sqlite3':
					if(!$this->link->exec($sql)){
						return $this->link->lastErrorMsg();
					}else{
						return '';
					}
				break;
				case 'sqlite':
					sqlite_query($this->link,$sql,null,$error);
					if($error){
						return $error;
					}else{
						return '';
					}		
				break;
			}
		}

		public function free_result(&$result){
			$result = null;
			return ;
		}

		public function stat(){
			return $this->connect_error ? false:true;
		}

		public function close(){
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					$this->link = null;
				break;
				case 'sqlite3':
					return $this->link->colse();
				break;
				case 'sqlite':
					return sqlite_close($this->link);
				break;
			}
		}

		public function result_type($type = null){
			return $type == 'BOTH' ? SQLITE_BOTH : SQLITE_ASSOC;
		}

		public function db_total($sql){
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					$row = $this->link->query($sql)->fetchAll();
					$total = $row[0][0];	
					return $total;
				break;
				case 'sqlite3':
					return $this->link->querySingle($sql);
				break;
				case 'sqlite':
					$row = sqlite_fetch_array(sqlite_query($this->link,$sql));
					return $row[0];
				break;
			}			
		}

		public function db_array($sql,$result_type = 'null'){
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					$row = $this->link->query($sql)->fetchAll();
					return $row[0];
				break;
				case 'sqlite3':
					return $this->link->querySingle($sql,true);
				break;
				case 'sqlite':
					$res = $this->query($sql);
					$row = clean_dbData(sqlite_fetch_array($res,$this->result_type($result_type)));
					$this->free_result($res);
					return $row;
				break;
			}
		}

		public function db_arrays($sql,$result_type = null){
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					foreach($this->link->query($sql)->fetchAll() AS $row){
						$rows[] = clean_dbData($row);
					}
				break;
				case 'sqlite3':
					$res = $this->link->query($sql);
					while ($row = $res->fetchArray()) {
						$rows[] = clean_dbData($row);
					}
				break;
				case 'sqlite':
					$res = sqlite_query($this->link,$sql);
					while($row = sqlite_fetch_array($res,$this->result_type($result_type))){
						$rows[] = clean_dbData($row);
					}
					$this->free_result($res);
				break;
			}
			return $rows;
		}

		public function db_insert($table,$_id,$_key,$_val){	
			$_key = '"'.implode('","',$_key).'"';
			$_val = "'".implode("','",$_val)."'";
			if($_id[0]&&$_id[1]>0){
				$sql = "REPLACE INTO \"".$table."\" (\"".$_id[0]."\",".$_key.")VALUES('".$_id[1]."',".$_val.")";
			}elseif($_id[0]){
				$sql = "REPLACE INTO \"".$table."\"(\"".$_id[0]."\",".$_key.")VALUES(NULL,".$_val.")";
			}else{
				$sql = "REPLACE INTO \"".$table."\"(".$_key.")VALUES(".$_val.")";
			}
			switch($this->db_setting['sqlite_driver']){
				case 'pdo_sqlite':
					$this->link->query($sql);
					if($_id[0]){
						return $this->link->lastInsertId();
					}else{
						return true;
					}
				break;
				case 'sqlite3':
					$this->link->exec($sql);
					if($_id[0]){
						return $this->link->lastInsertRowID();
					}
				break;
				case 'sqlite':
					sqlite_query($this->link,$sql);
					if($_id[0]){
						return sqlite_last_insert_rowid($this->link);
					}
				break;
			}
		}

		public function db_escape($str){		
			if(is_array($str)){
				foreach($str as $key=>$val){
					$str[$key] = sqlite_escape_string($val);
				}
				return $str;
			}
			return sqlite_escape_string($str);
		}

		public function db_unescape($str){
			if(is_array($str)){
				foreach($str as $key=>$val){
					$str[$key] = str_replace('\'\'','\'',$val);
				}
				return $str;
			}
			return str_replace('\'\'','\'',$str);
		}

		public function db_list(){
			$table_array = db_arrays_nocache("select name from sqlite_master where name LIKE '".DB_LEFT.'_'."%' AND name NOT LIKE 'sqlite_%'",'BOTH');
			foreach($table_array as $val){
				if(substr($val[0],0,(strlen(DB_LEFT)+1)) == DB_LEFT.'_'){
					$table_list[] = $val[0];
				}
			}
			return $table_list;
		}
	}

	function filter_file_name($filename){
		preg_match('|^(.+?)(\.[a-zA-Z]+)$|',$filename,$match);
		if ($match[1] && $match[2]) {
			return md5($match[1].time()).$match[2];
		}
		return null;
	}

	function form_token($form_name = '',$value = '',$output_type = null){
		if (!session_get('_form_token_'.$form_name)) {
			if (!$value) {
				$value = generate_slug();
			}
			session_set('_form_token_'.$form_name,$value);
		}else{
			session_set('_form_token_'.$form_name,session_get('_form_token_'.$form_name));
		}
		switch ($output_type) {
			case 'meta':
				echo '<meta id="_tkv_" value="'.session_get('_form_token_'.$form_name).'">';
			break;
			case 'input':
				echo '<input type="hidden" name="_tkv_" value="'.session_get('_form_token_'.$form_name).'">';
			break;
			default:
				return session_get('_form_token_'.$form_name);
		}
	}

	function check_form_token($form_name = '',$output_type = null){
		if ($_POST) {
			if (!$_POST['_tkv_'.$form_name] || session_get('_form_token_'.$form_name) != $_POST['_tkv_'.$form_name]) {
				if (!$output_type) {
					die(_t('Form session expired'));
				}
				return false;
			}
			return true;
		}
	}

	function page_limit($cookie_name = 'page_limit',$default = 30){
		if (!$cookie_name) {
			$cookie_name = 'page_limit';
		}
		return intval($_COOKIE[$cookie_name]) > 0?intval($_COOKIE[$cookie_name]):30;
	}

    function session_set($name, $data, $expire = 0){
    	if (!$expire) {
    		$expire = ini_get('session.gc_maxlifetime');
    	}
        $session_data = array();  
        $session_data['data'] = $data;  
        $session_data['expire'] = time()+$expire;  
        $_SESSION[$name] = $session_data;  
    }
   
    function session_get($name){  
        if(isset($_SESSION[$name])){  
            if($_SESSION[$name]['expire'] > time()){  
                return $_SESSION[$name]['data'];  
            }else{  
                unset($_SESSION[$name]);  
            }  
        }  
        return false;  
    }

    
 	function mkdir_p($dirname,$dest_dir = ''){
 		if (substr($dirname,0,1) == '/' && !$dest_dir) {
 			$dest_dir = '/';
 		}
 		$dirname = explode('/',$dirname);
 		foreach($dirname as $tmp){
 			if (!$tmp) {
 				continue ;
 			}
 			$dest_dir .= $tmp.'/'; 
 			if (!is_dir($dest_dir)) {
 				mkdir($dest_dir);
 			}
 		}
 	}
?>