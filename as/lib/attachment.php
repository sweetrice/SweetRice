<?php
/**
 * Attachment management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="get" action="./">
<input type="hidden" name="type" value="attachment"/>
	<input type="text" name="search" value="<?php echo $search;?>"/> <input type="submit" value="<?php echo SEARCH;?>" class="input_submit"/>
</form>
<div id="tbl">
<table>
<thead><tr><td><a href="javascript:void(0);"  class="btn_sort" data="filename"><?php echo FILENAME;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="filesize"><?php echo FILE_SIZE;?></a></td><td><a href="javascript:void(0);"  class="btn_sort" data="downloadtimes" stt="0"><?php echo DOWNLOAD_TIMES;?></a></td><td><a href="javascript:void(0);"  class="btn_sort" data="date"><?php echo UPLOAD_TIME;?></a></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
$no = 0;
	foreach($rows as $row){
		$no +=1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname = 'tr_sigle';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><a href="<?php echo $row['file_name'];?>"><span id="filename_<?php echo $no;?>"><?php echo $row['file_name'];?></span></a></td><td><span id="filesize_<?php echo $no;?>"><?php echo filesize2print($row['file_name']);?></span></td><td><span id="downloadtimes_<?php echo $no;?>"><?php echo $row['downloads'];?></span></td><td><span id="date_<?php echo $no;?>" class="sortNo"><?php echo $row['date'];?></span><?php echo date(DATE_FORMAT,$row['date']);?></td><td>
<a title="<?php echo MODIFY;?>" class="action_modify" href="./?type=post&mode=modify&id=<?php echo $row['post_id'];?>"><?php echo MODIFY;?></a>
</td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
<?php echo $pager['list_put'];?>
<script type="text/javascript" src="js/BodySort.js"></script>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_sort').bind('click',function(){
			sortBy(this,'#tbl');
		});
	});
//-->
</script>