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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $str;?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/public.js"></script>
</head>
<body>
<script type="text/javascript">
<!--
	var to = '<?php echo $to?$to:$_SERVER['HTTP_REFERER'];?>';
	_().ready(function(){
		_().dialog({'content':'<div style="text-align:center;vertical-align:middle;margin:5px 0px;"><?php echo $str;?><?php if($to){?><div style="margin:5px;padding:5px;border-bottom:1px solid #ccc;color:#339900;"><?php echo ALERT_REDIRECT_TIP;?></div><?php }?></div>','name':'media','width':800,'height':500,'layer':true});
		setTimeout(function(){
				location.href = to;
		},3000);
	});
//-->
</script>
</body>
</html>
<?php if($to){exit();}?>