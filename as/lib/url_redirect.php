<?php
/**
 * URL Redirect management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.2.0
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="post" action="./?type=url_redirect&mode=save">
<fieldset><legend><?php _e('URL Redirect Setting');?></legend>
<?php _e('Set URL redirect(301) here - this feature only available if URL rewrite enabled');?>
<div>
<ol id="redirectList">
<?php
$no = 0;
	foreach($redirectList as $key=>$val){
		$no += 1;
?>
<li id="_<?php echo $no;?>"><span><input type="text" name="k[<?php echo $no;?>]" value="<?php echo str_replace('\\\\','\\',$key);?>"></span> &raquo; <span><input type="text" name="t[<?php echo $no;?>]" value="<?php echo $val;?>"></span> <span><input type="radio" value="1" name="r[<?php echo $no;?>]" checked> <?php _e('URL Redirect');?> <input type="radio" value="0" name="r[<?php echo $no;?>]" > <?php _e('URL Parse');?> <input type="button" value="<?php _e('Delete');?>" class="btn_del" data="<?php echo $no;?>"> <a href="javascript:void(0);" key="<?php echo BASE_URL.str_replace('\\\\','\\',$key);?>" class="btn_test"><?php _e('Test');?></a></span></li>
<?php
	}
	foreach($parseList as $key=>$val){
	$no += 1;
?>
<li id="_<?php echo $no;?>"><span><input type="text" name="k[<?php echo $no;?>]" value="<?php echo str_replace('\\\\','\\',$key);?>"></span> &raquo; <span><input type="text" name="t[<?php echo $no;?>]" value="<?php echo $val;?>" ></span> <span><input type="radio" value="1" name="r[<?php echo $no;?>]"> <?php _e('URL Redirect');?> <input type="radio" value="0" name="r[<?php echo $no;?>]" checked> <?php _e('URL Parse');?> <input type="button" value="<?php _e('Delete');?>" class="btn_del" data="<?php echo $no;?>"> <a href="javascript:void(0);" key="<?php echo BASE_URL.str_replace('\\\\','\\',$key);?>" class="btn_test"><?php _e('Test');?></a></span></li>
<?php
	}
?>
</ol>
</div>
<input type="hidden" id="no" value="<?php echo intval($no);?>"/>
<input type="button" value="<?php _e('Add URL Rule');?>" class="btn_add">
<div class="tip"><?php _e('<p>Input rule:please note that the source URL must without "http(s)://" and your domain.</p>example: <ol><li>enter <strong>source.html->destination.html</strong> to redirect http(s)://yourdomain.com/source.html to http(s)://yourdomain.com/destination.html</li><li>enter <strong>source.html->http(s)://otherdomain.com/destination.html</strong> to redirect http(s)://yourdomain.com/source.html to http(s)://otherdomain.com/destination.html</li><li>Support regular rule,example: <strong>/^page\/([a-z0-9]+)\.html$/i->action=post&sys_name=$1</strong> to parse the url and <strong>/^page\/([a-z0-9]+)\.html$/i->$1.html</strong> to redirect the url.</li></ol>');?></div>
</fieldset>
<input type="submit" class="input_submit" value="<?php _e('Update');?>"/>
</form>
<a id="test_link" target="_blank"></a>
<script type="text/javascript">
<!--
	var urno = <?php echo intval($no);?>;
	_().ready(function(){
		_('.btn_add').bind('click',function(){
			urno += 1;
			_('#no').val(urno);
			var new_rule = document.createElement('li');
			_(new_rule).attr('id','_'+urno).html('<span><input type="text" name="k['+urno+']"></span> &raquo; <span><input type="text" name="t['+urno+']"></span> <span><input type="radio" value="1" name="r['+urno+']"> <?php _e('URL Redirect');?> <input type="radio" value="0" name="r['+urno+']"> <?php _e('URL Parse');?> <input type="button" value="<?php _e('Delete');?>" class="btn_del" id="btn_'+urno+'" data="'+urno+'"></span>');
			_('#redirectList').append(new_rule);
			_('.btn_del').unbind().bind('click',function(){
				var no = _(this).attr('data');
				_('#_'+no).remove();
			});
		});

		_('.btn_del').bind('click',function(){
			var no = _(this).attr('data');
			_('#_'+no).remove();
		});

		_('.btn_test').bind('click',function(){
			var url = prompt('<?php _e('Test link below');?>',_(this).attr('key'));
			if (url)
			{
				_('#test_link').attr({'href':url}).run('click');
			}
		});
	});
//-->
</script>