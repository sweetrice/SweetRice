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
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SweetRice error report</title>
<style>
*{margin:0;}
body{font-family:Verdana,Georgia,arial,sans-serif;font-size:small;}
h1{color:#339900;}
#errorBody{margin:10px 0px;padding:5px;background-color:#444;color:#FF9900;}
#errorBody dl{clear:both;}
#errorBody dt{width:20%;float:left;background-color:;}
#errorBody dd{width:79%;float:right;}
.clear{clear:both;}
#div_foot{	background-color:#444;	margin-top:10px;	height:30px;	line-height:30px;	color:#fff;padding:3px;}
#div_foot a:link{	color: #66CC00;	text-decoration: none;}
#div_foot a:visited{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
</style>
</head>
<body>
<h1>SweetRice error report</h1>
<div id="errorBody">
<dl><dt>DB error</dt><dd><?php echo db_error();?></dd></dl>
<?php
	if(is_array($errors)){
		foreach($errors as $key=>$val){
			if(in_array($key,array('file','line','message'))){
?>
<dl><dt><?php echo $key;?></dt><dd><?php echo $key=='file'?substr($val,strlen(ROOT_DIR)-1):$val;?></dd></dl>
<?php
			}
		}
	}
?>
<div class="clear"></div>
</div>
<div id="div_foot">
Copyright &copy; <?php echo date('Y');?><a href="<?php echo BASE_URL;?>"><?php echo $global_setting['name'];?></a> Powered By <a href="http://www.basic-cms.org">Basic CMS SweetRice</a>
</select>
</div>
</body>
</html>