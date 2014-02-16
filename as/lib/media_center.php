<?php
/**
 * Dashborad media management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.6.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="media_center" />
<?php echo SEARCH;?> <a href="./?type=media_center&dir=<?php echo $open_dir;?>"><?php echo $open_dir;?></a> <input type="hidden" name="dir" value="<?php echo $open_dir;?>"/>
	<input type="text" name="keyword" value="<?php echo $keyword;?>" /> <input type="submit" value="<?php echo SEARCH;?>" class="input_submit"/>
</form>
<p><span class="folder" ></span><a href="./?type=media_center<?php echo $parent?'&dir='.$parent:'';?>"><?php echo PARENT;?></a></p>
<span id="deleteTip"></span>
<form method="post" action="./?type=media_center&mode=bulk">
<div id="tbl">
<table>
<thead><tr><td><input type="checkbox" id="checkall"/> <a href="javascript:void(0);" class="btn_sort" data="name"><?php echo NAME;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="filetype"><?php echo FILE_TYPE;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="date"><?php echo DATE_TIP;?></a></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
$no = 0;
for($i=$pager['page_start']; $i<$pager['page_start']+$page_limit; $i++){
	if($files[$i]){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
?>
<tr class="<?php echo $classname?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span>
<?php
	if($files[$i]['type']=='dir'){
?>
<span class="folder" ></span><a href="./?type=media_center&dir=<?php echo $files[$i]['link'].'/';?>"><span id="name_<?php echo $no;?>"><?php echo $files[$i]['name'];?></span></a>
<?php
	}else{
?>
<span class="article" ></span><input type="checkbox" name="plist[]" class="ck_item" value="<?php echo $files[$i]['link'];?>"/> 
<?php
	if(substr($files[$i]['type'],0,5)=='image'){
?>
<a href="javascript:void(0);" class="attimage" data="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>"><span id="name_<?php echo $no;?>"><?php echo $files[$i]['name'];?></span></a>
<?php
	}else{
?>
<a href="<?php echo BASE_URL.substr(MEDIA_DIR.$files[$i]['link'],strlen(SITE_HOME));?>"><span id="name_<?php echo $no;?>"><?php echo $files[$i]['name'];?></span></a>
<?php
	}	
	}	
?>
</td>

<td><span id="filetype_<?php echo $no;?>"><?php echo $files[$i]['type']=='dir'?DIRECTORY:$files[$i]['type'];?></span></td><td>
<span id="date_<?php echo $no;?>" class="sortNo"><?php echo $files[$i]['date'];?></span><?php echo date(DATE_FORMAT,$files[$i]['date']);?></td>
<td class="td_admin"><span id="action_<?php echo $no;?>"></span>
<a title="<?php echo DELETE_TIP;?>" class="action_delete" onClick="if(confirm('<?php echo DELETE_CONFIRM;?>')) deleteAction('media_center','<?php echo $files[$i]['link']?>',<?php echo $no;?>); else return false;" href="javascript:void(0);"><?php echo DELETE_TIP;?></a></td></tr>
<?php
	}
}
?>
</table>
</div>
<input type="submit" value=" <?php echo BULK.' '.DELETE_TIP;?>" onclick='var no=totalChecked();if(no>0){if(confirm("<?php echo DELETE_CONFIRM;?>")){return true;}else{return false;}}else{alert("<?php echo NO_RECORD_SELECTED;?>.");return false;}'></form>
<div align="center"><?php echo $pager['list_put'];?></div>
<div id="upload_form">
<form method="post" action="./?type=media_center&mode=mkdir">
<?php echo NEW_DIRECTORY;?> : <input type="hidden" name="parent_dir" value="<?php echo $open_dir;?>"/>
	<input type="text" name="new_dir" /> <input type="submit" value="<?php echo DONE;?>"/>
</form>
<form method="post" action="./?type=media&mode=upload" enctype="multipart/form-data" >
<?php echo UPLOAD;?> : <input type="hidden" name="dir_name" value="<?php echo str_replace(MEDIA_DIR,'',$open_dir);?>"/>
	<input type="file" name="upload"> <input type="submit" value="<?php echo DONE;?>"/> * <?php echo MAX_UPLOAD_FILE_TIP,':',UPLOAD_MAX_FILESIZE;?>
</form>
</div>
</div>

<div class="div_clear"></div>
</div>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript" src="js/media_center.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		bind_checkall('#checkall','.ck_item');
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
			_('.attimage').unbind('click').bind('click',function(){
				if (!_(this).attr('data')){
					return false;
				}	
				_.dialog({'content':'<div style="width:22px;margin:auto;"><img src="../images/loading.gif"></div>','name':'media','width':300,'layer':true});
				psrc = _(this).attr('data');
				image.src = psrc;
				return false;
			});
		});
	});
</script>
<?php
	include("lib/foot.php");	
	exit();
?>