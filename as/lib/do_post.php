<?php
/**
 * Entry management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode=='delete'){
	if($_GET["one"]==1){
		$id = intval($_GET["id"]);
		if($id>0){
			db_query("DELETE FROM `".DB_LEFT."_posts` WHERE `id` = '$id'");
			db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` = '$id' AND `item_type` = 'post' ");
			db_query("DELETE FROM `".DB_LEFT."_comment` WHERE `post_id` = '$id'");
			db_query("DELETE FROM `".DB_LEFT."_attachment` WHERE `post_id` = '$id'");			
		}
		_goto('./?type=post');
	}else{
		$id = intval($_POST["id"]);
		$no = $_POST["no"];
		if($id>0){
			db_query("DELETE FROM `".DB_LEFT."_posts` WHERE `id` = '$id'");
			db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` = '$id' AND `item_type` = 'post' ");
			db_query("DELETE FROM `".DB_LEFT."_comment` WHERE `post_id` = '$id'");
			db_query("DELETE FROM `".DB_LEFT."_attachment` WHERE `post_id` = '$id'");			
		}
		output_json(array('status'=>'1','id'=>$id,'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(POST,$id))));
	}
 }elseif($mode == 'modify'){
	$id = intval($_GET["id"]);
	if($id>0){
		$row = db_array("SELECT * FROM `".DB_LEFT."_posts` WHERE `id` = '$id'");
		$att_rows = db_arrays("SELECT * FROM `".DB_LEFT."_attachment` WHERE `post_id` = '$id'");
		$cf_rows = db_arrays("SELECT * FROM `".DB_LEFT."_item_data` WHERE `item_id` = '$id' AND `item_type` = 'post'");
	}
	$old_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
	$new_link = array('src="'.SITE_URL.ATTACHMENT_DIR,'data="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.ATTACHMENT_DIR);
	$row['body'] = str_replace($old_link,$new_link,$row['body']);
	if($global_setting['theme']){
		$template = get_template(SITE_HOME.'_themes/'.$global_setting['theme'].'/','Entry');
	}else{
		$template = get_template(SITE_HOME.'_themes/default/','Entry');
	}
	$top_word = MODIFY.' '.POST;
	$subCategory = subCategory(" AND ip.`plugin` = ''");
	$referer = parse_url($_SERVER["HTTP_REFERER"]);
	preg_match("/&p=([0-9]+)/",$referer['query'],$matches);
	if($_SESSION["post_list_p"]!=$matches[1]&&$matches[1]){
		$_SESSION["post_list_p"] = $matches[1];
	}
	$returnUrl = './?type=post'.($_SESSION["video_list_p"]?'&p='.$_SESSION["post_list_p"]:'');
	$inc = 'post_modify.php';
 }elseif($mode=='insert'){
	$post_data = post_insert();
	if($post_data['post_id']){
		if($_POST["done"]){
			if(intval($_POST["id"])<=0){
				completeInsert('./?type=post&mode=insert','./?type=post');
			}else{
				_goto($_POST["returnUrl"]?$_POST["returnUrl"]:'./?type=post');
			}
		}else{
			_goto($_SERVER["HTTP_REFERER"]);
		}		
	}
	if(!$post_data['post_id']){
		if($global_setting['theme']){
			$template = get_template(SITE_HOME.'_themes/'.$global_setting['theme'].'/','Entry');
		}else{
			$template = get_template(SITE_HOME.'_themes/default/','Entry');
		}
		$top_word = CREATE.' '.POST;
		$subCategory = subCategory(" AND ip.`plugin` = ''");
		$inc = 'post_modify.php';
	}
 }elseif($mode=='bulk'){
	$paction = $_POST["paction"];
	$plist = $_POST["plist"];
	foreach($plist as $val){
		$val = intval($val);
		if($val>0){
			$ids[] = $val;
		}
	}
	if(count($ids)>0){
		$ids = implode(',',$ids);
		switch($paction){
			case 'pdelete':
					db_query("DELETE FROM `".DB_LEFT."_posts` WHERE `id` IN ($ids)");
					db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` IN ($ids) AND `item_type` = 'post' ");
					db_query("DELETE FROM `".DB_LEFT."_comment` WHERE `post_id` IN ($ids)");
					db_query("DELETE FROM `".DB_LEFT."_attachment` WHERE `post_id` IN ($ids)");
			break;
			case 'pmodify':
				$pcat = $_POST["pcat"];
				if($pcat!='no'){
					$sql = "`category` = '".intval($pcat)."',";
				}
				$in_blog = intval($_POST["in_blog"]);
				if($in_blog!=3){
					$sql .= "`in_blog` = '$in_blog',";
				}
				$allow_comment = intval($_POST["allow_comment"]);
				if($allow_comment!=3){
					$sql .= "`allow_comment` = '$allow_comment' ";
				}
				$template = $_POST["template"];
				if($template){
					$sql .= "`template` = '$template' ";
				}
				$sql = trim(trim($sql,' '),',');
				db_query("UPDATE `".DB_LEFT."_posts` SET ".$sql." WHERE `id` IN ($ids)");
				if($pcat!='no'){
					db_query("UPDATE `".DB_LEFT."_comment` SET `post_cat` = '".$categories[intval($pcat)]['link']."' WHERE `post_id` IN ($ids)");
				}
			break;
		}
	}
	_goto($_SERVER["HTTP_REFERER"]);
 }else{
	$where = " ip.`plugin` = ''";
	$search = db_escape($_GET["search"]);
	if($search){
		$where .= " AND ps.`title` LIKE '%$search%' OR ps.`name` LIKE '%$search%' ";
	}
	$category = $_GET["category"]!=''?$_GET["category"]:'all';
	if($category != 'all'){
		$where .= " AND ps.`category` = '$category' ";
	}

	$page_limit = 30;
	$p_link = './?type=post'.($search?'&search='.$search:'').'&';

	$data = db_fetch(array(
		'table' => "`".DB_LEFT."_posts` AS ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ps.`id` = ip.`item_id`",
		'field' => "ps.*,ip.`plugin`",
		'where' => $where." AND ip.`item_type` = 'post'",
		'order' => "ps.`date` DESC",
		'pager' => array('p_link'=>$p_link,'page_limit'=>$page_limit),
		'pager_function'=>'_pager'
	));
	$pager = $data['pager'];
	$rows = $data['rows'];
	foreach($rows as $val){
		$ids[] = $val['id'];
	}
	if(count($ids)){
		$cmts = db_arrays("SELECT `post_id`, COUNT(*) AS total FROM `".DB_LEFT."_comment` WHERE `post_id` IN(".implode(',',$ids).") GROUP BY `post_id`");
		foreach($cmts as $val){
			$cmtRows[$val['post_id']] = $val['total'];
		}
	}
	$subCategory = subCategory(" AND ip.`plugin` = ''");
	if($global_setting['theme']){
		$template = get_template(SITE_HOME.'_themes/'.$global_setting['theme'].'/','Entry');
	}else{
		$template = get_template(SITE_HOME.'_themes/default/','Entry');
	}
	$top_word = POST.' '.LISTS;
	$inc = 'post.php';
 }
?>