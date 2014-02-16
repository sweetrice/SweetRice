<?php
/**
 * Subscriber template.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	if(file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}
?>
<style>
#div_center{min-height:300px;text-align:center;padding-top:50px;}
#subscriber_email{width:300px;}
</style>
<script type="text/javascript">
<!--
	var INVALID_EMAIL = '<?php echo INVALID_EMAIL;?>';
	var SUBSCRIBER_URL = '<?php echo pluginHookUrl(THIS_PLUGIN,array('plugin_mod'=>'subscriber'));?>';
	_().ready(function(){
		_('#subscriber_button').bind('click',function(){
			var email = _('#subscriber_email').val();
			if(!CheckEmail(email)){
				alert(INVALID_EMAIL);
				_('#subscriber_email').run('focus');
				return ;
			}
			var query = new Object();
			query.email = escape(email);
			var ajax_dlg = _.dialog({'name':'ajax_dlg','content':'<img src="images/ajax-loader.gif">'});
			_.ajax({
				'type':'POST',
				'data':query,
				'url':SUBSCRIBER_URL,
				'success':function(result){
					ajax_dlg.remove();
					if (typeof(result) == 'object'){
						_.ajax_untip(result['status_code']);
					}
				}
			});
		});
	});
//-->
</script>
<div id="div_center">
<h1><?php echo SUBSCRIBER_FORM;?></h1>
<input type="text" id="subscriber_email"/> <input type="button" id="subscriber_button" value="<?php echo SUBSCRIBER;?>"/>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);		
	}
?>