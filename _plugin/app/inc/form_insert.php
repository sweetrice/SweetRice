<?php
/**
 * Form insert template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
defined('VALID_INCLUDE') or die();
?>
<form method="post" id="form_form" action="./?type=plugin&plugin=<?php echo THIS_APP; ?>&app_mode=form&mode=insert">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl; ?>"/>
<input type="hidden" name="id" value="<?php echo $row['id']; ?>"/>
<fieldset><legend><?php _e('Name');?></legend>
	<input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"/>
</fieldset>
<fieldset><legend><?php _e('Form\'s action');?></legend>
	<input type="text" id="action" name="action" value="<?php echo $row['action']; ?>" class="input_text"/> <span class="tip"><?php _e('Enter custom action url or let this empty');?></span>
</fieldset>
<fieldset><legend><?php _e('Method');?></legend>
	<?php _e('POST');?> <input type="radio" name="method" value="post" <?php echo $row['method'] == 'post' || !$row['method'] ? 'checked' : ''; ?>/>
	<?php _e('GET');?> <input type="radio" name="method" value="get" <?php echo $row['method'] == 'get' ? 'checked' : ''; ?>/>
</fieldset>
<fieldset><legend><?php _e('Enable Captcha');?></legend>
	<input type="checkbox" id="captcha" name="captcha" value="1" <?php echo $row['captcha'] == 1 ? 'checked' : ''; ?>/>
</fieldset>
<fieldset><legend><?php _e('Field List');?></legend>
	<div id="field_list">
	<ol>
	<?php foreach ($fields as $val): ?>
	<li style="margin: 10px 0px;">
	<div class="form_split"><?php _e('Field Name');?> : </div>
	<div class="form_split"><input name="fields[]" style="margin:0px;" type="text" value="<?php echo $val['name']; ?>"></div>
	<div class="form_split"> <?php _e('Show Name');?> : </div>
	<div class="form_split"><input name="tips[]" style="margin:0px;" type="text" value="<?php echo $val['tip']; ?>"></div>
	<input name="types[]" type="hidden" value="<?php echo $val['type']; ?>">
	<?php if ($val['option']): ?>
	<div class="form_split"> <?php _e('Options');?></div>
	<div class="form_split"> <input name="option[]" type="text" value="<?php echo $val['option']; ?>" style="margin:0px;">
	</div>
	<?php else: ?>
	<input name="option[]" type="hidden" value="">
	<?php endif;?>
	<?php if ($val['type'] == 'select'): ?>
	<div class="form_split"> <?php _e('Multiple');?></div>
	<div class="form_split"> <input name="select_multiple[]" type="checkbox" value="1" <?php echo $val['select_multiple'] ? 'checked="checked"' : ''; ?> style="margin:0px;">
	</div>
	<?php else: ?>
	<input name="select_multiple[]" type="hidden" value="0">
	<?php endif;?>
	<div class="form_split"> <?php _e('Required');?> <input type="hidden" name="required[]" value="<?php echo $val['required']; ?>"/><input type="checkbox" style="margin-top:0px;" <?php echo $val['required'] ? 'checked="true"' : ''; ?> onclick="if(this.checked){_(this).prev().val(1);}else{_(this).prev().val(0);}"/> <input value="-" onclick="_(this).parent().parent().remove();" type="button"></div></li>
	<?php endforeach;?>
	</ol>
	</div>
	<?php _e('Please split options by Comma');?>
	<div >
<select id="cftype" style="margin:0px;">
	<option value="text"><?php _e('Text');?></option>
	<option value="password"><?php _e('Password');?></option>
	<option value="textarea"><?php _e('Textarea');?> </option>
	<option value="radio"><?php _e('Single');?></option>
	<option value="checkbox"><?php _e('Checkbox');?></option>
	<option value="select"><?php _e('List');?></option>
	<option value="file"><?php _e('Files');?></option>
	<option value="multi_file"><?php _e('Multiple File');?></option>
</select> <input type="button" value="+" class="add_field">
</div>
</fieldset>
<fieldset><legend><?php _e('Template');?>:<?php echo $row['template']; ?></legend>
<select name="template">
<option value=""><?php _e('Default');?></option>
<?php
foreach ($template as $key => $val) {
    $s = '';
    if ($key == $row['template']) {
        $s = 'selected';
    }
    echo '<option value="' . $key . '" ' . $s . ' title="' . $key . '">' . $val . '</option>';
}
?>
</select>
</fieldset>
<input type="submit" value="<?php _e('Done');?>">
</form>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.add_field').bind('click',function(){
				var li = document.createElement('li');
				var type = _('#cftype').val();
				var option = '<input type="hidden" name="option[]">';
				if (type == 'radio' || type == 'select')
				{
					option = '<div class="form_split"><?php _e('Options');?></div><div class="form_split"> <input type="text" name="option[]" style="margin:0px;"></div>';
					if (type == 'select')
					{
						option += '<div class="form_split"><?php _e('Multiple');?> <input type="checkbox" name="select_multiple[]" style="margin:0px;"></div>';
					}
				}
				_(li).css({'margin':'10px 0px'}).html('<div class="form_split"><?php _e('Field Name');?></div><div class="form_split"> <input name="fields[]" type="text" style="margin:0px;"></div><div class="form_split"> <?php _e('Show Name');?></div><div class="form_split"> <input name="tips[]" style="margin:0px;" type="text"></div><input type="hidden" name="types[]" value="'+type+'"/> '+option+'<div class="form_split"> <?php _e('Required');?> <input type="hidden" name="required[]"/><input type="checkbox" style="margin-top:0px;" onclick="if(this.checked){_(this).prev().val(1);}else{_(this).prev().val(0);}"/> <input type="button" value="-" onclick="_(this).parent().parent().remove();"></div>');
				_('#field_list ol').append(li);
			});
		_('#form_form').bind('submit',function(event){
			if (!_('#name').val()){
				alert('<?php _e('Name is required');?>');
				_().stopevent(event);
			}
		});

	});
//-->
</script>