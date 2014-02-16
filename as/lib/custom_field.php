<?php
/**
 * Custom field template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<fieldset><legend><?php echo CUSTOM_FIELD;?></legend>
<ol id="cfList">
<?php
	$no = 0;
	$cflist = array();
	foreach($cf_rows as $val){
		$no += 1;
		$cflist[$val['name']] = true;
?>
<li id="cf_<?php echo $no;?>">
<fieldset><legend><input type="text" name="cfname[<?php echo $no;?>]" value="<?php echo $val['name'];?>"> 
<input type="button" value="<?php echo DELETE_TIP;?>" class="cf_del" data="<?php echo $no;?>"></legend>
<input type="hidden" name="cfid[<?php echo $no;?>]" value="<?php echo $val['id'];?>">
<input type="hidden" name="cftype[<?php echo $no;?>]" value="<?php echo $val['data_type'];?>">
<?php switch($val['data_type']){
	case 'text':
?>
<textarea name="cfvalue[<?php echo $no;?>]"><?php echo $val['value'];?></textarea>
<?php
	break;
	case 'single':
?>
<input type="checkbox" value="1" name="cfvalue[<?php echo $no;?>]" <?php echo $val['value']?'checked':'';?>>
<?php
	break;
	case 'html':
?>
<textarea name="cfvalue[<?php echo $no;?>]" id="cfvalue[<?php echo $no;?>]"><?php echo str_replace($old_link,$new_link,$val['value']);?></textarea>
<script type="text/javascript">
<!--
	editorEnable('cfvalue[<?php echo $no;?>]');
//-->
</script>
<?php
	break;
}?>
</fieldset></li>
<?php
	}
	$cfdata = getOption('custom_'.$cftype.'_field');
	if($cfdata && !count($cf_rows)){
		$no = 0;
		$cfdata = unserialize($cfdata['content']);
		foreach($cfdata as $key=>$val){
				$no += 1;
?>
<li id="cf_<?php echo $no;?>">
<fieldset><legend><input type="text" name="cfname[<?php echo $no;?>]" value="<?php echo $val['name'];?>"> 
<input type="button" value="<?php echo DELETE_TIP;?>" class="btn_del" data="<?php echo $no;?>"></legend>
<input type="hidden" name="cftype[<?php echo $no;?>]" value="<?php echo $val['type'];?>">
<?php switch($val['type']){
	case 'text':
?>
<textarea name="cfvalue[<?php echo $no;?>]"></textarea>
<?php
	break;
	case 'single':
?>
<input type="checkbox" value="1" name="cfvalue[<?php echo $no;?>]">
<?php
	break;
	case 'html':
?>
<textarea name="cfvalue[<?php echo $no;?>]" id="cfvalue[<?php echo $no;?>]"></textarea>
<script type="text/javascript">
<!--
	editorEnable('cfvalue[<?php echo $no;?>]');
//-->
</script>
<?php
	break;
}?>
</fieldset></li>
<?php
		}
	}
?>
</ol>
<div class="div_clear"></div>
<input type="hidden" id="cfno" name="cfno" value="<?php echo intval($no);?>"/>
<div class="mg5">
<select id="cftype">
	<option value="text"><?php echo TEXT;?></option>
	<option value="single"><?php echo SINGLE;?></option>
	<option value="html"><?php echo HTML;?></option>
</select> 
<input type="button" value="<?php echo ADD_CUSTOM_FIELD;?>" class="cf_add"> <?php echo CUSTOM_FIELD_TIP;?></div>
</fieldset>
<script type="text/javascript">
<!--
	var cfno = <?php echo intval($no);?>;
	_().ready(function(){
		_('.cf_add').bind('click',function(){
			cfno += 1;
			_('#cfno').val(cfno);
			var new_rule = document.createElement('li');
			var value_html;
			switch (_('#cftype').val())
			{
				case 'text':
					value_html = '<textarea name="cfvalue['+cfno+']"></textarea>';
				break;
				case 'single':
						value_html = '<input type="checkbox" name="cfvalue['+cfno+']" value="1"/>';
				break;
				case 'html':
					value_html = '<textarea name="cfvalue['+cfno+']" id="cfvalue['+cfno+']"></textarea>';
				break;
			}
			_(new_rule).attr('id','cf_'+cfno).html('<fieldset><legend><?php echo NAME;?> <input type="text" name="cfname['+cfno+']" size="30"> <?php echo SAVE_TO_LIST;?> <input type="checkbox" value="1" name="savelist['+cfno+']"> <input type="button" value="<?php echo DELETE_TIP;?>" class="cf_del" id="cf_'+cfno+'" data="'+cfno+'"><input type="hidden" name="cftype['+cfno+']" value="'+_('#cftype').val()+'"></legend>' + value_html + '</fieldset>');
			_('#cfList').append(new_rule);
			_('.cf_del').unbind().bind('click',function(){
				var no = _(this).attr('data');
				_('#cf_'+no).remove();
			});

			if (_('#cftype').val() == 'html'){
				editorEnable('cfvalue['+cfno+']');
			}
		});

		_('.cf_del').bind('click',function(){
			var no = _(this).attr('data');
			_('#cf_'+no).remove();
		});
	});
//-->
</script>