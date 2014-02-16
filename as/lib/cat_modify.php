<?php
/**
 * Category management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="POST" action="./?type=category&mode=insert">
<input type="hidden" name="id" value="<?php echo $row["id"];?>" />
<fieldset><legend><?php echo CAT_NAME;?></legend>
<input type="text" name="name" class="input_text" value="<?php echo $row["name"];?>" > *<?php echo CAT_NAME_TIPS;?>
</fieldset>
<fieldset><legend><?php echo SLUG;?></legend>
<input type="text" name="link" class="input_text" value="<?php echo $row["link"];?>" onchange="var sys_name = this.value.replace(/([^a-z0-9A-Z\-_])/g,'-');sys_name = sys_name.replace(/(^-*)|(-*$)/g,'');this.value = sys_name;"> *<?php echo SLUG_TIPS;?>
</fieldset>
<fieldset><legend><?php echo TITLE;?></legend>
<input type="text" name="title" class="input_text" value="<?php echo $row["title"];?>"> *<?php echo TITLE_TIP;?>
</fieldset>
<fieldset><legend><?php echo META.' '.SETTING;?></legend>
<ul>
<li><input type="text" name="keyword" class="input_text" value="<?php echo $row["keyword"]?$row['keyword']:KEYWORD;?>" onblur="if (this.value == '') {this.value = '<?php echo KEYWORD;?>';}" onfocus="if (this.value == '<?php echo KEYWORD;?>') {this.value = '';}"> *<?php echo KEYWORD_TIP;?></li>
<li><input type="text" name="description" class="input_text" value="<?php echo $row["description"]?$row["description"]:DESCRIPTION;?>" onblur="if (this.value == '') {this.value = '<?php echo DESCRIPTION;?>';}" onfocus="if (this.value == '<?php echo DESCRIPTION;?>') {this.value = '';}" > *<?php echo DESCRIPTION_TIP;?></li>
</ul>
</fieldset>
<fieldset><legend><?php echo TOP_WORD;?></legend>
<input type="text" name="sort_word" value="<?php echo $row['sort_word'];?>" class="input_text"> *<?php echo TOP_WORD_TIPS;?>
</fieldset>
<fieldset><legend><?php echo PARENT;?></legend>
<select name="parent_id">
<option value="0" <?php echo $s_parent[0];?>> -- <?php echo PARENT;?> -- </option>
<?php
	foreach($subCategory AS $val){
		$_prefix = '';
		for($i=0; $i<$val['level']; $i++){
			$_prefix .= '-- ';
		}
?>
<option value="<?php echo $val['id'];?>" <?php echo $s_parent[$val['id']];?>><?php echo $_prefix.$categories[$val['id']]['name'];?></option>
<?php
	}
?>
</select>
</fieldset>
<fieldset><legend><?php echo TEMPLATE;?></legend>
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
</select></fieldset>
<?php include('lib/tinymce.php');?>
<?php 
	$cftype = 'category';
	include('lib/custom_field.php');
?>
<input type="submit" class="input_submit" value="<?php echo DONE;?>" name="done">
<?php
	if($row['link']){
?><input type="submit" value="<?php echo UPDATE;?>" name="update" class="input_submit">
<?php
	}	
?><input type="button" value="<?php echo BACK;?>" onclick="location.href='./?type=category';" class="input_submit">
</form>
</table>