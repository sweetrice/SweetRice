<?php
/**
 * App plugin shareFunction for SweetRice.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
	defined('VALID_INCLUDE') or die();
	define('THIS_APP','App');
	if(!defined('APP_DIR')){
		define('APP_DIR',str_replace('//','/',dirname(__FILE__).'/'));
	}
	define('APP_HOME',str_replace(SITE_HOME,SITE_URL,APP_DIR));
	define('ADB',DB_LEFT_PLUGIN);
	if(defined('DASHABOARD')){
		$lang = $global_setting['lang'];
	}else{
		$lang = themeLang().'.php';
	}
	if($lang && file_exists(APP_DIR.'lang/'.$lang)){
		init_lang(APP_DIR.'lang/'.$lang);
	}else{
		init_lang(APP_DIR.'lang/en-us.php');
	}

	class App
	{
		function app_links(){
			return array(
				'home'=>array('action'=>'pluginHook','plugin'=>THIS_APP)
			);
		}

		function app_url($page=false){
			if(!$page){
				$page = $_REQUEST['page'];
			}
			$app_links = $this->app_links();
			if(!$app_links[$page]){return ;}
			return BASE_URL.pluginHookUrl(THIS_APP,$app_links[$page]);
		}

		function app_navs(){
			return array(
				array('app_mode'=>'database','name'=>_t('Database')),
				array('app_mode'=>'menu','name'=>_t('Menu')),
				array('app_mode'=>'form','name'=>_t('Form')),
				array('app_mode'=>'form_data','name'=>_t('Form Data'))
			);
		}

		function app_actions(){
			$actions = array();
			foreach($this->app_navs() as $val){
				$actions[$val['app_mode']] = array();
			}
			return array_merge($actions,array(
				'links'=>array(),
				'form' => array(),
				'form_data' => array()
			));
		}

		function app_nav(){
			$_menu_data = db_arrays("SELECT * FROM `".ADB."_app_menus`");
			foreach($_menu_data as $val){
				$menu_data[$val['id']] = $val;
			}
			$app_menus = subMenus();
			$output = '<link href="'.APP_HOME.'css/app.css" rel="stylesheet" type="text/css" media="screen" /><script type="text/javascript" src="'.APP_HOME.'js/app.js"></script><div class="app_nav">';

			$nav_order = 0;
			foreach($app_menus as $nav){
				$nav_order += 1;
				$output .= '<a href="'.$menu_data[$nav['id']]['link_url'].'" '.($_SERVER['REQUEST_URI'] == $menu_data[$nav['id']]?'title="'.$menu_data[$nav['id']]['link_text'].'"':'').' navorder="'.$nav_order.'" parentid="'.$menu_data[$nav['id']]['parent_id'].'" menuid="'.$nav['id'].'" level="'.$nav['level'].'">'.$menu_data[$nav['id']]['link_text'].'</a>';
			}
			$output .= '<div class="nav_line"><span class="curr_line"></span></div><div class="curr_child"></div></div>';
			return $output;
		}

		function form_front($row){
			if(!$row['id']){
				return ;
			}
			$fields = unserialize($row['fields']);
		?>
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
	<?php
		}
		
	}
	
	function subMenus($sql='',$id=0,$level=0){
		$subMenus = array();
		$row = db_arrays("SELECT `id` FROM `".ADB."_app_menus` WHERE `parent_id` = '$id' ".$sql." ORDER BY `order` ASC ");
		foreach($row as $val){
			$val['level'] = $level;
			$subMenus[] = $val;
			$subMenus = array_merge ($subMenus,subMenus($sql,$val['id'],$level+1));
		}
		return $subMenus;
	}

	function remove_form_data($ids){
		$data = db_fetch(array('table'=>ADB.'_app_form_data as afd LEFT JOIN '.ADB.'_app_form as af ON af.id = afd.form_id',
			'field' => 'afd.*,af.name,af.fields',
			'where' => "afd.id = '$ids'"
		));
		foreach($data['rows'] as $val){
			$fields = unserialize($val['fields']);
			$form_data = unserialize($val['data']);
			foreach($fields as $field){
				if($field['type'] == 'file' && file_exists(APP_DIR.'data/form/'.$form_data[$field['name']])){
					unlink(APP_DIR.'data/form/'.$form_data[$field['name']]);	
				}elseif($field['type'] == 'multi_file'){
					foreach($form_data[$field['name']] as $mfile){
						if(file_exists(APP_DIR.'data/form/'.$mfile)){
							unlink(APP_DIR.'data/form/'.$mfile);
						}
					}
				}
			}
		}
		db_query("DELETE FROM `".ADB."_app_form_data` WHERE `id` IN($ids)");
	}

	$myApp = new App();
?>