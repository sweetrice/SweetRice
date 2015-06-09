<?php
/**
 * Bugs management template.
 *
 * @package SweetRice
 * @Plugin member
 * @since 1.3.4
 */
	defined('VALID_INCLUDE') or die();
	if($_GET['mode'] == 'clean'){
		$_SESSION['imgs'] = array();
	}
	if($_GET['mode'] == 'delete'){
		$img = str_replace(SITE_URL,SITE_HOME,$_POST['img']);
		if($img && is_file($img)){
			unlink($img);
			$tmp = array();
			foreach($_SESSION['imgs'] as $val){
				if($val != $_POST['img']){
					$tmp[] = $val;
				}
			}
			$_SESSION['imgs'] = $tmp;
			output_json(array('status'=>1,'img'=>$img,'data'=>$_POST['img']));
		}else{
			output_json(array('status'=>1,'status_code'=>_t('No image selected')));
		}
	}
	$dest_dir = date('Ymd').'/';
	$tmp_dir = SITE_HOME.ATTACHMENT_DIR.$dest_dir;
	if(!is_dir($tmp_dir)){
		mkdir($tmp_dir);
	}
	if(is_array($_FILES['imgs']['name'])){
		foreach($_FILES['imgs']['name'] as $key=>$val){
			$tmp = array(
				'name' => $_FILES['imgs']['name'][$key],
				'type' => $_FILES['imgs']['type'][$key],
				'tmp_name' => $_FILES['imgs']['tmp_name'][$key],
				'error' => $_FILES['imgs']['error'][$key],
				'size' => $_FILES['imgs']['size'][$key]
			);
			
			if(substr($tmp['name'],-4) == '.zip'){
				$data = extractZIP($tmp['tmp_name'],$tmp_dir);
				foreach($data as $val){
					$val = str_replace(SITE_HOME,SITE_URL,$val);
					if($val && !in_array($val,$_SESSION['imgs'])){
						$_SESSION['imgs'][] = $val;
					}
				}
			}else{
				$upload = upload_($tmp,$tmp_dir,$tmp['name'],null);
				if($upload && file_exists($tmp_dir.$upload)){
					if(!in_array(SITE_URL.ATTACHMENT_DIR.$dest_dir.$upload,$_SESSION['imgs'])){
						$_SESSION['imgs'][] = SITE_URL.ATTACHMENT_DIR.$dest_dir.$upload;
					}
				}
			}
		}
		_goto('./?type=image');
	}elseif($_FILES['imgs']['name']){
		if(substr($_FILES['imgs']['name'],-4) == '.zip'){
			$data = extractZIP($_FILES['imgs']['tmp_name'],$tmp_dir);
			foreach($data as $val){
				$val = str_replace(SITE_DIR,SITE_HOME,$val);
				if($val && !in_array($val,$_SESSION['imgs'])){
					$_SESSION['imgs'][] = $val;
				}
			}
		}else{
			upload_($_FILES['imgs'],$tmp_dir,$_FILES['imgs']['name'],null);
			if(!in_array(BASE_URL.ATTACHMENT_DIR.$dest_dir.$upload,$_SESSION['imgs'])){
				$_SESSION['imgs'][] = BASE_URL.ATTACHMENT_DIR.$dest_dir.$upload;
			}
		}
		_goto('./?type=image');
	}
	define('UPLOAD_MAX_FILESIZE',ini_get('upload_max_filesize'));
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('Dashboard');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<style>
body{font-family:"Microsoft YaHei";font-size:small;}
input[type=button], input[type=submit] {
	padding: 2px 8px !important;
	border: none;
	-moz-box-sizing: content-box;
	-webkit-box-sizing: content-box;
	-khtml-box-sizing: content-box;
	box-sizing: content-box;
	cursor:pointer;
	color: #ffffff;
	background-color:#669900;
	text-shadow:0 -1px 0 rgba(0, 0, 0, 0.4);
	height:25px;
	line-height:25px;
}
input[type=button]:hover, input[type=submit]:hover {
	border-color: #669966;
	background-color:#99CC00;
	height:25px;
	line-height:25px;
}
select{
	height:35px;
}
select option{
	line-height:35px;
}
input[type=text]:focus,input[type=checkbox]:focus,input[type=password]:focus,select:focus,textarea:focus{
-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(125, 125, 125, 0.6);
box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(125, 125, 125, 0.6);
}
p{
	margin:5px 0px;
}
fieldset{
	padding:10px;
	margin:10px 0px;
	border:1px dotted #ccc;
}
fieldset:hover{
	border:1px solid #ccc;
	box-shadow: 5px 5px 10px #ccc;
}
fieldset li{
	margin:5px 0px 0px 20px;
}
fieldset legend{
	font-weight:bold;
	padding:0px 5px;
}
fieldset label{
	background-color:#fff;
	margin-left:2px;
	padding:5px 10px;
	cursor:pointer;
	height:25px;
	line-height:25px;
}
fieldset label:hover{
	background-color:#669900;
	color:#ffffff;
}ul,ol{
	margin-left:2px;
}

