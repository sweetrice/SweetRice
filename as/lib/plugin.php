<?php
/**
 * Plugins management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<div id="tbl">
<table>
<thead><tr><td><a href="javascript:void(0);" class="btn_sort" data="name"><?php echo NAME;?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="version" stt="number"><?php echo VERSION;?></a></td><td><?php echo PLUGIN_DESCRIPTION;?></td><td class="td_admin"><?php echo ADMIN;?></td></tr></thead>
<tbody>
<?php
$no = 0;
	foreach(pluginList() as $key=>$val){
		$no += 1;
		if($classname=='tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
		if($val['installed']){
			$admin_tip = '<a href="./?type=plugins&mode=deinstall&plugin='.$key.'" onClick=\'if(confirm("'.DELETE_CONFIRM.'")) return; else return false;\'>'.DEINSTALL.'</a>';
			$plugin_link = './?type=plugin&plugin='.$key;
		}else{
			$admin_tip = '<a href="./?type=plugins&mode=install&plugin='.$key.'">'.INSTALL.'</a>';
			$plugin_link = 'javascript:void(0);" onclick="alert(\'Plugin must be install first.\');';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><a href="<?php echo $plugin_link;?>"><span id="name_<?php echo $no;?>"><?php echo $val['name'];?></span></a></td><td><span id="version_<?php echo $no;?>"><?php echo $val['version'];?></span></td>
<td>
<div style="margin:10px;"><?php echo $val['description'];?></div>
<p><?php echo AUTHOR;?>:<?php echo $val['author'];?> | <?php echo CONTACT;?>:<a href="mailto:<?php echo $val['contact'];?>"><?php echo $val['contact'];?></a> | <?php echo HOME_PAGE;?>:<a href="<?php echo $val['home_page'];?>"><?php echo $val['home_page'];?></a></p>
</td>
<td><?php echo $admin_tip;?></td></tr>
<?php
	}
?>
</tbody>
</table>
</div>
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