<?php
/**
 * Update SweetRice.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.0.0
 */
 defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
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
					$str = _t('Extract SweetRice successfully');
					file_put_contents('step.txt',2);
					$nextstep = true;
				}else{
					$str = _t('Extract SweetRice failed');
				}
			break;
			case 2:
				$plist = $_POST['plist'];
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
				}elseif(!$_POST['submit']){
					$copyfailed = true;
				}
				if($copyfailed){
					$str = _t('Update SweetRice files failed');
				}else{
					$str = _t('Update SweetRice files successfully');
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
						$str = _t('Database upgrade successfully.');
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
						$str = vsprintf(_t('Database upgrade failed.<br />Some error maybe here:<br />%s'),array($upgrade_db));
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
						$str = _t('Clean temporary files successfully');
						file_put_contents('step.txt',5);
						$nextstep = true;
					}else{
						$str = _t('Clean temporary files failed');
					}				
				}
			break;
			case 5:
				unlink('step.txt');
				$str = vsprintf(_t('Upgrade SweetRice to %s successfully.'),array(SR_VERSION));
			break;
			default:
				$content = get_data_from_url('http://www.basic-cms.org/download/17/');
				if($content){
					file_put_contents(ROOT_DIR.'SweetRice_core.zip',$content);
					$str = vsprintf(_t('Download SweetRice_core.zip (File size:%s) successfully'),array(filesize(ROOT_DIR.'SweetRice_core.zip')));
					file_put_contents('step.txt',1);
					$nextstep = true;
				}else{
					$str =	_t('Update failed - cannot connect update server.');
				}
		}
		$top_word = _t('Update SweetRice');
	}elseif($mode=='automatically'){
		$curr_ = '1'.str_replace('.','',SR_VERSION);
		if (SR_VERSION >= 1150) {
			output_json(array('status'=>1,'status_code'=>$str));
		}
		$str = update_automatically('SweetRice_upgrade');
		$top_word = _t('Update SweetRice Automatically');
	}else{
		$lastest_ = sweetrice_version();
		$current_ = SR_VERSION;
		if($current_){
			$str = vsprintf(_t('Current version : %s'),array($current_));
			$last_ = '1'.str_replace('.','',$lastest_);
		}
		if($lastest_){
			$str .= ' '.vsprintf(_t('Lastest version : %s'),array($lastest_));
			$curr_ = '1'.str_replace('.','',$current_);
		}
		if($last_-$curr_>0){
			$update = true;
			file_put_contents('../inc/lastest_update.txt',$lastest_);
		}
		$top_word = _t('Check update');	
	}
	$inc = 'update.php';	
?>