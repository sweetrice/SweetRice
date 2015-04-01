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
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('SweetRice error report');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/public.js"></script>
<style>
*{margin:0;}
body{font-family:Verdana,Georgia,arial,sans-serif;background-color:#555;}
h1{height:60px;line-height:60px;border-bottom:1px solid #fff;color:#fff;padding-left:30px;}
#div_foot{	background-color:#444;height:30px;	line-height:30px;	color:#fff;text-align:center;}
#div_foot a{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
.content{text-align:center;background-color:#fff;}
.content dl{clear:both;}
.content dt{width:20%;float:left;display:inline;text-align:right;}
.content dd{width:79%;float:right;display:inline;text-align:left;}
.clear{clear:both;}
</style>
</head>
<body>
<h1><?php _e('SweetRice error report');?></h1>
<div class="content">
<?php if($db_error):?>
<dl><dt><?php _e('Database');?></dt><dd><?php echo db_error();?></dd></dl>
<?php endif;?>
<?php
	if(is_array($errors)){
		foreach($errors as $key=>$val){
			if(in_array($key,array('file','line','message'))){
?>
<dl><dt><?php _e(ucfirst($key));?></dt><dd><?php echo $key=='file'?substr($val,strlen(ROOT_DIR)-1):$val;?></dd></dl>
<?php
			}
		}
	}
?>
<div class="clear"></div>
</div>
<div id="div_foot">
<?php _e('Copyright')?> &copy; <?php echo date('Y');?> <a href="<?php echo BASE_URL;?>"><?php echo $global_setting['name'];?></a> Powered By <a href="http://www.basic-cms.org">Basic CMS SweetRice</a>
</select>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.content').css({'margin-top':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px'});
	});
	_(window).bind('resize',function(){
		_('.content').animate({'margin-top':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px'});
	});
//-->
</script>
</body>
</html>