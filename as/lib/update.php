<?php
/**
 * SweetRice upgrade template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	if($mode == 'manually'){
?>
<span id="action_tip"></span>
<p><?php echo $str;?></p>
<?php
	if($step==1||($step==2&&$copyfailed)){
?>
<form method="post" action="./?type=update&mode=manually">
<fieldset><legend><?php echo UPDATE_FILES_TIP;?></legend>
<ul>
<li><input type="checkbox" id="checkall" name="checkall" onclick="checkboxAll(this);" checked/> <?php echo CHECK;?> <?php echo ALL;?></li>
<?php
		$sweetrice_files = sweetrice_files(ROOT_DIR.$upgrade_dir.'/');
		foreach($sweetrice_files as $val){
			$tmp = false;
			$target_entry = str_replace($upgrade_dir.'/','',$val);
			$target_entry = str_replace(ROOT_DIR.'as/',ROOT_DIR.DASHBOARD_DIR.'/',$target_entry);
			if($target_entry == ROOT_DIR.'as'){
				$target_entry = ROOT_DIR.DASHBOARD_DIR;
			}
			if(is_file($val)){
				$new_size = filesize($val);
				if(is_file($target_entry)&&md5_file($val) != md5_file($target_entry)){
					$tmp = true;
					$old_size = filesize($target_entry);
				}elseif(!is_file($target_entry)){
					$tmp = true;
					$old_size = '0(does not exists)';
				}
				$str_size = 'File size : '.$old_size.' => '.$new_size;
			}else{
				if(!is_dir($target_entry)){
					$tmp = true;
				}
			}
			if($tmp){
?>
<li><input type="checkbox" name="plist[]" value="<?php echo substr($val,strlen(ROOT_DIR.$upgrade_dir.'/'));?>" checked/> <?php echo substr($target_entry,strlen(str_replace($upgrade_dir.'/','',ROOT_DIR)));?> <?php echo $str_size;?></li>
<?php
			}
		}
?>
</ul><input type="submit" name="submit" value="<?php echo UPDATE.' '.FILES;?>"/></form>
</fieldset>
<?php
	}elseif($step==2&&file_exists('../upgrade_db.php')){
?>
<input type="button" id="submit_button" value="<?php echo NEXT_STEP;?>" onclick="upgrade_db();">
<script type="text/javascript">
<!--
function upgrade_db(){
	$('action_tip').innerHTML = '<img src="../images/ajax-loader.gif">';
	$('submit_button').disabled = true;
	var query = new Object();
	ajaxd_get(
		query,
		'../upgrade_db.php',
		function(result){
			$('submit_button').disabled = false;
			switch (result){
				case 'Successfully':
					location.href = './?type=update&mode=manually';
				break;
				default:
					$('action_tip').innerHTML = 'Sorry,some error happen when upgrade db,please upgrade SweetRice manually.!';
			}
		}
	);
}
//-->
</script>
<?php
	}elseif($nextstep){
?>
<input type="button" value="<?php echo NEXT_STEP;?>" onclick="location='./?type=update&mode=manually';">
<?php
	}
 }elseif($mode=='automatically'){
?>
<p><?php echo $str;?></p>
<?php
 }else{
?>
<h1><?php echo $str;?></h1>
<?php
	if($update&&(extension_loaded("zlib")||extension_loaded("ZZIPlib"))){
?>
<p><?php echo TIPS_UPDATE;?></p>
<input type="button" value="<?php echo AUTOMATICALLY;?>" onclick="location.href='./?type=update&mode=automatically';">
<input type="button" value="<?php echo MANUALLY;?>" onclick="location.href='./?type=update&mode=manually';">
<?php
	}elseif($update){
?>
Your server does not support zlib or ZZIPlib extension,please <a href="http://www.basic-cms.org/download.html">download SweetRice</a> and upgrade manually.
<?php
	}
 }
?>