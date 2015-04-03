<?php
/**
 * App form management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
	defined('VALID_INCLUDE') or die();
	if($_POST['id'] > 0){
		$id = intval($_POST['id']);
		$row = db_array("SELECT * FROM `".ADB."_app_form` WHERE `id` = '$id'");
		if($row['captcha'] && md5($_POST['code']) != $_SESSION['hashcode']){
			alert(_t('Captcha miss match'),$_SERVER['HTTP_REFERER']);
		}
		$fields = unserialize($row['fields']);
		if($row['method'] == 'get'){
			$data = $_GET;
		}else{
			$data = $_POST;
		}
		$isvalid = true;
		$dest_dir = APP_DIR.'data/form/';
		if(!is_dir(APP_DIR.'data')){
			mkdir(APP_DIR.'data');
		}
		if(!is_dir($dest_dir)){
			mkdir($dest_dir);
		}
		foreach($fields as $field){
			if($field['type'] == 'multi_file'){
				if(is_array($_FILES[$field['name']]['name'])){
					foreach($_FILES[$field['name']]['name'] as $key=>$val){
						$tmp = array(
							'name' => $_FILES[$field['name']]['name'][$key],
							'type' => $_FILES[$field['name']]['type'][$key],
							'tmp_name' => $_FILES[$field['name']]['tmp_name'][$key],
							'error' => $_FILES[$field['name']]['error'][$key],
							'size' => $_FILES[$field['name']]['size'][$key]
						);
						$upload = upload_($tmp,$dest_dir,$tmp['name'],null);
						if(file_exists($dest_dir.$upload)){
							$field_data[$field['name']][] = $upload;
						}
					}
				}
				if($field['required'] && !count($field_data[$field['name']])){
					$isvalid = false;
					break;
				}
			}elseif($field['type'] == 'file'){
				$field_data[$field['name']] = upload_($_FILES[$field['name']],$dest_dir,$_FILES[$field['name']]['name'],null);
				if($field['required'] && !file_exists($dest_dir.$field_data[$field['name']])){
					$isvalid = false;
					break;
				}
			}else{
				if($field['required'] && !$data[$field['name']]){
					$isvalid = false;
					break;
				}
				$field_data[$field['name']] = $data[$field['name']];
			}
		}
		if($isvalid){
			$insert = db_insert(ADB.'_app_form_data',array('id',null),array('form_id','data','date'),array($id,serialize($field_data),time()));
			if($insert){
				alert(_t('Submit completed'),$_SERVER['HTTP_REFERER']);
			}
			alert(_t('Database error'),$_SERVER['HTTP_REFERER']);
		}
		alert(_t('Some field required'),$_SERVER['HTTP_REFERER']);
	}
	$id = intval($_GET['id']);
	if($id > 0){
		$row = db_array("SELECT * FROM `".ADB."_app_form` WHERE `id` = '$id'");
		$fields = unserialize($row['fields']);
	}else{
		_404();
	}
	$title = _t('Please complete form').' '.$row['name'];
	$inc = THEME_DIR.'form.php';
?>