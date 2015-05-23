<?php
/**
 * Head section template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
 $top_height = in_array($_COOKIE['top_height'],array('small','normal'))?$_COOKIE['top_height']:'';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" name="viewport" id="viewport"/>
<title><?php echo ($top_word?$top_word:_t('Welcome to SweetRice!')),' - ',_t('Dashboard');?></title>
<link rel="stylesheet" type="text/css" href="site.css">
<script type="text/javascript" src="../js/public.js"></script>
<script type="text/javascript" src="js/function.js"></script>
<script type="text/javascript" src="js/dashboard.js"></script>
</head>
<body id="body">
<div id="div_top">
<div id="top_image"><a href="<?php echo BASE_URL;?>" target="_blank"><img src="<?php echo BASE_URL;?><?php echo $top_height!='normal'?'images/sweetrice_icon.png':($global_setting['logo']?ATTACHMENT_DIR.$global_setting['logo']:'images/sweetrice.png');?>" alt="<?php echo $global_setting['name'];?>" id="logo"></a></div>
<div id="top_word">
<h1><?php echo $top_word?ucfirst($type).' -&gt; '.$top_word:_t('Welcome to SweetRice!');?></h1>
<div id="top_line" data="<?php echo $top_height;?>">......</div>
</div>
<div class="div_clear"></div>
</div>
<div id="toggle_nav"></div>