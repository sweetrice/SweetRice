<?php
/**
 * Site management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.1
 */
 defined('VALID_INCLUDE') or die();
 $linkType = $_GET["linkType"];
 $mode = $_GET["mode"];
 switch($linkType){
	case 'system':
		if($mode == 'save'){
			$alias = array('attachment','rssfeed','rssfeedCat','rssfeedPost','sitemapXml','sitemapHtml','comment','tag','ad');
			foreach($alias as $key=>$val){
				$tmp = preg_replace("/[^\w_\-]+/",'',$_POST[$val."_alias"]);
				if($key=='attachment'){
					if(!is_dir(ROOT_DIR.$tmp)){
						$permalinks['attachment'] = $tmp;
					}else{
						$permalinks['attachment'] = $permalinks['attachment'];
					}
				}else{
					$permalinks[$val] = $tmp;
				}
			}
			setOption('permalinks_system',($permalinks?serialize($permalinks):''));
			_goto('./?type=permalinks&linkType=system');
	 }else{
		$top_word = PERMALINKS.' '.SETTING;
		$inc = 'permalinks_system.php';
	 }
	break;
	case 'custom':
		if($mode == 'save'){
			$data = $_POST;
			if(!ltrim($data['url'],'/')){
				alert(URL_UPDATE_FAILED,'./?type=permalinks&linkType=custom');
			}
			$keys = $data["keys"];
			$vals = $data["vals"];
			$reqs = array();
			if($keys && $vals){
				foreach($keys as $key=>$val){
					if($val && $vals[$key]){
						$reqs[$val] = $vals[$key];
					}
				}		
			}
			$data['reqs'] = $reqs;
			$result = links_insert($data);
			alert($result['lid']?URL_UPDATE_SUCCESSFULLY:URL_UPDATE_FAILED,'./?type=permalinks&linkType=custom');
		 }elseif($mode == 'delete'){
			$id = intval($_POST["id"]);
			$no = intval($_POST["no"]);
			db_query("DELETE FROM `".DB_LEFT."_links` WHERE `lid` = '$id'");
			output_json(array('status'=>'1','id'=>$id,'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(LINKS,$id))));
		 }elseif($mode == 'bulk'){
				$plist = $_POST["plist"];
				foreach($plist as $val){
					$val = intval($val);
					if($val>0){
						$ids[] = $val;
					}
				}
				db_query("DELETE FROM `".DB_LEFT."_links` WHERE `lid` IN (".implode(',',$ids).")");
				_goto('./?type=permalinks&linkType=custom');
		 }
		 elseif($mode == 'insert'){
			$id = intval($_GET["id"]);
			if($id > 0){
				$row = db_array("SELECT * FROM `".DB_LEFT."_links` WHERE `lid` = '$id'");
			}
			$inc = 'permalinks_custom_modify.php';
		 }else{
			$sql = " WHERE 1=1 ";
			$search = $_GET["search"];
			if($search){
				$sql .= " AND `url` LIKE '%$search%'";
			}
			$total = db_total("SELECT COUNT(*) FROM `".DB_LEFT."_links` ".$sql);
			$page_limit = 30;
			$p_link = './?type=permalinks&linkType=custom'.($search?'&search='.$search:'').'&';
			$pager = _pager($total,$page_limit,$p_link);
			$rows = db_arrays("SELECT * FROM `".DB_LEFT."_links` ".$sql." ORDER BY `url` ASC ".get_limit_sql($pager['page_start'],$page_limit));
			$top_word = LINKS.' '.ADMIN;
			$inc = 'permalinks_custom.php';
		 }
	break;
 }
?>