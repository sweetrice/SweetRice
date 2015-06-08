<?php
/**
 * Site close tip
 *
 * @package SweetRice
 * @Default template
 * @since 1.3.2
 */
 	defined('VALID_INCLUDE') or die();
?>
<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" name="viewport" id="viewport"/><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title><?php _e('Welcome to SweetRice - Thank your for install SweetRice as your website management system.');?></title>
<style>body{text-align:center;font-family:Verdana,Georgia,arial,sans-serif;}</style>
</head>
<body>
<?php echo $global_setting['close_tip'];?>
</body>
</html>