<?php
/**
 * Cache management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 $cache_dir = SITE_HOME.'inc/cache/';
	if(file_exists($cache_dir)){
		switch(true){
			case extension_loaded('leveldb'):
				if($mode == 'full'){
					un_($cache_dir.'leveldb/');
				}else{
					$db = new LevelDb($cache_dir.'leveldb');
					$it = new LevelDBIterator($db);
					foreach($it as $key => $value) {
						if(time()-substr($value,0,10) > $global_setting['cache_expired']){
							$db->delete($key);
						}
					}
				}
			break;
			case extension_loaded('dba'):
				if($mode == 'full'){
					unlink($cache_dir.'cache.db');
				}else{
					$dba = dba_open($cache_dir.'cache.db', 'r', 'db4');
					$key = dba_firstkey($dba); 
					while($key != NULL) 
					{
						if(time()-substr(dba_fetch($key, $dba),0,10) > $global_setting['cache_expired']){
							dba_delete($key,$dba);
						}
						$key = dba_nextkey($dba);
					} 
					dba_close($dba); 
				}
			break;
			default:	
				clearstatcache();
				$no = 0;
				$d = dir($cache_dir);
				while (false !== ($entry = $d->read())) {
					if($entry!='.'&&$entry!='..'&&((time()-filemtime($cache_dir.$entry)>$cache_expired&&$cache_expired>0)||$mode=='full')){
						if(!is_dir($cache_dir.$entry)){
							unlink($cache_dir.$entry);
							$no += 1;
						}
					}
				}
				$d->close();
		}
	}
	die(_t('Cache has been clean successfully.').' '.intval($no).' '._t('Files'));
?>