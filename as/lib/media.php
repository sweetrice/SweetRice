<?php
/**
 * Dashborad media upload template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.6.4
 */
 defined('VALID_INCLUDE') or die();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('Dashboard');?></title>
<style>
*{
	margin:0;
	padding:0;
	font-size:11px;
}
body{
	padding:5px;
}
img {
	border: 0px;
}
a:link, a:visited{
	color: #000;
	text-decoration: none;
}
a:hover{
	color: #000;
	text-decoration: underline;
}
#upload_form{
	padding:2px 0px;
}
table{
	width:100%;
}
.file_table{
	height:370px;
	overflow:auto;
}
input[type=button], input[type=submit] {
	padding: 2px 8px !important;
	border: 1px solid #bbb;
	border-radius: 5px;
	-moz-box-sizing: content-box;
	-webkit-box-sizing: content-box;
	-khtml-box-sizing: content-box;
	box-sizing: content-box;
	cursor:pointer;
	color: #ffffff;
	background-color:#669900;
	text-shadow:0 -1px 0 rgba(0, 0, 0, 0.4);
}
input[type=button]:hover, input[type=submit]:hover {
	border-color: #669966;
}
input[type=text]:focus,input[type=checkbox]:focus,input[type=password]:focus,select:focus,textarea:focus{
-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(125, 125, 125, 0.6);
box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(125, 125, 125, 0.6);
}
input[type=text],input[type=password],input[type=file]{
	border:1px #999999 solid;
	border-top:1px #669966 solid;
	background-color:#F2F2F2;
	padding:3px;
	border-radius:3px;
}
.point{
	cursor: pointer;
}
#deleteTip{
	background-color:#FFFBCC;
}
.folder{
	background:url("../images/action_icon.png") no-repeat scroll -59px -2px transparent;
	float:left;
	height:20px;
	overflow:hidden;
	width:20px;
}
.article{
	background:url("../images/action_icon.png") no-repeat scroll -40px -2px transparent;
	float:left;
	height:20px;
	overflow:hidden;
	width:20px;
}
.action_delete{
	background:url("../images/action_icon.png") no-repeat scroll -18px -2px transparent;
	float:left;
	height:20px;
	overflow:hidden;
	text-indent:-9999px;
	width:20px;
	cursor: pointer;
}
#file_list{width:49%;float:left;display:inline;}
.preview{width:49%;float:right;display:inline;}
.preview input{display:inline;}
#preview {height:450px;line-height:420px;vertical-align:middle;text-align:center;}
#preview img{margin:auto;
max-width:380px;
	width:expression(this.width > 380 ? 380: true);
max-height:420px;
	height:expression(this.height > 420 ? 420: true);
	vertical-align:middle;
	}
.clear{clear:both;height:0px;line-height:0px;}
#upload_form form{margin:3px 0px;}
#remote{width:260px;}
#upload{width:250px;overflow:hidden;}
.page_limit{width:30px;}
</style>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/public.js"></script>
<script type="text/javascript" src="js/function.js"></script>
</head>
<body>
<div id="file_list">

<form method="get" action="./">
<input type="hidden" name="type" value="media" />
<input type="hidden" name="referrer" value="<?php echo $referrer;?>" />
<?php _e('Search');?> <a href="./?type=media&referrer=<?php echo $referrer;?>&dir=<?php echo $open_dir;?>"><?php echo $open_dir;?></a>:<input type="hidden" name="dir" value="<?php echo $open_dir;?>"/>
	<input type="text" name="keyword" value="<?php echo $keyword;?>" placeholder="<?php _e('Keywords');?>"/> <input type="submit" value="<?php _e('Search');?>" class="input_submit"/>