textarea{
	border:1px #999999 solid;
	background-color:#fafafa;
	-webkit-box-shadow: 3px 3px 15px #ccc;    
	-moz-box-shadow: 3px 3px 15px #ccc;    
	box-shadow: 3px 3px 15px #ccc; 
	border-radius:5px;
	padding:1%;
	margin:5px 0px;
	width:98%;
}
iframe{
	border:0;
}
input[type=text],input[type=password]{
	background-color:#fff;
	padding:3px 5px;
	height:24px;
	line-height:24px;
	font-size:18px;
	border: 1px solid #ccc;
}
input[type=file]{
	height:30px;
	line-height:30px;
	border: 1px solid #ccc;
}
.imgs{max-height:450px;}
.imgs ul{margin: 10px 0px; padding: 0px;}
.imgs li{width:18%;float:left;display:inline;list-style-type:none;height:100px;margin-bottom:10px;position:relative;box-shadow: 2px 2px 5px #ccc;margin:1%;text-align:center;padding: 5px 0px;}
.imgs li img{max-width:98%;max-height:98%;border:1px solid #d8d8d8;}
.imgs li input[type=checkbox]{position:absolute;right:5px;bottom:5px;}
.img_delete{position:absolute;left:5px;bottom:5px;width:16px;height:16px;line-height:16px;border:1px solid #ccc;text-decoration: none;color:#ccc;background-color: #fff;cursor:pointer;display:none;}
.clear{clear:both;}
.form_split{line-height:30px;display:inline;margin:2px 10px;height:30px;}
input[type=file]{
	width:250px;
}
@media (max-width: 640px){
	.form_split{float:none;margin-left:5px;display:block;height:auto;}
	.imgs li{width:48%;}
	input[type=file]{
		width:188px;
	}
}
</style>
</head>
<body>
<form method="post" action="" enctype="multipart/form-data" >
<div class="form_split">
	<input type="file" name="imgs[]" title="<?php echo _t('Max upload file size'),':',UPLOAD_MAX_FILESIZE;?>" multiple> <input type="submit" value="<?php _e('Upload');?>" class="input_submit"/></div>
	<div class="form_split"><?php _e('Supports zip archive');?>
	<?php _e('all');?> <input type="checkbox" class="ck_item"><input type="button" value="<?php _e('Insert images');?>" class="btn_attach"> <input type="button" value="<?php _e('Reset');?>" class="btn_clean"></div>
</form>
<div class="imgs">
<ul>
<?php 
foreach($_SESSION['imgs'] as $img):?>
<li data="<?php echo $img;?>"><img src="<?php echo $img;?>"><input type="checkbox" class="imglist" value="<?php echo $img;?>" /> <a href="javascript:void(0);" class="img_delete">&times;</a></li>
<?php endforeach;?>
<div class="clear"></div>
</ul>
</div>
<script type="text/javascript">
<!--
	_.ready(function(){
		_('.img_delete').click(function(){
			var _this = this;
			_.ajax({
				'type':'post',
				'data':{'img':_(this).parent().attr('data')},
				'url':'./?type=image&mode=delete',
				'success':function(result){
					if (result['status'] == 1)
					{
						_(_this).parent().remove();
					}else{
						_.ajax_untip(result['status_code']);
					}
				}
			});
		});
		_('.imgs li').hover(function(){
				_(this).find('a').show();
			},
			function(){
				_(this).find('a').hide();
			}
		).click(function(){
			if (_(this).find('.imglist').prop('checked'))
			{
				_(this).find('.imglist').prop('checked',false);
			}else{
				_(this).find('.imglist').prop('checked',true);
			}
		});
		_('.btn_clean').bind('click',function(){
			location.href = './?type=image&mode=clean';
		});
		_('.ck_item').bind('change',function(){
			_('.imglist').prop('checked',_(this).prop('checked'));
		});
		_('.btn_attach').bind('click',function(){
			var str = '';
			_('.imglist').each(function(){
				if (_(this).prop('checked'))
				{
					str += '<p style="text-align:center;"><img src="'+_(this).val()+'" style="max-width:100%;"></p>';
				}
			});
			if (!str)
			{
				_.ajax_untip('<?php _e('No image selected');?>');
				return ;
			}
			var ifr_body = parent.window._('.btn_upload').parent().parent().find('iframe').items();
			ifr_body.contentWindow.document.body.innerHTML = str + ifr_body.contentWindow.document.body.innerHTML;
			parent._('.SweetRice_dialog_close').run('click');
		});
	});
//-->
</script>
</body>
</html>
<?php exit;?>