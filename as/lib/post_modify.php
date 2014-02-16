<?php
/**
 * Entry management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form enctype="multipart/form-data" method="post" action="./?type=post&mode=insert">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl;?>"/>
<input type="hidden" name="createTime" value="<?php echo $row['date'];?>"/>
<input type="hidden" name="views" value="<?php echo $row['views'];?>" >
<input type="hidden" name="id" value="<?php echo $row['id'];?>" >
<fieldset><legend><?php echo NAME;?>:</legend>
<input type="text" name="name" class="input_text" value="<?php echo $row["name"];?>"> * 
<?php
	if($row['sys_name']){
?>
<a href="<?php echo SITE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>"><?php echo PREVIEW?></a>
<a title="<?php echo DELETE_TIP;?>" onClick='if(confirm("<?php echo(DELETE_CONFIRM);?>")) return true; else return false;' href="./?type=post&mode=delete&id=<?php echo $row['id'];?>&one=1"><?php echo DELETE_TIP;?></a> 
<?php echo date('m/d/Y H:i:s',$row['date']);?> <input type="checkbox" name="republish" value="1"/> <?php echo UPDATE;?>?
<?php
	}
?>
</fieldset>
<fieldset><legend><?php echo SLUG;?>:</legend>
<input type="text" name="sys_name" class="input_text" value="<?php echo $row["sys_name"];?>" onchange="var sys_name = this.value.replace(/([^a-z0-9A-Z\-_])/g,'-');sys_name = sys_name.replace(/(^-*)|(-*$)/g,'');this.value = sys_name;">*<?php echo SLUG_POST_TIP;?>
<?php
	if($row['sys_name']){
echo '<div id="permalinks"><p>'.SITE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']).'</p></div>';
	}
?>
</fieldset>
<fieldset><legend><?php echo TITLE;?>:</legend>
<input type="text" name="title" class="input_text" value="<?php echo $row["title"];?>">*<?php echo TITLE_TIP;?>
</fieldset>
<fieldset><legend><?php echo META.' '.SETTING;?></legend>
<ul>
<li><input type="text" name="keyword" class="input_text" value="<?php echo $row["keyword"]?$row['keyword']:KEYWORD;?>" onblur="if (this.value == '') {this.value = '<?php echo KEYWORD;?>';}" onfocus="if (this.value == '<?php echo KEYWORD;?>') {this.value = '';}" > *<?php echo KEYWORD_TIP;?></li>
<li><input type="text" name="description" class="input_text" value="<?php echo $row["description"]?$row["description"]:DESCRIPTION;?>" onblur="if (this.value == '') {this.value = '<?php echo DESCRIPTION;?>';}" onfocus="if (this.value == '<?php echo DESCRIPTION;?>') {this.value = '';}" > *<?php echo DESCRIPTION_TIP;?>
</li>
</ul>
</fieldset>
<fieldset><legend><?php echo TAG;?>:</legend><input type="text" name="tags" class="input_text" value="<?php echo htmlspecialchars($row["tags"],ENT_QUOTES);?>"> *<?php echo TAG_TIP;?>
</fieldset>
<fieldset><legend><?php echo BODY;?>: </legend> 
<div class="mg5"><label class="editor_toggle" tid="info" data="visual"><?php echo VISUAL;?></label>
<label class="editor_toggle current_label" data="html" tid="info"><?php echo HTML;?></a></label></div>
<?php include('lib/tinymce.php');?>
<textarea id="info" name="info">
<?php echo htmlspecialchars($row['body']);?>
</textarea>
</fieldset>
<fieldset><legend><?php echo CATEGORY;?>:</legend>
<select name="category">
<option value="0"> -- <?php echo UNCATEGORY;?> -- </option>
<?php
$s_category[$row['category']] = 'selected';
	foreach($subCategory as $val){
		$_prefix = '';
		for($i=0; $i<$val['level']; $i++){
			$_prefix .= '-- ';
		}
		echo '<option value="'.$val['id'].'" '.$s_category[$val['id']].'>'.$_prefix.$categories[$val['id']]['name'].'</option>';
	}
?>
</select>
</fieldset>
<fieldset><legend><?php echo TEMPLATE;?>:<?php echo $row['template'];?></legend>
<select name="template">
<?php
	if(!in_array($row['template'],$template)&&$row['template']){
		$template[$row['template']] = $row['template'];
	}
	foreach($template as $key=>$val){
		$s = '';
		if($key==$row['template']){
			$s = 'selected';
		}
		echo '<option value="'.$key.'" '.$s.'>'.$val.'</option>';
	}
?>
</select>
</fieldset>
<fieldset><legend><?php echo OPTION_POST;?>:</legend>
<?php echo PUBLISH;?> <input type="checkbox" name="in_blog" value="1" <?php echo $row['in_blog']?'checked':'';?> >
<?php echo ALLOW_COMMENT;?> <input type="checkbox" name="allow_comment" value="1" <?php echo $row['allow_comment']?'checked':'';?> >
</fieldset>
<fieldset><legend><?php echo ATTACHMENT;?>:</legend>
<?php
$no = 0;
if(count($att_rows)){
	foreach($att_rows AS $att_row){
		$no +=1;
		$is_local = false;
		if(substr($att_row['file_name'],0,strlen(SITE_URL))==SITE_URL){
			$is_local = true;
		}
?>
<div class="att_list">
<li id="f_<?php echo $no;?>">
<input type="hidden" name="attid_<?php echo $no;?>" value="<?php echo $att_row['id'];?>"/>
<input type="hidden" name="atttimes_<?php echo $no;?>" value="<?php echo $att_row['downloads'];?>"/>
<input type="hidden" name="attdate_<?php echo $no;?>" value="<?php echo $att_row['date'];?>"/>
No.<?php echo $no;?> <?php echo FILENAME;?>:<input type="text" id="att_<?php echo $no;?>" name="att_<?php echo $no;?>" value="<?php echo $att_row['file_name'];?>" class="input_text"/> <input type="button" value="<?php echo REPLACE_TIP;?>" onclick="replaceAtt(<?php echo $no;?>,event);">
 <input type="button" value="<?php echo REMOVE_FILE;?>" onclick="delfile(<?php echo $no;?>,event);"> <?php echo UPLOAD_TIME;?>:<?php echo date('m/d/y H:i:s',$att_row['date']);?> <?php echo DOWNLOAD_TIMES;?>:<?php echo $att_row['downloads'];?> <?php echo $is_local?(file_exists(str_replace(SITE_URL,ROOT_DIR,$att_row['file_name']))?'<span class="file_exists">File exists</span>':'<span class="file_noexists">Does not exists</span'):'Remote File';?> 
</li>
</div>
<?php
	}
}
?>
<input type="hidden" id="no" name="no" value="<?php echo $no;?>"  >
<div id="muti_files"></div>
<input type="button" value="<?php echo ADD_FILE;?>"  class="att_add">
</fieldset>
<script type="text/javascript">
	<!--
	var attNo = <?php echo $no;?>;
	var currentNo = 0;
	var REMOVE_FILE = '<?php echo REMOVE_FILE;?>';
	var REPLACE_TIP = '<?php echo REPLACE_TIP;?>';
	_().ready(function(){
		_('.att_add').bind('click',function(event){
			addfile(event);
		});
	});
	//-->
	</script>
<?php 
	$cftype = 'post';
	include('lib/custom_field.php');
?>
<p><input type="submit" class="input_submit" name="done" value="<?php echo DONE;?>">
<?php
	if($row['sys_name']){
?><input type="submit" value="<?php echo UPDATE;?>" name="update" class="input_submit">
<?php
	}
?><input type="button" value="<?php echo BACK;?>" onclick="location.href='./?type=post';" class="input_submit"></p>
</form>
</div>
<div class="div_clear"></div>
</div>
<?php
	include("lib/foot.php");	
	exit();
?>