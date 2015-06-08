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
}
body{
	font-family:"Microsoft YaHei","lucida grande",tahoma,verdana,arial,sans-serif;
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
table{
	width:100%;
}
.file_table{
	height:370px;
	overflow:auto;
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
.point{
	cursor: pointer;
}
#deleteTip{
	background-color:#FFFBCC;
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

#remote{width:260px;}
#upload{width:250px;overflow:hidden;}
.page_limit{width:30px;}
.imgs{max-height:450px;}
.imgs ul{margin: 10px 0px; padding: 0px;}
.imgs li{width:18%;float:left;display:inline;list-style-type:none;height:100px;margin-bottom:10px;position:relative;box-shadow: 2px 2px 5px #ccc;margin:1%;text-align:center;padding: 5px 0px;cursor:pointer;}
.imgs li div{width:100%;word-wrap: break-word; word-break: break-all;height:100%;overflow:hidden;}
.imgs li img{max-width:98%;max-height:98%;border:1px solid #d8d8d8;}
.imgs li input[type=checkbox]{position:absolute;right:5px;bottom:5px;}
.img_delete{position:absolute;left:5px;bottom:5px;width:16px;height:16px;line-height:16px;border:1px solid #ccc;text-decoration: none;color:#ccc;background-color: #fff;cursor:pointer;display:none;}
.clear{clear:both;}
.imgs li div a{display:block;width:100%;height:100%;}
.form_split{float:left;line-height:30px;display:inline;margin:2px 10px;height:30px;}
.selected_item{box-shadow: 2px 2px 5px #690 !important;}
@media (max-width: 640px){
	.form_split{float:none;margin-left:5px;display:block;height:auto;}
	.imgs li{width:48%;}
	.new_dir{width:150px;}
	.mw120{max-width:120px;}
	.mw140{max-width:140px;}
	.mw150{max-width:150px;}
	.mw160{max-width:160px;}
	.mw170{max-width:170px;}
	.mw180{max-width:180px;}
}
</style>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<script type="text/javascript" src="js/function.js"></script>
</head>
<body>
<div class="form_split">
<span class="folder"></span> <a href="./?type=media<?php echo $parent?'&dir='.$parent:'';?>&referrer=<?php echo $referrer;?>"><?php _e('Parent');?></a>
</div>
<div class="form_split">
<form method="get" action="./">
<input type="hidden" name="type" value="media" />
<input type="hidden" name="referrer" value="<?php echo $referrer;?>" />
<?php _e('Search');?> <a href="./?type=media&referrer=<?php echo $referrer;?>&dir=<?php echo $open_dir;?>"><?php echo $open_dir;?></a>:<input type="hidden" name="dir" value="<?php echo $open_dir;?>"/>
	<input type="text" name="keyword" value="<?php echo $keyword;?>" placeholder="<?php _e('Keywords');?>" class="mw160"/> <input type="submit" value="<?php _e('Search');?>" class="input_submit"/>
</form>
</div>
<div class="clear"></div>
<span id="deleteTip"></span>
<div class="imgs">
<ul>
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
<li id="tr_<?php echo $no;?>" class="<?php echo $files[$i]['type'] == 'dir' ? 'isdir':'isfile';?>">
<?php
	if($files[$i]['type']=='dir'){
?>
<div>
<a href="./?type=media&referrer=<?php echo $referrer;?>&dir=<?php echo $files[$i]['link'].'/';?>"><span title="<?php echo $files[$i]['name'];?>"><?php echo $files[$i]['name'];?></span></a></div>
<?php
	}else{
		$tmp_prefix = '';
		$tmp_prefix = explode('.',$files[$i]['name']);
		$tmp_prefix = '.'.end($tmp_prefix);
		if(mb_strlen($files[$i]['name'],'UTF-8') > 36){
			if(count($tmp_prefix)){
				$files[$i]['name'] = mb_substr($files[$i]['name'],0,32,'UTF-8').'...'.$tmp_prefix;
			}
		}
		if(in_array(strtolower($tmp_prefix),array('.jpg','.png','.gif','.jpeg','.bmp'))){
?>
<img src="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" class="<?php echo $referrer == 'attachment'?'attlist':'medialist';?>" link="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" title="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" mtype="<?php echo $files[$i]['type'];?>">
<?php
		}else{
?><div>
<a href="javascript:void(0);" class="<?php echo $referrer == 'attachment'?'attlist':'medialist';?>" link="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" title="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>" mtype="<?php echo $files[$i]['type'];?>"><?php echo $files[$i]['name'];?></a></div>
<?php
		}
	}
?>
<a title="<?php _e('Delete');?>" class="action_delete dellist img_delete" link="<?php echo $files[$i]['link']?>" no="<?php echo $no;?>"><?php _e('Delete');?></a></li>

<?php
	}
}
?>
</ul>
</div>
<div class="clear"></div>
<?php echo $pager['list_put'];?>
<div class="form_split">
<form method="post" action="./?type=media&mode=upload" enctype="multipart/form-data" >
<div class="form_split">
<?php _e('Upload');?> : <input type="hidden" name="dir_name" value="<?php echo str_replace(MEDIA_DIR,'',$open_dir);?>"/>
	<input type="file" name="upload[]" id="upload" class="mw180" title="<?php echo _t('Max upload file size'),':',UPLOAD_MAX_FILESIZE;?>" multiple></div>
	<div class="form_split"><?php _e('Extract zip archive?');?> <input type="checkbox" name="unzip" value="1" /> <input type="submit" value="<?php _e('Done');?>" class="input_submit"/></div>
</form>
</div>
<div class="form_split">
<form method="post" action="./?type=media&mode=mkdir">
<input type="hidden" name="referrer" value="<?php echo $referrer;?>" />
<?php _e('New Directory');?> : <input type="hidden" name="parent_dir" value="<?php echo str_replace(MEDIA_DIR,'',$open_dir);?>"/>
	<input type="text" name="new_dir" class="new_dir mw120"/> <input type="submit" value="<?php _e('Done');?>" class="input_submit"/>
</form>
</div>
<div class="clear"></div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.imgs li').hover(function(){
				_(this).css({'background-color':'#fafafa'}).find('.img_delete').show();
			},
			function(){
				_(this).css({'background-color':'#fff'}).find('.img_delete').hide();
			}
		).click(function(){
			_('.imgs li').removeClass('selected_item');
			_(this).addClass('selected_item');
		});
		_('.isfile').bind('click',function(){
			_('.isfile').removeClass('selected_item');
			_(this).addClass('selected_item');
		});
		_('.attlist').bind('click',function(){
			parent.attach_media.val(_(this).attr('link'));
			_('#remote').val(_(this).attr('link'));
		}).bind('dblclick',function(){
			parent.attach_media.val(_(this).attr('link'));
			parent._('#SweetRice_dialog_media').find('.SweetRice_dialog_close').run('click');
		});
		_('#remote').bind('change',function(){
			parent.attach_media.val(_(this).val());
			_('#preview').html('<img src="'+_(this).val()+'">');
		});
		_('.dellist').bind('click',function(){
			if(confirm('<?php _e('Are you sure delete it?');?>')) {
				deleteAction('media',_(this).attr('link'),_(this).attr('no'));
				var _this = this;
				_.ajax({
					'type':'post',
					'data':{'file':_(this).attr('link')},
					'url':'./?type=media&mode=delete',
					'success':function(result){
						if (result['status_code'])
						{
							_.ajax_untip(result['status_code']);
						}
						if (result['status'] == 1)
						{
							_(_this).parent().remove();
						}
					}
				});
			}else{ 
				return false;
			}
		});

		_('.medialist').bind('click',function(){
			parent.document.getElementById('tmp_media').value = _(this).attr('link');
		});
	});
//-->
</script>
</body>
</html>