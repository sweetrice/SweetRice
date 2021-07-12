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
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" name="viewport" id="viewport"/>
<title><?php _e('HTTP 404 - File not found');?></title>
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
<div class="header"><?php _e('HTTP 404 - File not found');?></div>
<div class="content">
):
<?php echo vsprintf(_t('%s You can Visit <a href="%s">%s</a> Home page'),array($tip_404,BASE_URL,$global_setting['name']));?>
</div><div id="div_foot">Powered by <a href="https://www.sweetrice.xyz">SweetRice.xyz</a> SweetRice.</div>

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