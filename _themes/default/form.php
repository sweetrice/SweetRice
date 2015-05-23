<?php
/**
 * Template Name:App form template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
 	defined('VALID_INCLUDE') or die();
	if(file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}
?>
<div id="div_center">
	<div id="div_right">
	<h1><?php echo _t('Please complete form').' '.$row['name']?></h1>
	<form method="<?php echo $row['method'];?>" enctype="multipart/form-data" action="<?php echo $row['action'];?>" id="app_form">
	<input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
		<?php foreach($fields as $val):
?>
		<fieldset class="app_fields <?php echo $val['required']?'required':'';?>" req="<?php echo $val['required'];?>">
			<legend><?php echo $val['tip'];?></legend>
			<div class="app_div">
<?php
		switch($val['type']){
			case 'text':
?><input name="<?php echo $val['name'];?>" class="input_text app_field" id="<?php echo $val['name'];?>" type="text">
<?php
			break;
			case 'password':
?><input name="<?php echo $val['name'];?>" class="input_text app_field" id="<?php echo $val['name'];?>" type="password">
<?php
			break;
			case 'textarea':
?>
<textarea id="<?php echo $val['name'];?>" name="<?php echo $val['name'];?>" class="input_textarea app_field"></textarea>
<?php
			break;
			case 'file':
?>
<input name="<?php echo $val['name'];?>" id="<?php echo $val['name'];?>" class="app_field" type="file">
<?php
			break;
			case 'checkbox':
?>
<input name="<?php echo $val['name'];?>" id="<?php echo $val['name'];?>" class="app_field" type="checkbox" value="1">
<?php
			break;
			case 'radio':
			foreach(explode(',',$val['option']) as $option):
?>
<input name="<?php echo $val['name'];?>" class="app_field" type="radio" value="<?php echo $option;?>"> <?php echo $option;?>
<?php
			endforeach;
			break;
			case 'select':
?>
		<select name="<?php echo $val['name'];?>" class="app_field">
<?php
			foreach(explode(',',$val['option']) as $option):
?>
<option value="<?php echo $option;?>"><?php echo $option;?></option>
<?php
			endforeach;
?>
		</select>
<?php
			break;
			case 'multi_file':
?>
	<div id="<?php echo $val['name'];?>_content" class="multi_field">
	<ol style="margin-left:20px;padding-left:0px;"><li><input name="<?php echo $val['name']?>[]" type="file" class="input_text app_field"> <input type="button" value="-" onclick="_(this).parent().remove();"></li>
	</ol>
	</div>
	<input type="button" value="+" class="add_<?php echo $val['name'];?>">
	<script type="text/javascript">
			_('.add_<?php echo $val['name']?>').bind('click',function(){
				var li = document.createElement('li');
				_(li).html('<input name="<?php echo $val['name']?>[]" type="file" class="input_text app_field"> <input type="button" value="-" onclick="_(this).parent().remove();">');
				_('#<?php echo $val['name']?>_content ol').append(li);
			});
	</script>
<?php
			break;
		
		}
	?>
	</div>
	</fieldset>
	<?php endforeach;?>
	<?php if($row['captcha']):?>
	<fieldset><legend><?php _e('Verification Code');?></legend>
<input type="text" id="code" name="code" size="6" maxlength="5"/> * <img id="captcha" src="images/captcha.png" align="absmiddle" title="<?php _e('Click to refresh');?>"/>
	</fieldset>
	<?php endif;?>
	<input type="submit" value="<?php _e('Submit');?>"/>
	</form>
	<script type="text/javascript">
	<!--
	_.ready(function(){
		_('#code').bind('focus',function(){
			if(_('#captcha').attr('src').indexOf('captcha.png') != -1){
				_('#captcha').attr('src','images/captcha.php?timestamp='+new Date().getTime());
			}
		});
		_('#captcha').bind('click',function(){
			_(this).attr('src','images/captcha.php?timestamp='+new Date().getTime());
		});
		_('.required .app_field').bind('change',function(){
			if (!_(this).val())
			{
				_(this).css({'background-color':'#ff0000'});
			}else{
				_(this).css({'background-color':'transparent'});
			}
		});
		_('#app_form').bind('submit',function(event){
			var isvalid = true;
			_('.app_fields').each(function(){
				if (_(this).attr('req') == '1')
				{
					var is_valid = false;
					_(this).find('.app_field').each(function(){
						if (_(this).val())
						{
							is_valid = true;
						}
					});
					if (!is_valid)
					{ 
						isvalid = false;
						_.ajax_untip('<?php _e('Some field required');?>');
						_('.required .app_field').each(function(){
							if (!_(this).val())
							{
								_(this).css({'background-color':'#ff0000'});
							}else{
								_(this).css({'background-color':'transparent'});
							}
						});
						_.stopevent(event);
						return;
					}
				}
			});
			if (isvalid && _('#code').size() && !_('#code').val())
			{
				_.ajax_untip('<?php _e('Captcha required');?>');
				_.stopevent(event);
				return;
			}
		});
	});
	//-->
	</script>
</div>
<?php		
	if(file_exists(THEME_DIR.$page_theme['sidebar'])){
		include(THEME_DIR.$page_theme['sidebar']);		
	}
?>
<div class="div_clear"></div></div>
<?php		
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>