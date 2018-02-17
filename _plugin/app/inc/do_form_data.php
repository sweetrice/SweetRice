<?php
/**
 * App form data management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
	defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
	switch($mode){
		case 'bulk':
			$plist = $_POST['plist'];
			foreach($plist as $val){
				$val = intval($val);
				if($val>0){
					$ids[] = $val;
				}
			}
			if(count($ids)>0){
				$ids = implode(',',$ids);
				remove_form_data($ids);
			}
			output_json(array('status'=>1));
		break;
		default:
			$form_id = intval($_GET['form_id']);
			$forms = db_arrays("SELECT * FROM `".ADB."_app_form`");
			$where = " 1=1 ";
			if($form_id > 0){
				$where .= " AND afd.`form_id` = '$form_id'";
				$search_url = '&form_id='.$form_id;
				foreach($forms as $tmp){
					if ($tmp['id'] == $form_id) {
						$this_form = $tmp;
						$this_form['fields'] = unserialize($this_form['fields']);
						break;
					}
				}
			}
			$data = db_fetch(array('table'=>ADB.'_app_form_data as afd LEFT JOIN '.ADB.'_app_form as af ON af.id = afd.form_id',
				'field' => 'afd.*,af.name,af.fields',
				'where' => $where,
				'order' => 'afd.date DESC',
				'pager' => array('p_link'=>pluginDashboardUrl(THIS_APP,array('app_mode'=>'form_data')).$search_url.'&','page_limit'=>page_limit(null,10),'pager_function'=>'_pager')
			));
			$app_inc = 'form_data_list.php';
	}
?>