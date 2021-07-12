<?php
/**
 * SweetRice error report
 *
 * @package SweetRice
 * @Default template
 * @since 1.3.2
 */
 	defined('VALID_INCLUDE') or die();
	$etypes = array(1=>'E_ERROR', 2=>'E_WARNING', 4=>'E_PARSE', 8=>'E_NOTICE', 16=>'E_CORE_ERROR', 32=>'E_CORE_WARNING',64=>'E_COMPILE_ERROR',128=>'E_COMPILE_WARNING',256=>'E_USER_ERROR',512=>'E_USER_WARNING',1024=>'E_USER_NOTICE',6143=>'E_ALL',2048=>'E_STRICT',4096=>'E_RECOVERABLE_ERROR');
	$db_error = db_error();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" name="viewport" id="viewport"/>
<title><?php _e('SweetRice error report');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<style>
*{margin:0;}
body{font-family:"Microsoft YaHei",Verdana,Georgia,arial,sans-serif;}
.header{line-height:30px;font-size:20px;background-color:#444;box-shadow:0px 0px 2px 2px #444;color:#fafafa;padding:0px 10px;}
#div_foot{	background-color:#444;height:30px;	line-height:30px;	color:#fff;text-align:center;padding:0px 10px;}
#div_foot a{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
.content{margin:0px 10px;}
.content div{margin-bottom:16px;}
</style>
</head>
<body>
<div class="header"><?php _e('SweetRice error report');?></div>
<div class="content">
<?php if($db_error):?>
<fieldset><legend><?php _e('Database');?></legend><?php echo db_error();?></fieldset>
<?php endif;?>
<?php
	if(is_array($errors)){
		foreach($errors as $key=>$val){
			if(in_array($key,array('file','line','message'))){
?>
<fieldset><legend><?php _e(ucfirst($key));?></legend><?php echo $key=='file'?substr($val,strlen(ROOT_DIR)-1):$val;?></fieldset>
<?php
			}
		}
	}
?>
<div class="clear"></div>
</div>
<div id="div_foot">
Powered By <a href="https://www.sweetrice.xyz">SweetRice</a>
</select>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.content').css({'margin-top':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px'});
	});
	_(window).bind('resize',function(){
		_('.content').animate({'margin-top':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px'});
	});
//-->
</script>
</body>
</html>