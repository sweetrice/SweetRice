<?php
/**
 * Update SweetRice.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
	$mode = $_GET["mode"];
	if($mode == 'manually'){
		$upgrade_dir = 'SweetRice_upgrade';
		if(file_exists('step.txt')){
			$step = file_get_contents('step.txt');
		}else{
			$step = 0;
		}
		switch($step){
			case 1:
				if(!file_exists(ROOT_DIR.$upgrade_dir)){
					mkdir(ROOT_DIR.$upgrade_dir);
				}
				if(extractZIP(ROOT_DIR.'SweetRice_core.zip',ROOT_DIR.$upgrade_dir.'/')){
					$str = EXTRACT_SR_SUCCESSFULLY;
					file_put_contents('step.txt',2);
					$nextstep = true;
				}else{
					$str = EXTRACT_SR_FAILED;
				}
			break;
			case 2:
				$plist = $_POST["plist"];
				if($plist){
					foreach($plist as $val){
						$target_entry = ROOT_DIR.$val;
						$target_entry = str_replace(ROOT_DIR.'as/',ROOT_DIR.DASHBOARD_DIR.'/',$target_entry);
						if($target_entry == ROOT_DIR.'as'){
							$target_entry = ROOT_DIR.DASHBOARD_DIR;
						}
						if(is_dir(ROOT_DIR.$upgrade_dir.'/'.$val)){
							if(!is_dir($target_entry)&&!mkdir($target_entry)){
								$copyfailed = true;
								break;
							}
						}elseif(!copy(ROOT_DIR.$upgrade_dir.'/'.$val,$target_entry)){
							$copyfailed = true;
							break;
						}
					}
				}elseif(!$_POST["submit"]){
					$copyfailed = true;
				}
				if($copyfailed){
					$str = UPDATE_SR_FILE_FAILED;
				}else{
					$str = UPDATE_SR_FILE_SUCCESSFULLY;
					file_put_contents('step.txt',3);
					$nextstep = true;
				}
			break;
			case 3:
				if(file_exists(ROOT_DIR.$upgrade_dir.'/upgrade_db.php')){
					if(!file_exists(ROOT_DIR.'upgrade_db.php')){
						copy(ROOT_DIR.$upgrade_dir.'/upgrade_db.php',ROOT_DIR.'/upgrade_db.php');
					}
					$upgrade_db = get_data_from_url(BASE_URL.'upgrade_db.php');
					if($upgrade_db == 'Successfully'){
						$str = DATABASE_UPGRADE_SUCCESSFULLY;
						if(file_exists(ROOT_DIR.'upgrade_db.php')){
							unlink(ROOT_DIR.'upgrade_db.php');
						}
						if(file_exists(ROOT_DIR.'inc/lastest_update.txt')){
							rename(ROOT_DIR.'inc/lastest_update.txt',ROOT_DIR.'inc/lastest.txt');
						}else{
							$lastest = sweetrice_version();
							file_put_contents(ROOT_DIR.'inc/lastest.txt',$lastest);
						}
						file_put_contents('step.txt',4);
						$nextstep = true;
					}else{
						$str = vsprintf(DATABASE_UPGRADE_FAILED,array($upgrade_db));
					}
				}else{
					if(file_exists(ROOT_DIR.'inc/lastest_update.txt')){
						rename(ROOT_DIR.'inc/lastest_update.txt',ROOT_DIR.'inc/lastest.txt');
					}else{
						$lastest = sweetrice_version();
						file_put_contents(ROOT_DIR.'inc/lastest.txt',$lastest);
					}
					file_put_contents('step.txt',4);
					_goto('./?type=update&mode=manually');
				}
			break;
			case 4:
				if(file_exists(ROOT_DIR.'SweetRice_core.zip')&&is_dir(ROOT_DIR.$upgrade_dir.'/')){
					if(un_(ROOT_DIR.$upgrade_dir.'/')&&unlink(ROOT_DIR.'SweetRice_core.zip')){
						$str = CLEAN_TEMPORARY_FILES_SUCCESSFULLY;
						file_put_contents('step.txt',5);
						$nextstep = true;
					}else{
						$str = CLEAN_TEMPORARY_FILES_FAILED;
					}				
				}
			break;
			case 5:
				unlink('step.txt');
				$str = vsprintf(UPGRADE_SR_SUCCESSFULLY,array(SR_VERSION));
			break;
			default:
				$content = get_data_from_url('http://www.basic-cms.org/download/17/');
				if($content){
					file_put_contents(ROOT_DIR.'SweetRice_core.zip',$content);
					$str = vsprintf(DOWNLOAD_SR_SUCCESSFULLY,array(filesize(ROOT_DIR.'SweetRice_core.zip')));
					file_put_contents('step.txt',1);
					$nextstep = true;
				}else{
					$str =	UPDATE_FAILED_CONNECT_SERVER;
				}
		}
		$top_word = UPDATE.' SweetRice';
	}elseif($mode=='automatically'){
		$top_word = UPDATE.' SweetRice '.AUTOMATICALLY;
		$str = update_automatically('SweetRice_upgrade');
	}else{
		$lastest_ = sweetrice_version();
		$current_ = SR_VERSION;
		if($current_){
			$str = vsprintf(CURRENT_VERSION_TIP,array($current_));
			$last_ = '1'.str_replace('.','',$lastest_);
		}
		if($lastest_){
			$str .= ' '.vsprintf(LASTEST_VERSION_TIP,array($lastest_));
			$curr_ = '1'.str_replace('.','',$current_);
		}
		if($last_-$curr_>0){
			$update = true;
			file_put_contents('../inc/lastest_update.txt',$lastest_);
		}
		$top_word = CHECK_UPDATE;	
	}
	$inc = 'update.php';	
?>