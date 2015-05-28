<?php
/**
 * Template Name:404 page template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('HTTP 404 - File not found');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<style>
*{margin:0;}
body{font-family:Verdana,Georgia,arial,sans-serif;}
h1{height:60px;line-height:60px;border-bottom:1px solid #555;color:#555;padding-left:30px;}
#div_foot{	background-color:#444;height:30px;	line-height:30px;	color:#fff;text-align:center;}
#div_foot a{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
.content{text-align:center;}
</style>
</head>
<body>
<h1><?php _e('HTTP 404 - File not found');?></h1>
<div class="content">
):
<?php echo vsprintf(_t('%s You can Visit <a href="%s">%s</a> Home page'),array($tip_404,BASE_URL,$global_setting['name']));?>
</div><div id="div_foot">Powered by <a href="http://www.basic-cms.org">Basic-CMS.ORG</a> SweetRice.</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.content').css({'line-height':(_.pageSize().windowHeight-91)+'px','height':(_.pageSize().windowHeight-91)+'px'});
	});
	_(window).bind('resize',function(){
		_('.content').animate({'line-height':(_.pageSize().windowHeight-91)+'px','height':(_.pageSize().windowHeight-91)+'px'});
	});
//-->
</script>
</body>
</html>