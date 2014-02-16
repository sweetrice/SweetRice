<?php
/**
 * Category management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET["mode"];
 if($mode=='delete'){
	$id = intval($_POST["id"]);
	$no = $_POST["no"];
	if($id>0){
		db_query("DELETE FROM `".DB_LEFT."_category` WHERE `id` = '$id'");
		db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` = '$id' AND `item_type` = 'category' ");
		db_query("UPDATE `".DB_LEFT."_posts` SET `category` = '0' WHERE `category` = '$id'");
		$rows = db_arrays("SELECT * FROM `".DB_LEFT."_category`");
		setOption('categories',db_escape(serialize($rows)));
	}
	output_json(array('status'=>'1','id'=>$id,'no'=>$no,'data'=>vsprintf(DELETE_SUCCESSFULLY,array(CATEGORY,$id))));
 }elseif($mode=='modify'){
	$id = intval($_GET["id"]);
	if($id>0){
		$row = db_array("SELECT * FROM `".DB_LEFT."_category` WHERE `id` = '$id'");
		$cat = db_arrays("SELECT * FROM `".DB_LEFT."_category` WHERE `parent_id` = '0' AND `id` !='".$row['id']."'");
		$old_link = array('src="'.ATTACHMENT_DIR,'data="'.ATTACHMENT_DIR,'value="'.ATTACHMENT_DIR);
		$new_link = array('src="'.SITE_URL.ATTACHMENT_DIR,'data="'.SITE_URL.ATTACHMENT_DIR,'value="'.SITE_URL.ATTACHMENT_DIR);
		$cf_rows = db_arrays("SELECT * FROM `".DB_LEFT."_item_data` WHERE `item_id` = '$id' AND `item_type` = 'category'");
	}else{
		$cat = db_arrays("SELECT * FROM `".DB_LEFT."_category` WHERE `parent_id` = '0'");
	}
	$s_parent[$row['parent_id']] = 'selected';
	if($global_setting['theme']){
		$template = get_template(SITE_HOME.'/_themes/'.$global_setting['theme'].'/','Category');
	}else{
		$template = get_template(SITE_HOME.'_themes/default/','Category');
	}
	$top_word = MODIFY.' '.CATEGORY;
	$subCategory = subCategory(" AND ip.`plugin` = ''");
	$inc = 'cat_modify.php';
 }elseif($mode=='insert'){
		$cat_data = category_insert();
		if($cat_data['cat_id']){
			if($_POST["done"]){
				if(intval($_POST["id"])<=0){
					completeInsert('./?type=category&mode=insert','./?type=category');
				}else{
					_goto('./?type=category');
				}
			}else{
				_goto($_SERVER["HTTP_REFERER"]);
			}
		}else{
			if($global_setting['theme']){
				$template = get_template(SITE_HOME.'_themes/'.$global_setting['theme'].'/','Category');
			}else{
				$template = get_template(SITE_HOME.'_themes/default/','Category');
			}
			$top_word = CREATE.' '.CATEGORY;
			$subCategory = subCategory(" AND ip.`plugin` = ''");
			$inc = 'cat_modify.php';
		}
 }elseif($mode=='bulk'){
	$plist = $_POST["plist"];
	foreach($plist as $val){
		$val = intval($val);
		if($val>0){
			$ids[] = $val;
		}
	}
	if(count($ids)>0){
		$ids = implode(',',$ids);
		db_query("DELETE FROM `".DB_LEFT."_category` WHERE `id` IN ($ids)");
		db_query("DELETE FROM `".DB_LEFT."_item_plugin` WHERE `item_id` IN ($ids) AND `item_type` = 'category' ");
		db_query("UPDATE `".DB_LEFT."_posts` SET `category` = '0' WHERE `category` IN ($ids)");
		$rows = db_arrays("SELECT * FROM `".DB_LEFT."_category`");
		setOption('categories',db_escape(serialize($rows)));	
	}
	_goto($_SERVER["HTTP_REFERER"]);
}else{
	$sql = " AND ip.`plugin` = ''";
	$search = db_escape($_GET["search"]);
	if($search){
		$sql .= " AND c.`title` LIKE '%$search%'";
	}
	$subCategory = subCategory($sql);
	$top_word = CATEGORY.' '.LISTS;
	$inc = 'category.php';
 }
?>