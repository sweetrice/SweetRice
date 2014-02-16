<?php
/**
 * Template Name:Header section template.
 *
 * @package SweetRice
 * @Wblog template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title?$title:$global_setting['title'];?></title>
<base href="<?php echo BASE_URL;?>">
<meta name="keywords" content="<?php echo $keywords?$keywords:$global_setting['keywords'];?>" />
<meta name="description" content="<?php echo $description?$description:$global_setting['description'];?>"/>
<link rel="alternate" type="application/rss+xml" title="<?php echo $global_setting['name'];?>" href="<?php echo show_link_rssfeed();?>" />
<?php echo $rssfeed;?>
<meta name="generator" content="SweetRice <?php echo SR_VERSION;?>" />
<meta name="copyright" content="<?php echo $global_setting['name'];?>" />
<link href="<?php echo THEME_URL.$page_theme['css'];?>" rel="stylesheet" type="text/css" media="screen" />
<link rel="shortcut icon" href="images/favicon.ico" />
<script type="text/javascript" src="js/public.js"></script>
<script type="text/javascript" src="js/function.js"></script>
</head>
<body>
<div id="header">
<div id="logo"><a href="<?php echo BASE_URL;?>"><img src="<?php echo $global_setting['logo']?ATTACHMENT_DIR.$global_setting['logo']:'images/sweetrice.jpg';?>" alt="<?php echo $global_setting['name'];?>"></a>
</div>
<div id="navbar">
<div class="top_word"><?php echo $top_word?$top_word:$global_setting['name'];?></div>
<?php
	if(file_exists(THEME_DIR.$page_theme['sidebar'])){
		include(THEME_DIR.$page_theme['sidebar']);
	}
?>
<div class="div_clear"></div></div>
</div>
</div>