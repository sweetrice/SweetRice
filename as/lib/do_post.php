<?php
/**
 * Entry management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.7.0
 */
 defined('VALID_INCLUDE') or die();
 $mode = $_GET['mode'];
 switch($mode){
	case 'insert':
		$post_data = post_insert();
		if($post_data['post_id']){
			if($_POST['done']){
				if(intval($_POST['id'])<=0){
					completeInsert('./?type=post&mode=insert','./?type=post');
				}else{
					_goto($_POST['returnUrl']?$_POST['returnUrl']:'./?type=post');
				}
			}else{
				_goto($_SERVER['HTTP_REFERER']);
			}		
		}
		if(!$post_data['post_id']){
			if($global_setting['theme']){
				$template = get_template(SITE_HOME.'_themes/'.$global_setting['theme'].'/','Entry');
			}else{
				$template = get_template(SITE_HOME.'_themes/default/','Entry');
			}
			$id = intval($_GET['id']);
			if($id > 0){
				$row = getPosts(array('ids'=>$id,'custom_field'=>true,'fetch_one'=>true));
				$att_rows = db_arrays("SELECT * FROM `".DB_LEFT."_attachment` WHERE `post_id` = '$id'");
				$cf_rows = $row['custom_field'];
				$row['body'] = toggle_attachment($row['body'],'dashboard');
				$top_word = _t('Modify Post');
			}else{
				$top_word = _t('Create Post');
			}
			$referer = parse_url($_SERVER['HTTP_REFERER']);
			preg_match('/&p=([0-9]+)/',$referer['query'],$matches);
			if($_SESSION['post_list_p']!=$matches[1]&&$matches[1]){
				$_SESSION['post_list_p'] = $matches[1];
			}
			$returnUrl = './?type=post'.($_SESSION['post_list_p']?'&p='.$_SESSION['post_list_p']:'');
			$subCategory = subCategory(" AND ip.`plugin` = ''");
			$inc = 'post_insert.php';
		}
	break;
	case 'bulk':
		$paction = $_POST['paction'];
		$plist = $_POST['plist'];
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
					removePosts(array('ids'=>$ids));
					output_json(array('status'=>1,'status_code'=>vsprintf(_t('%s (%s) has been delete successfully.'),array(_t('Post'),$ids))));
				break;
				case 'pmodify':
					$pcat = $_POST['pcat'];
					if($pcat!='no'){
						$sql = "`category` = '".intval($pcat)."',";
					}
					$in_blog = intval($_POST['in_blog']);
					if($in_blog != 3){
						$sql .= "`in_blog` = '$in_blog',";
					}
					$allow_comment = intval($_POST["allow_comment"]);
					if($allow_comment!=3){
						$sql .= "`allow_comment` = '$allow_comment' ";
					}
					$template = $_POST['template'];
					if($template){
						$sql .= "`template` = '$template' ";
					}
					$sql = trim(trim($sql,' '),',');
					db_query("UPDATE `".DB_LEFT."_posts` SET ".$sql." WHERE `id` IN ($ids)");
					if($pcat!='no'){
						db_query("UPDATE `".DB_LEFT."_comment` SET `post_cat` = '".$categories[intval($pcat)]['link']."' WHERE `post_id` IN ($ids)");
					}
					output_json(array('status'=>1,'status_code'=>vsprintf(_t('%s (%s) has been update successfully.'),array(_t('Post'),$ids))));
				break;
			}
		}
		output_json(array('status'=>0,'status_code'=>_t('Sorry,some error happened')));
	break;
	default:
		$where = " ip.`plugin` = '' AND ip.`item_type` = 'post'";
		$search = db_escape($_GET['search']);
		$search_url = '';
		if($search){
			$where .= " AND ps.`title` LIKE '%$search%' OR ps.`name` LIKE '%$search%' ";
			$search_url .= '&search='.$_GET['search'];
		}
		$category = isset($_GET['category'])? intval($_GET['category']):'all';
		if($category !== 'all'){
			$where .= " AND ps.`category` = '$category' ";
			$search_url .= '&category='.intval($_GET['category']);
		}
		$data = getPosts(array('table'=>" `".DB_LEFT."_posts` AS ps LEFT JOIN `".DB_LEFT."_item_plugin` AS ip ON ps.`id` = ip.`item_id`",
			'field' => "ps.*",
			'where'=>$where,
			'pager' =>  array('p_link'=>'./?type=post'.$search_url.'&',
			'page_limit'=>$_COOKIE['page_limit']?$_COOKIE['page_limit']:30,'pager_function' => '_pager')
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
		$top_word = _t('Post List');
		$inc = 'post.php';
 }
?>