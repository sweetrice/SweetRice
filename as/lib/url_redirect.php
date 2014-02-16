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
<fieldset><legend><?php echo EDIT.' '.URL_REDIRECT;?> - <?php echo URL_REDIRECT_TITLE;?></legend>
<ol id="redirectList">
<?php
$no = 0;
	foreach($redirectList as $key=>$val){
		$no += 1;
?>
<li id="_<?php echo $no;?>"><input type="text" name="k[<?php echo $no;?>]" value="<?php echo str_replace('\\\\','\\',$key);?>" size="30"> -> <input type="text" name="t[<?php echo $no;?>]" value="<?php echo $val;?>" size="30"> <input type="radio" value="1" name="r[<?php echo $no;?>]" checked> <?php echo URL_REDIRECT;?> <input type="radio" value="0" name="r[<?php echo $no;?>]" > <?php echo URL_PARSE;?> <input type="button" value="<?php echo DELETE_TIP;?>" class="btn_del" data="<?php echo $no;?>"></li>
<?php
	}
	foreach($parseList as $key=>$val){
	$no += 1;
?>
<li id="_<?php echo $no;?>"><input type="text" name="k[<?php echo $no;?>]" value="<?php echo str_replace('\\\\','\\',$key);?>" size="30"> -> <input type="text" name="t[<?php echo $no;?>]" value="<?php echo $val;?>" size="30"> <input type="radio" value="1" name="r[<?php echo $no;?>]"> <?php echo URL_REDIRECT;?> <input type="radio" value="0" name="r[<?php echo $no;?>]" checked> <?php echo URL_PARSE;?> <input type="button" value="<?php echo DELETE_TIP;?>" class="btn_del" data="<?php echo $no;?>"></li>
<?php
	}
?>
</ol>
<div class="div_clear"></div>
<input type="hidden" id="no" value="<?php echo intval($no);?>"/>
<input type="button" value="<?php echo ADD_URL_RULE;?>" class="btn_add">
<p><?php echo URL_REDIRECT_TIPS;?></p>
</fieldset>
<input type="submit" class="input_submit" value="<?php echo UPDATE;?>"/>
</form>
<script type="text/javascript">
<!--
	var urno = <?php echo intval($no);?>;
	var URL_REDIRECT = '<?php echo URL_REDIRECT;?>';
	var URL_PARSE = '<?php echo URL_PARSE;?>';
	var DELETE_TIP = '<?php echo DELETE_TIP;?>';
	_().ready(function(){
		_('.btn_add').bind('click',function(){
			urno += 1;
			_('#no').val(urno);
			var new_rule = document.createElement('li');
			_(new_rule).attr('id','_'+urn).html('<input type="text" name="k['+urno+']" size="30"> -> <input type="text" name="t['+urno+']" size="30"> <input type="radio" value="1" name="r['+urno+']"> '+URL_REDIRECT+' <input type="radio" value="0" name="r['+urno+']"> '+URL_PARSE+' <input type="button" value="'+DELETE_TIP+'" class="btn_del" id="btn_'+urno+'" data="'+urno+'">');
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
	});
//-->
</script>