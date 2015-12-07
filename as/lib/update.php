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
	if($step == 1 || ($step == 2 && $copyfailed)){
?>
<form method="post" action="./?type=update&mode=manually">
<fieldset><legend><?php _e('These files/directory will be updated');?></legend>
<ul>
<li><input type="checkbox" id="checkall" name="checkall" onclick="checkboxAll(this);" checked/> <?php _e('Check');?> <?php _e('All');?></li>
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
					$old_size = _e('0(does not exists)');
				}
				$str_size = _t('File size : ').$old_size.' => '.$new_size;
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
</ul><input type="submit" name="submit" value="<?php echo _t('Update').' '._t('Files');?>"/></form>
</fieldset>
<?php
	}elseif($step == 2 && file_exists('../upgrade_db.php')){
?>
<input type="button" id="submit_button" value="<?php _e('Next Step');?>" class="updb">
<script type="text/javascript">
<!--
_().ready(function(){
	_('link').each(function(){
		_(this).attr('href',(_(this).attr('href').indexOf('?') == -1 ? '?' : '&' )+'time='+new Date().getTime());
	});
	_('.updb').bind('click',function(){
		var updialog = _.dialog({content:'<img src="../images/ajax-loader.gif">'});
		var query = new Object();
		_.ajax({
			'type':'get',
			'url':'../upgrade_db.php',
			'success':function(result){
				switch (result){
					case 'Successfully':
						location.href = './?type=update&mode=manually&time='+new Date().getTime();
					break;
					default:
						updialog.find('.SweetRice_dialog_content').html('<?php echo _t('Database').' '._t('Upgrade').' '._t('Failed');?>');
				}
			}
		});
	});
});
//-->
</script>
<?php
	}elseif($nextstep){
?>
<input type="button" value="<?php _e('Next step');?>" url="./?type=update&mode=manually&time=<?php echo microtime(true);?>" class="back">
<?php
	}
 }elseif($mode == 'automatically'){
?>
<p><?php echo $str;?></p>
<?php
 }else{
?>
<h1><?php echo $str;?></h1>
<?php
	if($update&&(extension_loaded('zlib')||extension_loaded('ZZIPlib'))){
?>
<p><?php _e('Please upgrade SweetRice.Important: before upgrading, please <a href="./?type=data&mode=db_backup">backup your database</a> and files.');?></p>
<input type="button" value="<?php _e('Automatically');?>" url="./?type=update&mode=automatically" class="back">
<input type="button" value="<?php _e('Manually');?>" url="./?type=update&mode=manually&time=<?php echo microtime(true);?>" class="back">
<?php
	}elseif($update){
		_e('Your server does not support zlib or ZZIPlib extension,please <a href="http://www.basic-cms.org/download.html">download SweetRice</a> and upgrade manually.');
	}
 }
?>