</form>
<span id="deleteTip"></span>
<div class="file_table">
<table cellspacing="1" cellpadding="1">
<tr><td></td><td>
<span class="folder"></span> <a href="./?type=media&referrer=<?php echo $referrer;?>"><?php _e('Parent');?></a></td><td><?php _e('Media Center');?></td><td></td></tr>
<?php
$no = 0;
for($i=$pager['page_start']; $i<$pager['page_start']+$page_limit; $i++){
	if($files[$i]){
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
	 $no +=1;
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>" ><td><?php echo $no;?></td><td>
<?php
	if($files[$i]['type']=='dir'){
?>
<span class="folder"></span> <a href="./?type=media&referrer=<?php echo $referrer;?>&dir=<?php echo $files[$i]['link'].'/';?>"><?php echo $files[$i]['name'];?></a>
<?php
	}else{
		$tmp_prefix = '';
		if(mb_strlen($files[$i]['name'],'UTF-8') > 36){
			$tmp_prefix = explode('.',$files[$i]['name']);
			if(count($tmp_prefix)){
				$tmp_prefix = '.'.end($tmp_prefix);
				$files[$i]['name'] = mb_substr($files[$i]['name'],0,32,'UTF-8').'...'.$tmp_prefix;
			}
		}
		switch($referrer){
			case 'attachment':
?>
<span class="article" ></span><a href="javascript:void(0);" link="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" title="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" mtype="<?php echo $files[$i]['type'];?>" class="attlist"><?php echo $files[$i]['name'];?></a>
<?php
			break;
			default:
?>
<span class="article" ></span><a href="javascript:void(0);" link="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" title="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" class="medialist" mtype="<?php echo $files[$i]['type'];?>"><?php echo $files[$i]['name'];?></a>
<?php
			break;
		}
	}
?>
</td>
<td>
<?php echo $files[$i]['date'];?></td>
<td><span id="action_<?php echo $no;?>"></span>
<a title="<?php _e('Delete');?>" class="action_delete dellist" link="<?php echo $files[$i]['link']?>" no="<?php echo $no;?>"><?php _e('Delete');?></a>
</td></tr>
<?php
	}
}
?>
</table>
</div>
<div style="text-align:center;"><?php echo $pager['list_put'];?></div>
<div id="upload_form">
<form method="post" action="./?type=media&mode=mkdir">
<input type="hidden" name="referrer" value="<?php echo $referrer;?>" />
<?php _e('New Directory');?> : <input type="hidden" name="parent_dir" value="<?php echo str_replace(MEDIA_DIR,'',$open_dir);?>"/>
	<input type="text" name="new_dir" /> <input type="submit" value="<?php _e('Done');?>" class="input_submit"/>
</form>
<form method="post" action="./?type=media&mode=upload" enctype="multipart/form-data" >
<?php _e('Upload');?> : <input type="hidden" name="dir_name" value="<?php echo str_replace(MEDIA_DIR,'',$open_dir);?>"/>
	<input type="file" name="upload[]" id="upload" title="<?php echo _t('Max upload file size'),':',UPLOAD_MAX_FILESIZE;?>" multiple> <input type="submit" value="<?php _e('Done');?>" class="input_submit"/>
</form>
</div>
</div>
<div class="preview">
<div id="preview"></div>
<?php
	if($referrer == 'attachment'){
	?>
	<input type="text" id="remote"> <input type="button" value="<?php _e('Attach File');?>" class="aa_btn">
	<?php }?>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.aa_btn').bind('click',function(){
			if (parent.attach_media.val()){
				parent._('#SweetRice_dialog_media').find('.SweetRice_dialog_close').run('click');
			}
		});
		_('.attlist').bind('click',function(){
			parent.attach_media.val(_(this).attr('link'));
			_('#remote').val(_(this).attr('link'));
			preview_file(this);
		});
		_('#remote').bind('change',function(){
			parent.attach_media.val(_(this).val());
			_('#preview').html('<img src="'+_(this).val()+'">');
		});
		_('.dellist').bind('click',function(){
			if(confirm('<?php _e('Are you sure delete it?');?>')) {
				deleteAction('media',_(this).attr('link'),_(this).attr('no'));
			}else{ 
				return false;
			}
		});

		_('.medialist').bind('click',function(){
			parent.document.getElementById('tmp_media').value = _(this).attr('link');
			preview_file(this);
		});
	});
	function preview_file(obj){
		if (_(obj).attr('mtype').substring(0,6) == 'image/')
		{
			_('#preview').html('<img src="'+_(obj).attr('link')+'">');
		}else if(_(obj).attr('mtype').substring(0,6) == 'video/'){
			_('#preview').html('<video width="300" height="200" controls="controls"><source src="'+_(obj).attr('link')+'" type="'+_(obj).attr('mtype')+'" /></video>');
		}else if(_(obj).attr('mtype').substring(0,6) == 'audio/'){
			_('#preview').html('<audio width="300" height="20" controls="controls"><source src="'+_(obj).attr('link')+'" type="'+_(obj).attr('mtype')+'" /></audio>');
		}else{
			_('#preview').html('<?php _e('Can not preview this file');?>');
		}
	}
//-->
</script>
</body>
</html>