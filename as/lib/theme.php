<?php
/**
 * Themes management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<div class="div_info">
<h2><?php _e('Current Theme');?> : <?php echo $global_setting['theme']?$global_setting['theme']:'Default';?></h2>
<div class="tip"><?php _e('For design theme for SweetRice,you can view _themes/default/theme.config.');?></div>
<p><select class="tlist">
	<option value="" > --- </option>                       
<?php
	foreach($themes as $key=>$val){
?>
	<option value="<?php echo $key;?>" <?php echo $key==$page?'selected':''?>><?php echo $key;?></option>
<?php
	}
?>
</select></p>
</div>
<?php
	if($page&&$themes[$page]){
		$data = getOption($themes[$page].'.bak');
		if($data['content']){
			$bak_list = unserialize($data['content']);
		}
		$source = $_GET['source'];
		if($source && $bak_list[$source]){
			$page_contents = $bak_list[$source];
			$from_bak = true;
		}else{
			$page_contents = file_get_contents(trim(SITE_HOME.$themes[$page]));
		}
?>
<form method="post" action="./?type=theme&mode=save&page=<?php echo $page;?>">
<fieldset><legend><?php echo _t('Modify').' '.$page.' : '.$themes[$page];?>:</legend>
	<textarea name="contents" class="theme_contents"><?php echo htmlspecialchars($page_contents);?></textarea>
</fieldset>
	<input type="submit" class="input_submit" value=" <?php _e('Done');?> "/> * <span class="tip"><?php _e('Please backup before modify');?></span> <?php if(!$from_bak):?><input type="button" value="<?php _e('Delete');?>" class="btn_delete" data="<?php echo $page;?>"><?php endif;?>
</form>
<?php
if(count($bak_list)){
	?>
	<form method="post" action="./?type=theme&mode=clean_backup&page=<?php echo $page;?>">
	<fieldset><legend><?php _e('Template History');?></legend>
		<ul class="template_history">
	<?php foreach($bak_list as $key=>$val):?>
		<li><input type="checkbox" name="tb[]" class="ck_item" value="<?php echo $key;?>"/> <a href="./?type=theme&page=<?php echo $page;?>&source=<?php echo $key;?>"><?php echo date(_t('M d Y H:i'),$key);?></a></li>
	<?php endforeach;?>
	<li><input type="checkbox" id="checkall"/> <input type="submit" value="<?php _e('Clean Backup');?>"></li>
	</ul>
	</fieldset>
	</form>
	<?php
}
	}
?>
<form method="post" action="./?type=theme&mode=add">
<fieldset><legend><?php _e('Create Template');?></legend>
 <?php _e('Name');?> <input type="text" name="name" /> 
	<select name="theme_type">
		<option value="category" selected="selected"><?php _e('Category');?></option>
		<option value="entry"><?php _e('Post');?></option>
	</select> <input type="submit" value="<?php _e('Create');?>"/>
</fieldset>
</form>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.tlist').bind('change',function(){
			var theme = _(this).val();
			if(theme){
				location.href = './?type=theme&page='+theme;
			}
		});
		_('.btn_delete').bind('click',function(){
			if (!confirm('<?php _e('Are you sure delete it?');?>')){
				return ;
			}
			location.href = './?type=theme&mode=delete&page=' + _(this).attr('data');
		});
	});
//-->
</script>