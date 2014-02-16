<?php
/**
 * Template Name:404 page template.
 *
 * @package SweetRice
 * @Default template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	$_404_tip = '_404TIP_'.strtoupper($tip_404);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo _404_TITLE;?></title>
<style>
*{margin:0;}
body{font-family:Verdana,Georgia,arial,sans-serif;}
h1{color:#339900;}
#div_foot{	background-color:#444;	margin-top:10px;	height:30px;	line-height:30px;	color:#fff;padding:3px;}
#div_foot a:link{	color: #66CC00;	text-decoration: none;}
#div_foot a:visited{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
</style>
</head>
<body>
<h1><?php echo _404_H1;?></h1>
<?php echo vsprintf(_404_BODY,array(eval("echo $_404_tip;"),BASE_URL,$global_setting['name']));?>
</body>
</html>