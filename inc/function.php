<?php
/**
 * All function.
 *
 * @package SweetRice
 * @SweetRice core.
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	function do_data($a,$filterData='public'){
		foreach($a as $key=>$val){
			if(!is_array($val)){
				switch($filterData){
					case 'privacy':
						$a[$key] = trim(clean_quotes($val));
					break;
					default:
						$a[$key] = db_escape(trim(clean_quotes($val)));
				}
			}else{
				$a[$key] = do_data($val,$filterData);
			}
		}
		return $a;
	}
	function escape_string($str){
		return htmlspecialchars(db_unescape($str),ENT_QUOTES);
	}
	function clean_quotes($str){
		if(get_magic_quotes_gpc()){
			$str = stripslashes($str);
		}
		return $str;
	}
	if(!function_exists('sqlite_escape_string')){
		function sqlite_escape_string($str){
			return str_replace('\'','\'\'',$str);
		}	
	}
	function db_escape($str){
		switch(DATABASE_TYPE){
			case 'sqlite':
				return sqlite_escape_string($str);
			break;
			case 'pgsql':
				return pg_escape_string($str);
			break;
			default:
				return mysql_real_escape_string($str);
		}
	}
	function db_unescape($str){
		switch(DATABASE_TYPE){
			case 'sqlite':
				return str_replace('\'\'','\'',$str);
			break;
			case 'pgsql':
				$str = str_replace(array('\\\'','\\"','\\\\','\'\''),array('\'','"','\\','\''),$str);
				return $str;
			break;
			default:
				$str = stripslashes($str);
				return $str;
		}
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
			$key=ltrim($key,'[');
			$key=rtrim($key,']');
			$key=stripslashes($key);
			if(preg_match($key,$url,$matches)){
				parse_str($val,$data);
				foreach($data as $k=>$v){
					if(preg_match('/^\$([1-9]+)$/',$v,$tmp)){
						$data[$k] = $matches[$tmp[1]];
					}
				}
				return $data;
				break;
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
		$permalinks = initPermalinks();
		$load_dir = str_replace('//','/',dirname($_SERVER["PHP_SELF"]).'/');
		$url = substr(preg_replace("/(\?.*)?$/",'',$_SERVER["REQUEST_URI"]),strlen($load_dir));
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
		}elseif($url==$permalinks['rssfeed'].'.xml'){
			$url_data['action'] = 'rssfeed';
		}elseif(preg_match("/^".$permalinks['rssfeedCat']."\/([a-zA-Z0-9\-_]+)\.xml$/",$url,$matches)){
			$url_data['action'] = 'rssfeed';
			$url_data['type'] = 'category';
			$url_data['c'] = $matches[1];
		}elseif(preg_match("/^".$permalinks['rssfeedPost']."\/([a-zA-Z0-9\-_]+)\.xml$/",$url,$matches)){
			$url_data['action'] = 'rssfeed';
			$url_data['type'] = 'entry';
			$url_data['post'] = $matches[1];
		}elseif($url==$permalinks['sitemapXml'].'.xml'){
			$url_data['action'] = 'sitemap';
			$url_data['type'] = 'xml';
		}elseif($url==$permalinks['sitemapHtml'].'/'){
			$url_data['action'] = 'sitemap';
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
		}elseif(preg_match("/^([a-zA-Z0-9\-_]+)\/(([0-9]{0,3})\/)?$/",$url,$matches)){
			$url_data['action'] = 'category';
			$url_data['c'] = $matches[1];
			$url_data['p'] = $matches[3];
		}elseif(preg_match("/^([a-zA-Z0-9\-_]+)\/([a-zA-Z0-9\-_]+)\/$/",$url,$matches)){
			$url_data['action'] = 'entry';
			$url_data['cateName'] = $matches[1];
			$url_data['post'] = $matches[2];
		}elseif($url){
			$prefix = $url;
			$prefix = explode('/',$prefix);
			if(count($prefix)>=2){
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
			return formatUrl('action=entry&post='.$post);
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
	function show_link_cat($category,$p='',$original_url=false){
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
	function show_link_sitemapHtml($original_url=false){
		$permalinks = initPermalinks();
		if(URL_REWRITE && !$original_url){
			return $permalinks['sitemapHtml'].'/';
		}else{
			return formatUrl('action=sitemap');
		}
	}
	function show_link_sitemapXml($original_url=false){
		$permalinks = initPermalinks();
		if(URL_REWRITE && !$original_url){
			return $permalinks['sitemapXml'].'.xml';
		}else{
			return formatUrl('action=sitemap&type=xml');
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
		preg_match_all("/.*(<p ?.*>(.+)<\/p>)+.*/",$content,$matchs);
		foreach($matchs[1] as $key=>$val){
			if($val!='<p>&nbsp;</p>'&&$val!='<p></p>'){
				preg_match("/<p ?[^>]*>(.+)<\/p>/",$val,$out);
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
			$tmpstr = "";
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
	function my_post($to,$subject,$mail_text,$mail_html,$from_mail,$from_name,$mime_boundary,$charset = 'UTF-8'){
		$from_name = '=?'.$charset.'?B?'.base64_encode($from_name).'?=';
		$subject = '=?'.$charset.'?B?'.base64_encode($subject).'?=';
		$headers = "From: ".$from_name." <".($from_mail?$from_mail:'noreply@'.$_SERVER["HTTP_HOST"]).">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
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
		$message .= "--$mime_boundary--\n\n";
		$mail_sent = mail( $to, $subject, $message, $headers );
		return $mail_sent;
	}
	function js_unescape($str){
			$ret = '';
			$len = strlen($str);
			for ($i = 0; $i < $len; $i++){
					if ($str[$i] == '%' && $str[$i+1] == 'u'){
							$val = hexdec(substr($str, $i+2, 4));
							if ($val < 0x7f) $ret .= chr($val);
							else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
							else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
							$i += 5;
					}else if ($str[$i] == '%'){
							$ret .= urldecode(substr($str, $i, 3));
							$i += 2;
					}
					else $ret .= $str[$i];
			}
			return $ret;
	}

	function sweetrice_cache($cache_link,$data,$cache_type){
		global $global_setting;
		if(!$global_setting['cache']){return ;}
		switch(true){
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

	function db_insert($table,$_id,$_key,$_val,$return_no=false,$database_type=false){
		$_id[1] = intval($_id[1]);
		if(!$database_type){
			$database_type = DATABASE_TYPE;
		}
		switch($database_type){
			case 'sqlite':
				$_key = '"'.implode('","',$_key).'"';
				$_val = "'".implode("','",$_val)."'";
				if($_id[0]&&$_id[1]>0){
					$sql = "REPLACE INTO \"".$table."\" (\"".$_id[0]."\",".$_key.")VALUES('".$_id[1]."',".$_val.")";
				}elseif($_id[0]){
					$sql = "REPLACE INTO \"".$table."\"(\"".$_id[0]."\",".$_key.")VALUES(NULL,".$_val.")";
				}else{
					$sql = "REPLACE INTO \"".$table."\"(".$_key.")VALUES(".$_val.")";
				}
				global $db;
				return sqlite_dbinsert($db,$sql,$_id[0]);
			break;
			case 'pgsql':
				if($_id[0]&&$_id[1]>0){
					$total = db_total_nocache("SELECT COUNT(*) FROM \"".$table."\" WHERE \"".$_id[0]."\" = '".$_id[1]."'",$database_type);
					if($total==1){
						$_sql = " SET ";
						for($i=0; $i<count($_key); $i++){
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
					pg_query($sql);
					return $_id[1];
				}else{
					$_key = '"'.implode('","',$_key).'"';
					$_val = "'".implode("','",$_val)."'";
					if($_id[0]){
						$last_id = db_array_nocache("SELECT \"".$_id[0]."\" FROM \"".$table."\" ORDER BY \"".$_id[0]."\" DESC LIMIT 1 ",'ASSOC',$database_type);
						$last_id = $last_id[$_id[0]] + 1;
						$sql = "INSERT INTO \"".$table."\"(\"".$_id[0]."\",".$_key.")VALUES('$last_id',".$_val.")";
					}else{
						$tindex = db_array_nocache("SELECT pg_constraint.conname AS pk_name,pg_attribute.attname AS colname FROM pg_constraint INNER JOIN pg_class ON pg_constraint.conrelid = pg_class.oid INNER JOIN pg_attribute ON pg_attribute.attrelid = pg_class.oid AND pg_attribute.attnum = pg_constraint.conkey[1] WHERE pg_class.relname = '".$table."' AND pg_constraint.contype='p'",'ASSOC',$database_type);
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
					pg_query($sql);
					if(!$return_no&&$_id[0]){
						$row = db_array_nocache("SELECT \"".$_id[0]."\" FROM \"".$table."\" ORDER BY \"".$_id[0]."\" DESC LIMIT 1 ",'ASSOC',$database_type);
						return $row[$_id[0]];
					}else{
						return true;
					}
				}
			break;
			default:
				$_key = '`'.implode('`,`',$_key).'`';
				$_val = "'".implode("','",$_val)."'";
				if($_id[0]){
					$sql = "REPLACE INTO `".$table."`(`".$_id[0]."`,".$_key.")VALUES('".$_id[1]."',".$_val.")";
				}else{
					$sql = "REPLACE INTO `".$table."`(".$_key.")VALUES(".$_val.")";
				}
				mysql_query($sql);
				if($_id[0]){
					return mysql_insert_id();
				}else{
					return true;
				}
		}
	}
	function db_error($database_type=false){
		if(!$database_type){
			$database_type = DATABASE_TYPE;
		}
		switch($database_type){
			case 'sqlite':
				global $sqlite_driver,$db;
				if(!$db){
					return 'No SQLite connected';
				}
				switch($sqlite_driver){
					case 'pdo_sqlite':
						$error = $db->errorInfo();
						if($error[0] != '0000'){
							return $error[2];
						}else{
							return '';
						}
					break;
					case 'sqlite3':
						return $db->lastErrorMsg();
					break;
					case 'sqlite':
						return sqlite_error_string(sqlite_last_error($db));
					break;				
				}
			break;
			case 'pgsql':
				if(!pg_version()){
					return 'No PostgreSQL connected';
				}
				return pg_last_error();
			break;
			default:
				if(!mysql_stat()){
					return 'No Mysql connected';
				}
				return mysql_error();
		}
	}
	function db_query($sql,$database_type=false){
		if(!$database_type){
			$database_type = DATABASE_TYPE;
		}
		switch($database_type){
			case 'sqlite':
				$sql = str_replace('`','"',$sql);
				global $db;
				return sqlite_dbquery($db,$sql);
			break;
			case 'pgsql':
				$sql = str_replace('`','"',$sql);
				$res = pg_query($sql);
				return pg_last_error();
			break;
			default:
				mysql_query($sql);
				return mysql_error();
		}
	}
	function db_arrays($sql,$type = 'ASSOC',$database_type=false){
		if(!$database_type){
			$database_type = DATABASE_TYPE;
		}
		$cache_link = 'db_arrays_'.md5($sql);
		$cache_data = sweetrice_cached($cache_link,'db_arrays');
		if($cache_data){
			return $cache_data;
		}else{
			switch($database_type){
				case 'sqlite':
					$sql = str_replace('`','"',$sql);
					global $db;
					$rows = sqlite_dbarrays($db,$sql,$type);
				break;
				case 'pgsql':
					$sql = str_replace('`','"',$sql);
					$res = pg_query($sql);
					while($row = pg_fetch_array($res,null,$type=='BOTH'?PGSQL_BOTH:PGSQL_ASSOC)){
						$rows[] = $row;
					}
				break;
				default:
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res,$type=='BOTH'?MYSQL_BOTH:MYSQL_ASSOC)){
						$rows[] = $row;
					}
			}
			sweetrice_cache($cache_link,$rows,'db_arrays');
			return is_array($rows)?$rows:array();
		}
	}
	function db_array($sql,$type = 'ASSOC',$database_type=false){
		if(!$database_type){
			$database_type = DATABASE_TYPE;
		}
		$cache_link = 'db_array_'.md5($sql);
		$cache_data = sweetrice_cached($cache_link,'db_array');
		if($cache_data){
			return $cache_data;
		}else{
			switch($database_type){
				case 'sqlite':
					$sql = str_replace('`','"',$sql);
					global $db;
					$row = sqlite_dbarray($db,$sql,$type);
				break;
				case 'pgsql':
					$sql = str_replace('`','"',$sql);
					$row = pg_fetch_array(pg_query($sql),null,$type=='BOTH'?PGSQL_BOTH:PGSQL_ASSOC);
				break;
				default:
					$row = mysql_fetch_array(mysql_query($sql),$type=='BOTH'?MYSQL_BOTH:MYSQL_ASSOC);
			}
			sweetrice_cache($cache_link,$row,'db_array');
			return is_array($row)?$row:array();
		}
	}

	function db_total($sql,$database_type=false){
		if(!$database_type){
			$database_type = DATABASE_TYPE;
		}
		$cache_link = 'db_total_'.md5($sql);
		$cache_data = sweetrice_cached($cache_link,'db_total');
		if($cache_data){
			return $cache_data;
		}else{
			switch($database_type){
				case 'sqlite':
					$sql = str_replace('`','"',$sql);
					global $db;
					$total = sqlite_dbtotal($db,$sql);
				break;
				case 'pgsql':
					$sql = str_replace('`','"',$sql);
					$row = pg_fetch_row(pg_query($sql));
					$total = $row[0];
				break;
				default:
					$row = mysql_fetch_row(mysql_query($sql));
					$total = $row[0];
			}
			sweetrice_cache($cache_link,$total,'db_total');
			return $total;			
		}
	}

	function db_fetch($query){
		$table = $query['table'];
		$where = $query['where'];
		$pager = $query['pager'];
		$pager_function = $query['pager_function'];
		if(!$pager_function){
			$pager_function = 'pager';
		}
		$limit = $query['limit']?get_limit_sql($query['limit'][0],$query['limit'][1]):'';
		$order = $query['order'];
		$field = $query['field'];
		if(!$field){
			$field = '*';
		}
		if(is_array($where)){
			$where = " WHERE 1=1 ".implode(' AND ',$where);
		}elseif(trim($where)){
			$where = " WHERE ".$where;
		}
		if($pager['page_limit']){
			$page_limit = $pager['page_limit'];
			$total = db_total("SELECT COUNT(*) FROM $table ".$where);
			$pager = call_user_func_array($pager_function,array($total,$page_limit,$pager['p_link']));
			$pager['total'] = $total;
			if($pager['outPage']){
				return $pager;
			}
			$limit = get_limit_sql($pager['page_start'],$page_limit);
		}
		$rows = db_arrays("SELECT ".$field." FROM ".$table." ".$where." ".($order?"ORDER by ".$order:"")." ".$limit);
		return array('rows'=>$rows,'pager'=>$pager);
	}

	function sqlite_dbhandle($dbname){
		global $sqlite_driver;
		switch($sqlite_driver){
			case 'pdo_sqlite':
				if(!is_file($dbname)){
					touch($dbname);
				}
				$db = new PDO('sqlite:'.$dbname);
			break;
			case 'sqlite3':
				class db extends SQLite3{
					function __construct($dbname){
						$this->open($dbname);
					}
				}
				$db = new db($dbname);
			break;
			case 'sqlite':
				$db = sqlite_open($dbname);
			break;
		}
		return $db;
	}

	function sqlite_dbinsert($db,$sql,$id){
		global $sqlite_driver;
		switch($sqlite_driver){
			case 'pdo_sqlite':
				$db->query($sql);
				if($id){
					return $db->lastInsertId();
				}else{
					return true;
				}
			break;
			case 'sqlite3':
				$db->exec($sql);
				if($id){
					return $db->lastInsertRowID();
				}
			break;
			case 'sqlite':
				if($id){
					return sqlite_last_insert_rowid($db);
				}
			break;
		}
	}

	function sqlite_dbquery($db,$sql){
		global $sqlite_driver;
		switch($sqlite_driver){
			case 'pdo_sqlite':
				$db->query($sql);
				$error = $db->errorInfo();
				if($error[0]!='0000'){
					return $error[2];
				}else{
					return '';
				}
			break;
			case 'sqlite3':
				if(!$db->exec($sql)){
					return $db->lastErrorMsg();
				}else{
					return '';
				}
			break;
			case 'sqlite':
				sqlite_query($db,$sql,null,$error);
				if($error){
					return $error;
				}else{
					return '';
				}		
			break;
		}
	}

	function sqlite_dbarray($db,$sql,$type){
		global $sqlite_driver;
		switch($sqlite_driver){
			case 'pdo_sqlite':
				$row = $db->query($sql)->fetchAll();
				return $row[0];
			break;
			case 'sqlite3':
				return $db->querySingle($sql,true);
			break;
			case 'sqlite':
				return clean_dbData(sqlite_fetch_array(sqlite_query($db,$sql),$type=='BOTH'?SQLITE_BOTH:SQLITE_ASSOC));
			break;
		}
	}

	function sqlite_dbarrays($db,$sql,$type=null){
		global $sqlite_driver;
		switch($sqlite_driver){
			case 'pdo_sqlite':
				foreach($db->query($sql)->fetchAll() AS $row){
					$rows[] = clean_dbData($row);
				}
			break;
			case 'sqlite3':
				$results = $db->query($sql);
				while ($row = $results->fetchArray()) {
						$rows[] = clean_dbData($row);
				}
			break;
			case 'sqlite':
				$res = sqlite_query($db,$sql);
				while($row = sqlite_fetch_array($res,$type=='BOTH'?SQLITE_BOTH:SQLITE_ASSOC)){
					$rows[] = clean_dbData($row);
				}
			break;
		}
		return $rows;
	}

	function sqlite_dbtotal($db,$sql){
		global $sqlite_driver;
		switch($sqlite_driver){
			case 'pdo_sqlite':
				$row = $db->query($sql)->fetchAll();
				$total = $row[0][0];	
				return $total;
			break;
			case 'sqlite3':
				return $db->querySingle($sql);
			break;
			case 'sqlite':
				$row = sqlite_fetch_array(sqlite_query($db,$sql));
				return $row[0];
			break;
		}
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

	function db_list(){
		switch(DATABASE_TYPE){
			case 'sqlite':
					$table_array = db_arrays_nocache("select name from sqlite_master where name LIKE '".DB_LEFT.'_'."%' AND name NOT LIKE 'sqlite_%'",'BOTH');
					foreach($table_array as $val){
						if(substr($val[0],0,(strlen(DB_LEFT)+1))==DB_LEFT.'_'){
							$table_list[] = $val[0];
						}
					}
			break;
			case 'mysql':
					$table_array = db_arrays_nocache("SHOW TABLES",'BOTH');
					foreach($table_array as $val){
						if(substr($val[0],0,(strlen(DB_LEFT)+1))==DB_LEFT.'_'){
							$table_list[] = $val[0];
						}
					}
			break;
			case 'pgsql':
					$table_array = db_arrays_nocache("SELECT tablename FROM pg_tables  WHERE tablename LIKE '".DB_LEFT."_%' ;");
					foreach($table_array as $val){
						if(substr($val['tablename'],0,(strlen(DB_LEFT)+1))==DB_LEFT.'_'){
							$table_list[] = $val['tablename'];
						}
					}
			break;
		}
		return $table_list;
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
		if(!headers_sent()&&extension_loaded("zlib")&&strpos($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")!==false){
			ob_start(ob_start("ob_gzhandler"));
		}else{
			ob_start();
		}
	}

	function _flush(){
		ob_end_flush();
	}

	function upload_($f,$dest_dir,$new_file,$old_file){
		$file_type = '.php';
		if(!$f['name']){
			return $old_file;
		}
		$tmp = explode('.',$f['name']);
		if(count($tmp)){
			$fileext = '.'.end($tmp);
		}
		
		if(preg_match("/[^a-zA-Z0-9_\-\.\s]+/",$new_file)){
			$new_file  = md5($new_file).$fileext;
		}
		if($f['name']&&strtolower($fileext)!=$file_type){
			$dest=$dest_dir.$new_file;
			$r=move_uploaded_file($f['tmp_name'],$dest);
			if($old_file&&file_exists($dest_dir.$old_file)&&$old_file!=$new_file){
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
		$browsers = array('MSIE 10.0','MSIE 9.0','MSIE 8.0','MSIE 7.0','MSIE 6.0','Firefox','Opera','Chrome','Safari','Google','Yahoo','Bing','Baidu','Other');
		$bg_browsers = array('#2800fc','#2896fc','#286ea2','#285880','#0a9682','#ee7907','#e11625','#6aa0c8','#63b143','#009900','#ff11dd','#FF9900','#6655ff','#648282');
		if($t==2){
			return $bg_browsers;
		}else{
			return $browsers;
		}
	}
	function user_track(){
		$ip = $_SERVER["REMOTE_ADDR"];
		$user_from = $_SERVER["HTTP_REFERER"];
		$this_page = $_SERVER["REQUEST_URI"];
		$user_browser = $_SERVER["HTTP_USER_AGENT"];
		$browsers = init_browsers(1);
		foreach($browsers as $val){
			if(strpos(strtoupper($user_browser),strtoupper($val))!==false){
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
		$db_track = sqlite_dbhandle($dbname);
		if($new_track){
			sqlite_dbquery($db_track,"CREATE TABLE user_agent (id INTEGER PRIMARY KEY ,ip varchar(39) ,user_from varchar(255) ,this_page varchar(255),user_browser varchar(255),time integer)");
			sqlite_dbquery($db_track,"CREATE TABLE agent_month (id INTEGER PRIMARY KEY ,user_browser varchar(255),record_date date,total int(10),UNIQUE(user_browser,record_date))");
		}
		sqlite_dbquery($db_track,"INSERT INTO user_agent (id,ip,user_from,this_page,user_browser,time)VALUES(NULL,'$ip','$user_from','$this_page','$user_browser','".time()."')");
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
		if(substr($filename,0,strlen(BASE_URL))!=BASE_URL){
			return 'Remote file';
		}
		$filename = str_replace(BASE_URL,SITE_HOME,$filename);
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
	function filterXMLContent($content){
		$content = preg_replace("/[(\\x00-\\x08)(\\x0b-\\x0c)(\\x0e-\\x1f)]*/",'',$content);
		return $content;
	}

	function _404($tip_404){
		global $global_setting;
		header('HTTP/1.1 404 Page Not Found');
		include("inc/404.php");
		exit();
	}

	function getLangTypes($lang_dir){
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
		if(is_dir(SITE_HOME."_themes/")){
			$d = dir(SITE_HOME."_themes/");
			while (false !== ($entry = $d->read())) {
				if(file_exists(SITE_HOME."_themes/".$entry.'/theme.config')){
						$themes[$entry] = $entry;
					}
				}
			$d->close();
			return $themes;			
		}
	}

	function _403(){
		header("HTTP/1.1 403 Forbidden"); 
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
		$last_modify = max($last_modify,$_COOKIE["lang_update"],$_COOKIE["theme_update"],SETTING_UPDATE,CATEGORIES_UPDATE,LINKS_UPDATE);
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
		$lang = $_COOKIE["lang"]?$_COOKIE["lang"]:$global_setting['theme_lang'];
		if(!$lang){
			$ltmp = explode(',',$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
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
		$theme = $_COOKIE["theme"]?$_COOKIE["theme"]:$global_setting['theme'];
		if(is_dir(SITE_HOME.'_themes/'.$theme)){
			return $theme;
		}
	}

	function pluginApi($plugin,$apiFunction,$apiReturn=false){
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
						echo call_user_func(array($pluginClass,$apiFunction));
					break;
					default:
						return call_user_func(array($pluginClass,$apiFunction));
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

	function pluginHookUrl($plugin,$args=array()){
		if(!isPluginInstall($plugin)){
			return false;
		}	
		$reqs = $args;
		$reqs['action'] = 'pluginHook';
		$reqs['plugin'] = $plugin;
		ksort($reqs);
		if(URL_REWRITE){
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

	function getOption($name){
		if(!$name){
			return false;
		}
		return db_array_nocache("SELECT * FROM `".DB_LEFT."_options` WHERE `name` = '$name'");
	}

	function setOption($name,$value){
		if(!$name){
			return false;
		}
		$row = getOption($name);
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

	function pager($total,$page_limit,$p_link){
		$page = max(1,intval($_GET["p"]));
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
				$tmp_link = $i>1?(URL_REWRITE?$p_link.$i:$p_link.'&p='.$i):$p_link;
				$list_put .= "<a href=\"".$tmp_link.(URL_REWRITE&&$i!=1?'/':'')."\" ".($i==$page?'class="pageCurrent"':'').">".$i."</a> ";
			}
		}elseif($page == 1){
			$p_end = 0;
			for($i=0; $i<($page_last>10?10:$page_last); $i++){
				$tmp_link = $i>0?(URL_REWRITE?$p_link.($i+1):$p_link.'&p='.($i+1)):$p_link;
				$list_put .= "<a href=\"".$tmp_link.(URL_REWRITE&&$i>0?'/':'')."\" ".($i==0?'class="pageCurrent"':'').">".($i+1)."</a> ";
				$p_end +=1;
			}
			$list_put .= $page_last>=10?("<a href=\"".(URL_REWRITE?$p_link.'2':$p_link.'&p=2').(URL_REWRITE?'/':'')."\">Next&raquo;</a>"):'';
		}elseif($page == $page_total){
			$list_put .= "<a href=\"".(URL_REWRITE?$p_link.($page_total-1):$p_link.'&p='.($page_total-1)).(URL_REWRITE&&$page_total>11?'/':'')."\"/>&laquo;Previous</a> ";
			for($i=$page_total-9; $i<=$page_total; $i++){
				$tmp_link = $i>1?(URL_REWRITE?$p_link.$i:$p_link.'&p='.$i):$p_link;
				$list_put .= "<a href=\"".$tmp_link.(URL_REWRITE&&$i>1?'/':'')."\" ".($i==$page_total?'class="pageCurrent"':'').">".$i."</a> ";
			}
		}elseif($page > 1 && $page < $page_total){
			$p_end = $page-1;
			$list_put .= "<a href=\"".($page>2?(URL_REWRITE?$p_link.($page-1):$p_link.'&p='.($page-1)):$p_link).(URL_REWRITE&&$page>2?'/':'')."\"/>&laquo;Previous</a> ";
			if($page_last<10){
				for($i=10-$page_last; $i>0; $i--){
					$tmp_link = $page-$i>1?(URL_REWRITE?$p_link.($page-$i):$p_link.'&p='.($page-$i)):$p_link;
					$list_put .= "<a href=\"".$tmp_link.(URL_REWRITE&&($page-$i)>1?'/':'')."\" ".($i==0?'class="pageCurrent"':'').">".($page-$i)."</a> ";
				}		
			}
			for($i=0; $i<($page_last>10?10:$page_last); $i++){
				$tmp_link = $page+$i>1?(URL_REWRITE?$p_link.($page+$i):$p_link.'&p='.($page+$i)):$p_link;
				$list_put .= "<a href=\"".$tmp_link.(URL_REWRITE&&($page+$i)>1?'/':'')."\" ".($i==0?'class="pageCurrent"':'').">".($page+$i)."</a> ";
				$p_end += 1;
			}
			$list_put .= "<a href=\"".(URL_REWRITE?$p_link.($page+1):$p_link.'&p='.($page+1)).(URL_REWRITE?'/':'')."\">Next&raquo;</a>";
		}
		if($page_total>1){
			$list_put = $list_put?'<div id="PageList">'.$list_put.'</div>':'';
		}else{
			$list_put = '';
		}
		return array('page_start'=>$page_start,'list_put'=>$list_put,'outPage'=>$outPage,'page'=>$page);
	}


//API for Post insert
	function post_insert($data=array(),$without_attachment=false,$without_custome_field=false){
		if(!$data){
			$data = $_POST;
		}
		$id = intval($data["id"]);
		$name = $data["name"];
		if(!$name){return false;}
		$title = $data["title"];
		$old_link = array('src="'.SITE_URL.ATTACHMENT_DIR,'data="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.ATTACHMENT_DIR);
		$new_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
		$info = str_replace($old_link,$new_link,$data["info"]);
		$info = db_escape($info);
		$keyword = $data["keyword"];
		$tags = explode(',',$data["tags"]);
		$taglist = array();
		foreach($tags as $val){
			$val = trim($val);
			if($val&&!in_array($val,$taglist)){
				$taglist[] = $val;
			}
		}
		$tags = implode(',',$taglist);
		$description = $data["description"];
		$sys_name = $data["sys_name"];
		$category = intval($data["category"]);
		$views = intval($data["views"]);
		$createTime = intval($data["createTime"]);
		$in_blog = intval($data["in_blog"]);
		$allow_comment = intval($data["allow_comment"]);
		$template = $data["template"];
		if($data["republish"]){
			$createTime = false;
		}else{
			$createTime = intval($data["createTime"]);
		}
		if($sys_name){
			$sysname_total = db_total_nocache("SELECT COUNT(*) FROM `".DB_LEFT."_posts` WHERE `sys_name` = '$sys_name' and `id` !='$id' ");
			if($sysname_total!=0){
				$sys_name = time();
			}
		}else{
			$sys_name = time();
		}
		$post_id = db_insert(DB_LEFT."_posts",array('id',$id),array( 'name','title' , 'body' ,'keyword' ,'tags','description' , 'sys_name' ,'date' , 'category' ,'in_blog','views','allow_comment','template'),array(escape_string($name), escape_string($title), $info,escape_string($keyword),db_escape($tags),escape_string($description),$sys_name,($createTime?$createTime:time()), $category ,$in_blog,$views,$allow_comment,$template));
		if($id>0){
			db_query("UPDATE `".DB_LEFT."_comment` SET `post_cat` = '".$categories[$category]['link']."',`post_slug` = '$sys_name' WHERE `post_id` = '$post_id'");
		}else{
			db_insert(DB_LEFT."_item_plugin",array('id',''),array('item_id','item_type','plugin'),array($post_id,'post',$data['plugin']));
		}
		if(!$without_attachment){
			$attNos = $data["no"];
			$inlist = array();
			for($i=1;$i<=$attNos;$i++){
				$attid = intval($data["attid_".$i]);
				if($data['att_'.$i]){
					$inlist[] = db_insert(DB_LEFT."_attachment",array('id',$attid),array('post_id','file_name','date','downloads'),array($post_id,$data['att_'.$i],$data["attdate_".$i]?intval($data["attdate_".$i]):time(),intval($data["atttimes_".$i])));
				}
			}
			if(count($inlist)){
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
		if(!$data["name"]){return false;}
		$id = intval($data["id"]);
		$sys_name = strtolower($data["link"]);
		if($sys_name){
			$sysname_total = db_total_nocache("SELECT COUNT(*) FROM `".DB_LEFT."_category` WHERE `link` = '$sys_name' and `id` !='$id' ");
			if($sysname_total>0){
				$sys_name = time();
			}
		}else{
			$sys_name = time();
		}
		$cat_id = db_insert(DB_LEFT.'_category',array('id',$id),array('name' , 'link' , 'title' , 'description' , 'keyword' , 'sort_word','parent_id','template'),array(escape_string($data["name"]), $sys_name, escape_string($data["title"]), escape_string($data["description"]), escape_string($data["keyword"]), escape_string($data["sort_word"]), intval($data["parent_id"]),$data["template"]));
		$rows = db_arrays_nocache("SELECT * FROM `".DB_LEFT."_category`");
		db_query("UPDATE `".DB_LEFT."_options` SET `content` = '".db_escape(serialize($rows))."',`date` = '".time()."' WHERE `name` = 'categories'");
		if($id==0){
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
				$inlist[] = db_insert(DB_LEFT.'_item_data',array('id',$cfid),array('item_id','item_type','data_type','name','value'),array($item_id,$type,$data['cftype'][$i],$data['cfname'][$i],db_escape($data['cfvalue'][$i])));
				if($data['savelist'][$i]){
					$savelist[] = array('name'=>$data['cfname'][$i],'type'=>$data['cftype'][$i]);
				}
			}
		}
		if(count($inlist)){
			db_query("DELETE FROM `".DB_LEFT."_item_data` WHERE `id` NOT IN (".implode(',',$inlist).") AND `item_id` = '$item_id' AND `item_type` = '$type'");
		}
		if(count($savelist) > 0){
			$cfdata = getOption('custom_'.$type.'_field');
			if($cfdata){
				$cfdata = unserialize($cflist['content']);
			}
			foreach($savelist as $val){
				if(!$cfdata[$val['name']]){
					$cfdata[$val['name']] = $val;
				}
			}
			setOption('custom_'.$type.'_field',serialize($cfdata));
		}
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
			$data = db_array("SELECT `name`,`value` FROM `".DB_LEFT."_item_data` ".$where);
		}else{
			$rows = db_arrays("SELECT `name`,`value` FROM `".DB_LEFT."_item_data` ".$where);
			foreach($rows as $val){
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
		$comment = nl2br(escape_string(js_unescape($data["info"])));
		$post_id = intval(js_unescape($data["postID"]));
		$website = escape_string(js_unescape($data["website"]));
		$email = js_unescape($data["email"]);
		$name = escape_string(js_unescape($data["name"]));
		$remember = intval($data["remember"]);
		$row = db_array_nocache("SELECT `name`,`allow_comment`,`category`,`sys_name` FROM `".DB_LEFT."_posts` WHERE `id` = '$post_id'");
		if($_SESSION["hashcode"] != md5(js_unescape($data["code"]))||!checkemail($email)||!$comment||!$row['allow_comment']){
			return array('status'=>'0','status_code'=>CMT_TIP_RESPONSE_ERROR);
		}else{
			$post_name = db_escape($row['name']);
			$post_cat = $categories[$row['category']]['link'];
			$post_slug = $row['sys_name'];
			$ip = $_SERVER["REMOTE_ADDR"];
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
			unset($_SESSION["hashcode"]);
			if($id>0){
				return array('status'=>'1','status_code'=>CMT_TIP_RESPONSE_SUCCESS);
			}
			return array('status'=>'0','status_code'=>db_error());
		}
	}

//API for links insert
	function links_insert($data){
		if(!$data){
			$data = $_POST;
		}
		$id = $data["id"];
		$url = ltrim($data["url"],'/');
		$reqs = $data['reqs'];
		ksort($reqs);
		$plugin = $data["plugin"];
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
			return '<div class="blog_text" id="post-'.$row['id'].'"><h2 class="blog_title"><a href="'.show_link_page($categories[$row['category']]['link'],$row['sys_name']).'">'.$row['name'].'</a></h2><div class="post_info"><div class="list_info"><p class="list_date">'.date(POST_DATE_FORMAT,$row['date']).'</p><p class="list_views">'.$row['views'].' '.VIEWS.'</p><p class="div_clear"></p></div>'.postPreview($row['body']).' <span title="'.BASE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']).'" class="readmore">'.READ_MORE.'</span></div><p>'.TAGS.' '.$tag_str.'</p></div>';			
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
			return '<fieldset><legend><a name="comment_'.$k.'"></a><a href="'.$comment_link.'#comment_'.$k.'">#'.$last_no.'</a> '.($val['website']?'<a href="'.$val['website'].'" rel="external nofollow">'.$val['name'].'</a>':$val['name']).' '.date('M,d,Y D',$val['date']).'</legend>'.$val['info'].'</fieldset>';			
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
			$d = dir(SITE_HOME."_plugin/");
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
		if(is_dir(ROOT_DIR."_sites/")){
			$d = dir(ROOT_DIR."_sites/");
			while (false !== ($entry = $d->read())) {
				if($entry !='.' && $entry !='..' && file_exists(ROOT_DIR.'_sites/'.$entry.'/inc/db.php')){
					$site_config = array();
					include(ROOT_DIR.'_sites/'.$entry.'/inc/db.php');
					$site_list[$entry] = array('database_type'=>$database_type,'db_url'=>$db_url,'db_name'=>$db_name,'db_left'=>$db_left,'sqlite_driver'=>$sqlite_driver,'db_user'=>$db_username,'db_passwd'=>$db_passwd,'db_port'=>$db_port);		
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
		include(INCLUDE_DIR.'alert.php');
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

	function error_report(){
		if(function_exists('error_get_last')){
			$errors = error_get_last();
			$debug = 1;
		}else{
			$errors = debug_backtrace();
			$debug = 2;
		}
		if($debug==1 && $errors['type'] != 1){
			return ;
		}
		include(INCLUDE_DIR.'error_report.php');
		exit();
	}
	
	function dashboard_acts(){
		return array(
		'category'=>array('title'=>CATEGORY,'file'=>'do_category.php','type'=>1,'child'=>
			array(
				array('title'=>LISTS,'request'=>array('type'=>'category')),
				array('title'=>CREATE,'request'=>array('type'=>'category','mode'=>'insert'))
			)
		),
		'post'=>array('title'=>POST,'file'=>'do_post.php','type'=>1,'child'=>
			array(
				array('title'=>LISTS,'request'=>array('type'=>'post')),
				array('title'=>CREATE,'request'=>array('type'=>'post','mode'=>'insert'))
			)
		),
		'comment'=>array('title'=>COMMENT,'file'=>'do_comment.php','type'=>3,'request'=>array('type'=>'comment')),
		'attachment'=>array('title'=>ATTACHMENT,'file'=>'do_attachment.php','type'=>3,'request'=>array('type'=>'attachment')),
		'setting'=>array('title'=>SETTING,'type'=>2,'child'=>
			array(
				array('title'=>GENERAL,'file'=>'do_setting.php','request'=>array('type'=>'setting')),
				array('mustBase'=>true,'title'=>'.htaccess','file'=>'do_htaccess.php','request'=>array('type'=>'htaccess')),
				array('title'=>URL_REDIRECT,'file'=>'do_urlredirect.php','request'=>array('type'=>'url_redirect'))
			)
		),
		'permalinks'=>array('title'=>PERMALINKS,'file'=>'do_permalinks.php','type'=>1,'child'=>array(
				array('title'=>SYSTEM_TIP,'request'=>array('type'=>'permalinks','linkType'=>'system')),
				array('title'=>CUSTOM,'request'=>array('type'=>'permalinks','linkType'=>'custom'))
			)
		),
		'plugins'=>array('title'=>PLUGIN_LIST,'file'=>'do_plugins.php','request'=>array('type'=>'plugins'),'type'=>5,'child'=>pluginChild()),
		'ad'=>array('title'=>AD,'file'=>'do_ad.php','type'=>3,'request'=>array('type'=>'ad')),
		'password'=>array('title'=>PASSWORD,'file'=>'do_password.php','type'=>4),
		'plugin'=>array('title'=>PLUGIN,'file'=>'do_plugin.php','type'=>4),
		'track'=>array('title'=>TRACK,'file'=>'do_track.php','type'=>3,'request'=>array('type'=>'track')),
		'chart'=>array('title'=>CHART,'file'=>'do_chart.php','type'=>4,'request'=>array('type'=>'chart')),
		'link'=>array('title'=>LINKS,'file'=>'do_link.php','type'=>3,'request'=>array('type'=>'link')),
		'sitemap'=>array('title'=>SITEMAP,'file'=>'do_sitemap.php','type'=>3,'request'=>array('type'=>'sitemap')),
		'theme'=>array('title'=>THEME,'file'=>'do_theme.php','type'=>3,'request'=>array('type'=>'theme')),
		'media_center'=>array('title'=>MEDIA_CENTER,'file'=>'do_media_center.php','type'=>3,'request'=>array('type'=>'media_center')),
		'media'=>array('title'=>MEDIA,'file'=>'do_media.php','type'=>4,'request'=>array('type'=>'media')),
		'cache'=>array('title'=>CACHE,'file'=>'do_cache.php','type'=>1,'child'=>
			array(
				array('title'=>EXPIRED,'request'=>array('type'=>'cache')),
				array('title'=>FULL,'request'=>array('type'=>'cache','mode'=>'full'))
			)	
		),
		'update'=>array('mustBase'=>true,'title'=>UPDATE,'file'=>'do_update.php','type'=>1,'child'=>
			array(
				array('title'=>CHECK,'request'=>array('type'=>'update'))
			)	
		),
		'sites'=>array('title'=>SITES,'mustBase'=>true,'file'=>'do_sites.php','type'=>1,'child'=>
			array(
				array('title'=>LISTS,'request'=>array('type'=>'sites')),
				array('title'=>CREATE,'request'=>array('type'=>'sites','mode'=>'insert'))
			)
		),		
		'data'=>array('title'=>DATA,'file'=>'do_data.php','type'=>1,'child'=>
			array(
				array('title'=>DATABACKUP,'request'=>array('type'=>'data','mode'=>'db_backup')),
				array('title'=>DATAIMPORT,'request'=>array('type'=>'data','mode'=>'db_import')),
				array('title'=>DATACONVERTER,'request'=>array('type'=>'data','mode'=>'db_converter')),
				array('title'=>DATAOPTIMIZER,'request'=>array('type'=>'data','mode'=>'db_optimizer')),
				array('title'=>SQL_EXECUTE,'request'=>array('type'=>'data','mode'=>'sql_execute'))
			)	
		),
		'signout'=>array('title'=>LOGOUT,'file'=>'do_signout.php','type'=>3,'request'=>array('type'=>'signout')),
		'signin'=>array('title'=>LOGIN,'file'=>'do_signin.php','type'=>4)
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

	function getCategoryPosts($limit=10,$offset=0){
		$category_list = array();
		foreach(subCategory() as $cs){
			$post_rows = getPosts(array(
				'field' => '`sys_name`,`name`,`category`',
				'category_ids' => $cs['id'],
				'offset' => $offset,
				'limit' => $limit
			));
			$total_post = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_posts` WHERE `in_blog` = 1 AND `category` = '".$cs['id']."'");
			$category_list[] = array('post_rows'=>$post_rows,'total_post'=>$total_post,'category_id'=>$cs['id'],'category_level'=>$cs['level']);
		}
		return $category_list;
	}

	function getUncategoryPosts($limit=20,$offset=0){
		return db_arrays("SELECT `sys_name`,`category`,`name` FROM `".DB_LEFT."_posts` WHERE `category` = '0' AND `in_blog` = 1 ORDER BY `id` DESC ".get_limit_sql($offset,$limit));
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
		if(intval($param['limit'])){
			$limit = get_limit_sql(intval($param['offset']),intval($param['limit']));
		}
		
		if(!$param['post_type'] || $param['post_type'] == 'show'){
			$where = " WHERE `in_blog` = 1 ";
		}elseif($param['post_type'] == 'hide'){
			$where = " WHERE `in_blog` = 0 ";
		}else{
			$where = " WHERE 1=1 ";
		}
		
		if(is_array($param['category_ids']) && count($param['category_ids']) > 0){
			$where .= " AND `category` IN (".implode(',',$param['category_ids']).")";
		}elseif($param['category_ids']){
			$where .= " AND `category` IN (".$param['category_ids'].")";
		}
		
		$where .= $param['tag']?" AND (`title` LIKE '%$tag%' OR `body` LIKE '%$tag%' OR `tags` LIKE '%$tag%') ":'';

		$order = " ORDER BY ".($param['orderby']?$param['orderby']." ":"`id` ");
		$order .= " ".($param['order']?$param['order']." ":"DESC ");

		$filed = $param['field']?$param['field']:'*';
		return db_arrays("SELECT ".$filed." FROM `".DB_LEFT."_posts` ".$where." ".$order." ".$limit);
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
		return $setting;
	}

?>