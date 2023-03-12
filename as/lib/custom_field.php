<?php
/**
 * Custom field template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
 $cfdata = is_string($cfdata['content']) ? unserialize($cfdata['content']) : array();
?>
<style>
#cfList select{
	min-width:100px;
	min-height:150px;
	text-align:center;
}
</style>
<fieldset><legend class="toggle" data=".cfbody"><?php _e('Custom Field');?></legend>
<input type="hidden" id="deletelist" name="deletelist"/>
<div class="cfbody<?php echo is_array($cf_rows) && count($cf_rows)?'':' hidden';?>">
<ol id="cfList">
<?php
	$no = 0;
	$cflist = array();
	foreach($cf_rows as $val){
		$no += 1;
		if($cfdata[$val['name']]){
			$cflist[$val['name']] = true;
		}
?>
<li id="cf_<?php echo $no;?>">
<fieldset><legend><?php _e('Name');?> 
<?php if(!$savelist_none):?>
<?php if($cfdata[$val['name']]):?>
<?php _e('Delete from list');?> <input type="checkbox" data="<?php echo $val['name'];?>" class="df_list"> 
<?php else:?>
<?php _e('Save to list');?> <input type="checkbox" data="<?php echo $val['name'];?>" name="savelist[<?php echo $no;?>]">
<?php endif;?> <?php endif;?></legend>
<div class="mb10"> <input type="text" name="cfname[<?php echo $no;?>]" value="<?php echo $val['name'];?>" class="input_text">
<input type="button" value="<?php _e('Delete');?>" class="cf_del" data="<?php echo $no;?>"></div>
<input type="hidden" name="cfid[<?php echo $no;?>]" value="<?php echo $val['id'];?>">
<?php if(($val['data_type'] == 'radio' && !$cfdata[$val['name']]['options']) || ($val['data_type'] == 'select' && !$cfdata[$val['name']]['options'])):?>
<input type="hidden" name="cftype[<?php echo $no;?>]" value="text">
<?php else:?>
<input type="hidden" name="cftype[<?php echo $no;?>]" value="<?php echo $val['data_type'];?>">
<?php endif;?>
<input type="hidden" name="cfoption[<?php echo $no;?>]" value="<?php echo $cfdata[$val['name']]['options'];?>">
<?php switch($val['data_type']){
	case 'password':
?>
<input type="password" name="cfvalue[<?php echo $no;?>]" value="<?php echo $val['value'];?>" class="input_text"/>
<?php
	break;
	case 'text':
?>
<textarea name="cfvalue[<?php echo $no;?>]"><?php echo $val['value'];?></textarea>
<?php
	break;
	case 'checkbox':
?>
<input type="checkbox" value="1" name="cfvalue[<?php echo $no;?>]" <?php echo $val['value']?'checked':'';?>>
<?php
	break;
	case 'radio':
		if($cfdata[$val['name']]['options']){
		$options = explode(',',$cfdata[$val['name']]['options']);
		foreach($options as $option):
?>
<input type="radio" name="cfvalue[<?php echo $no;?>]" <?php echo $val['value'] == $option?'checked':'';?> value="<?php echo $option;?>"> <?php echo $option;?> 
<?php
		endforeach;
		}else{
?>
<textarea name="cfvalue[<?php echo $no;?>]"><?php echo $val['value'];?></textarea>
<?php
		}
	break;
	case 'select':
		if($cfdata[$val['name']]['options']){
		$options = explode(',',$cfdata[$val['name']]['options']);
		$val['value'] = unserialize($val['value']);
?>
<select name="cfvalue[<?php echo $no;?>][]" multiple>
<?php foreach($options as $option):if($option):?>
	<option value="<?php echo $option;?>" <?php echo in_array($option,$val['value'])?'selected':'';?>><?php echo $option;?></option>
<?php endif;
endforeach;?>
</select>
<?php
	}else{
?>
<textarea name="cfvalue[<?php echo $no;?>]"><?php echo $val['value'];?></textarea>
<?php
	}
	break;
	case 'file':
?>
<textarea name="cfvalue[<?php echo $no;?>]" id="cfvalue[<?php echo $no;?>]"><?php echo getAttachmentUrl($val['value']);?></textarea> <a href="<?php echo getAttachmentUrl($val['value']);?>" target="_blank"><?php echo getAttachmentUrl($val['value']);?></a> <input type="button" value="<?php _e('Attach File');?>" class="replaceFile" data="<?php echo $no;?>">
<?php
	break;
	case 'html':
?>
<textarea name="cfvalue[<?php echo $no;?>]" id="cfvalue[<?php echo $no;?>]"><?php echo toggle_attachment($val['value'],'dashboard');?></textarea>
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
	if(is_array($cfdata) && count($cfdata) > count($cflist)){
?>
<?php if(count($cf_rows)):?>
<input type="button" value="<?php _e('>>>>>>>>>>>> More <<<<<<<<<<<<');?>" class="more_btn" data=".more_cf">
<?php endif;?>
<div class="more_cf<?php echo count($cf_rows)?' hidden':'';?>">
<?php
		foreach($cfdata as $key=>$val){
			if($cflist[$key]){
				continue;
			}
			$no += 1;
?>
<li id="cf_<?php echo $no;?>">
<fieldset><legend><?php _e('Name');?> 
<?php if(!$savelist_none):?><?php _e('Delete from list');?> <input type="checkbox" data="<?php echo $val['name'];?>" class="df_list"> <?php endif;?></legend>
<div class="mb10"><input type="text" name="cfname[<?php echo $no;?>]" value="<?php echo $val['name'];?>" class="input_text"> 
<input type="button" value="<?php _e('Delete');?>" class="cf_del" data="<?php echo $no;?>"></div>
<input type="hidden" name="cftype[<?php echo $no;?>]" value="<?php echo $val['type'];?>">
<input type="hidden" name="cfoption[<?php echo $no;?>]" value="<?php echo $val['options'];?>">
<?php switch($val['type']){
	case 'text':
?>
<textarea name="cfvalue[<?php echo $no;?>]"></textarea>
<?php
	break;
	case 'password':
?>
<input type="password" name="cfvalue[<?php echo $no;?>]" class="input_text"/>
<?php
	break;
	case 'checkbox':
?>
<input type="checkbox" value="1" name="cfvalue[<?php echo $no;?>]">
<?php
	break;
	case 'radio':
		$options = explode(',',$val['options']);
		foreach($options as $option):
?>
<input type="radio" name="cfvalue[<?php echo $no;?>]" value="<?php echo $option;?>"> <?php echo $option;?> 
<?php
		endforeach;
	break;
	case 'select':
		$options = explode(',',$val['options']);
?>
<select name="cfvalue[<?php echo $no;?>][]" multiple>
<?php foreach($options as $option):if($option):?>
	<option value="<?php echo $option;?>"><?php echo $option;?></option>
<?php endif;endforeach;?>
</select>
<?php
	break;
	case 'file':
?>
<textarea id="cfvalue[<?php echo $no;?>]" name="cfvalue[<?php echo $no;?>]"></textarea> <input type="button" value="<?php _e('Attach File');?>" class="replaceFile" data="<?php echo $no;?>">
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
?>
</div>
<?php
	}
?>
</ol>
<div class="div_clear"></div>
<input type="hidden" id="cfno" name="cfno" data="<?php echo intval($no);?>"<?php echo !is_array($cf_rows) || !count($cf_rows)?'':' value="'.intval($no).'"';?>/>
<div class="mg5">
<div class="form_split">
<select id="cftype">
	<option value="text"><?php _e('Text');?></option>
	<option value="password"><?php _e('Password');?></option>
	<option value="radio"><?php _e('Single');?></option>
	<option value="checkbox"><?php _e('Checkbox');?></option>
	<option value="select"><?php _e('List');?></option>
	<option value="html"><?php _e('HTML');?></option>
	<option value="file"><?php _e('Files');?></option>
</select> 
</div>
<div class="form_split">
<input type="button" value="<?php _e('Add Custom Field');?>" class="cf_add"></div>
<div class="form_split"><input type="button" value="<?php _e('Clean Custom Field');?>" class="cf_clean"> <div class="tip"><?php _e('One name & one value,these data will be listed by function get_custom_field,if you choose "save to field list",new item form will show this field');?>
</div>
</div></div>
</div>
</fieldset>
<script type="text/javascript">
<!--
	var cfno = <?php echo intval($no);?>,attach_media;
	_().ready(function(){
		_('.df_list').bind('click',function(){
			if (_(this).prop('checked'))
			{
				if (!confirm('<?php _e('This action can not be recover,are you sure delete it from custom field list?');?>'))
				{
					_(this).prop('checked',false);
					return ;
				}
				if (!_('#deletelist').val())
				{
					_('#deletelist').val(_(this).attr('data')+',');
				}else{
					_('#deletelist').val(_('#deletelist').val()+_(this).attr('data')+',');
				}
			}else{
				_('#deletelist').val((_('#deletelist').val()||'').replace(_(this).attr('data')+',',''));
			}
		});
		_('.more_btn').bind('click',function(){
			_(_(this).attr('data')).toggle();
			_(this).hide();
		});
		_('.toggle').bind('click',function(){
			if (!_('#cfno').val())
			{
				_('#cfno').val(_('#cfno').attr('data'));
			}else{
				_('#cfno').val(0);
			}
		});
		_('.cf_clean').bind('click',function(){
			for (var i = 1; i <= parseInt(_('#cfno').attr('data')) ;i++ ){
				_('#cf_' + i).remove();
			}
		});
		_('.cf_add').bind('click',function(){
			cfno += 1;
			_('#cfno').val(cfno);
			_('#cfno').attr('data',cfno);
			var new_rule = document.createElement('li');
			var value_html;
			switch (_('#cftype').val())
			{
				case 'text':
					value_html = '<textarea name="cfvalue['+cfno+']"></textarea>';
				break;
				case 'password':
					value_html = '<input type="password" name="cfvalue['+cfno+']">';
				break;
				case 'checkbox':
						value_html = '<input type="checkbox" name="cfvalue['+cfno+']" value="1"/>';
				break;
				case 'radio':
					var cfoption = window.prompt('<?php _e('Please input options,split by commas');?>');
					if (!cfoption)
					{
						alert('<?php _e('Options is required');?>');
						return ;
					}
					var tmp_opt,opts = cfoption.split(','),value_html = '';
					for (var i=0;i<opts.length ;i++ )
					{
						if (opts[i])
						{
							value_html += '<input type="radio"  value="'+opts[i]+'" name="cfvalue['+cfno+']"/> '+opts[i]+' ';
						}
					}
						
				break;
				case 'select':
					var cfoption = window.prompt('<?php _e('Please input options,split by commas');?>');
					if (!cfoption)
					{
						alert('<?php _e('Options is required');?>');
						return ;
					}
					var tmp_opt,opts = cfoption.split(','),value_html = '';
					for (var i=0;i<opts.length ;i++ )
					{
						if (opts[i])
						{
							value_html += '<option  value="'+opts[i]+'"/>'+opts[i]+'</option>';
						}
					}
					value_html = '<select name="cfvalue['+cfno+'][]" multiple>'+value_html+'</select>';
						
				break;
				case 'file':
						value_html = '<textarea name="cfvalue['+cfno+']" id="cfvalue['+cfno+']"></textarea> <input type="button" value="<?php _e('Attach File');?>" class="replaceFile" data="'+cfno+'">';
				break;
				case 'html':
					value_html = '<textarea name="cfvalue['+cfno+']" id="cfvalue['+cfno+']"></textarea>';
				break;
			}
			_(new_rule).attr('id','cf_'+cfno).html('<fieldset><legend><?php _e('Name');?> <?php if(!$savelist_none):?> <input type="button" value="<?php _e('Delete');?>" class="cf_del" id="cf_'+cfno+'" data="'+cfno+'"><input type="hidden" name="cftype['+cfno+']" value="'+_('#cftype').val()+'"><input type="hidden" name="cfoption['+cfno+']" value="'+cfoption+'"> <?php _e('Save to list');?> <input type="checkbox" value="1" name="savelist['+cfno+']"><?php endif;?></legend><div class="mb10"><input type="text" name="cfname['+cfno+']" class="input_text"></div>' + value_html + '</fieldset>');
			_('#cfList').append(new_rule);
			_('.cf_del').unbind('click').bind('click',function(){
				var no = _(this).attr('data');
				_('#cf_'+no).remove();
			});
			_('#cf_'+cfno).find('.input_text').run('focus');
			_('.replaceFile').unbind('click').bind('click',function(event){
				attach_media = _('#cfvalue['+_(this).attr('data')+']');
				_.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','title':'Choose file','name':'media','width':800,'height':500,'layer':true});
			});
			if (_('#cftype').val() == 'html'){
				editorEnable('cfvalue['+cfno+']');
			}
			if (_('#cftype').val() == 'file')
			{
				attach_media = _('#cfvalue['+cfno+']');
			}
		});

		_('.cf_del').bind('click',function(){
			var no = _(this).attr('data');
			_('#cf_'+no).remove();
		});
		
		_('.replaceFile').bind('click',function(event){
			attach_media = _('#cfvalue['+_(this).attr('data')+']');
			_.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','title':'Choose file','name':'media','width':800,'height':500,'layer':true});
		});
	});
//-->
</script>