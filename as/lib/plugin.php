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
<thead><tr><td><a href="javascript:void(0);" class="btn_sort" data="name"><?php _e('Name');?></a></td><td><a href="javascript:void(0);" class="btn_sort" data="version" stt="number"><?php _e('Version');?></a></td><td><?php _e('Plugin Description');?></td><td class="td_admin"><?php _e('Admin');?></td></tr></thead>
<tbody>
<?php
$no = 0;
	foreach(pluginList() as $key=>$val){
		$no += 1;
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname = 'tr_sigle';
		}
		if($val['installed']){
			$admin_tip = '<a href="javascript:void(0);" url="./?type=plugins&mode=deinstall&plugin='.$key.'" class="btn_plugin" mode="deinstall">'._t('Deinstall').'</a>';
			$plugin_link = './?type=plugin&plugin='.$key;
		}else{
			$admin_tip = '<a href="javascript:void(0);" url="./?type=plugins&mode=install&plugin='.$key.'" class="btn_plugin">'._t('Install').'</a>';
			$plugin_link = 'javascript:void(0);" class="noinstall"';
		}
?>
<tr class="<?php echo $classname;?>" id="tr_<?php echo $no;?>"><td><span class="sortNo" id="sortNo_<?php echo $no;?>"><?php echo $no;?></span><a href="<?php echo $plugin_link;?>"><span id="name_<?php echo $no;?>"><?php echo $val['name'];?></span></a></td><td><span id="version_<?php echo $no;?>"><?php echo $val['version'];?></span></td>
<td>
<div style="margin:10px;"><?php echo is_array($val['description'])?($val['description'][basename($global_setting['lang'],'.php')]?$val['description'][basename($global_setting['lang'],'.php')]:$val['description']['en-us']):$val['description'];?></div>
<p><?php _e('Author');?>:<?php echo $val['author'];?> | <?php _e('Contact');?>:<a href="mailto:<?php echo $val['contact'];?>"><?php echo $val['contact'];?></a> | <?php _e('Home Page');?>:<a href="<?php echo $val['home_page'];?>"><?php echo $val['home_page'];?></a></p>
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
		_('.btn_plugin').bind('click',function(){
			if (_(this).attr('mode') == 'deinstall')
			{
				if (!confirm('<?php _e('Are you sure delete it?');?>')){
					return ;
				}
			}
			_.ajax({
				type:'GET',
				url:_(this).attr('url'),
				success:function(result){
					_.dialog({
						'content':result.status_code,
						'close':function(){
							if (result.status == 1){
								window.location.reload();
							}
						}
					});
				}
			});
		});
		_('.noinstall').bind('click',function(){
			alert('<?php _e('Plugin must be install first.');?>');
		});
	});
//-->
</script>