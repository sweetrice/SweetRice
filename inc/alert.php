<?php
/**
 * Alert page
 *
 * @package SweetRice
 * @Default template
 * @since 1.3.2
 */
 	defined('VALID_INCLUDE') or die();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" name="viewport" id="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php _e('SweetRice notice');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<style>
*{margin:0;}
body{font-family:Verdana,Georgia,arial,sans-serif;}
h1{height:60px;line-height:60px;border-bottom:1px solid #555;color:#555;padding-left:30px;}
#div_foot{	background-color:#444;height:30px;	line-height:30px;	color:#fff;text-align:center;}
#div_foot a{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
.content{text-align:center;}
.content div{margin-bottom:16px;}
</style>
</head>
<body>
<h2><?php _e('You will be redirected in 3 seconds.');?></h2>
<div class="content">
<div><?php echo $str;?></div>
<div><a href="<?php echo $to?$to:$_SERVER['HTTP_REFERER'];?>"><?php _e('If your browser does not redirect automatically,please click here.');?></a></div>
</div>
<div id="div_foot">Powered by <a href="http://www.basic-cms.org">Basic-CMS.ORG</a> SweetRice.</div>
<script type="text/javascript">
<!--
	var to = '<?php echo $to?$to:$_SERVER['HTTP_REFERER'];?>';
	_().ready(function(){
		setTimeout(function(){
				location.href = to;
		},3000);
		_('.content').css({'margin-top':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px'});
	});
	_(window).bind('resize',function(){
		_('.content').animate({'margin-top':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-91-_('.content').height())/2)+'px'});
	});
//-->
</script>
</body>
</html>
<?php if($to){exit();}?>