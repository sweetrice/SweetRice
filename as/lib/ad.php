<?php
/**
 * Ad management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
?>
<strong><?php _e('You can edit ads code and put it to template,or you can directly edit template <a href="./?type=theme">here</a>');?></strong>
<div>
<ul class="ads">
<?php
$no = 0;
	foreach($ads as $val){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<li class="<?php echo $classname;?>" id="li_<?php echo $no;?>">
<div class="ads_list">
<h3><?php echo $val;?></h3>
<div class="ads_content">
<?php highlight_string('<script type="text/javascript" src="'.BASE_URL.show_link_ads($val).'"></script>');?>
</div>
<p><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete" data="<?php echo $val;?>" no="<?php echo $no;?>" href="javascript:void(0);"><?php _e('Delete');?></a> 
<a title="<?php _e('Modify');?>" class="action_modify" href="./?type=ad&adk=<?php echo $val;?>"><?php _e('Modify');?></a> </p>
</div>
</li>
<?php
	}
?>
</ul>
</div>
<form method="post" action="./?type=ad&mode=save">
<fieldset><legend><strong><?php _e('Ads name');?>:</strong></legend>
<input type="text" name="adk" value="<?php echo $adk;?>" class="input_text"/>
</fieldset>
<fieldset><legend><strong><?php _e('Ads code');?>:</strong></legend>
<textarea name="adv" class="ad"><?php echo $adv;?></textarea>
</fieldset>
<input type="submit" value=" <?php _e('Done');?> " class="input_submit"/>
</form>

<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
		_('.action_delete').bind('click',function(){
			if(confirm('<?php _e('Are you sure delete it?');?>')) deleteAction('ad',_(this).attr('data'),_(this).attr('no'));
		});
	});
//-->
</script>