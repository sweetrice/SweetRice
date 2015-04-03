<?php
/**
 * App form data list template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
 defined('VALID_INCLUDE') or die();
?>
<style>
.form_data dl{clear:both;}
.form_data dl dt{float:left;width:20%;display:inline;border:1px solid #690;margin:5px;padding:5px;border-radius:5px;}
.form_data dl dd{float:left;width:70%;display:inline;border:1px solid #c8c8c8;margin:5px;padding:5px;border-radius:5px;}
.form_data dl{display:none;}
.toggle_data{text-align:center;cursor:pointer;}
</style>
<select id="form">
<option value="0"></option>
<?php foreach($forms as $form):?>
	<option value="<?php echo $form['id'];?>" <?php echo $form_id == $form['id'] ? 'selected':'';?>><?php echo $form['name'];?></option>
<?php endforeach;?>
</select>
<?php echo $data['pager']['list_put'];?>
<form method="post" id="bulk_form" action="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'form_data','mode'=>'bulk'));?>">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl;?>"/>
<table>
<thead>
	<tr><td><input type="checkbox" class="checkall"/></td><td><?php _e('Form Name');?></td><td><?php _e('Data');?></td><td><?php _e('Date');?></td><td style="width:30px;"><?php _e('Admin');?></td></tr>
</thead>
<tbody>
<?php
	foreach($data['rows'] AS $key => $row ){
		if($classname == 'tr_sigle'){
			$classname = 'tr_double';
		}else{
			$classname='tr_sigle';
		}
		$fields = unserialize($row['fields']);
		$form_data = unserialize($row['data']);
?>
<tr class="<?php echo $classname;?>"><td><input type="checkbox" name="plist[]" value="<?php echo $row['id'];?>" class="ck_item"/></td><td><a href="<?php echo BASE_URL.pluginHookUrl(THIS_APP,array('app_mode'=>'form','id'=>$row['form_id']))?>" target="_blank"><?php echo $row['name'];?></a></td><td class="form_data">
<div class="toggle_data">---</div>
<?php foreach($fields as $val):
echo '<dl '.($key == 0 ?'style="display:block;"':'').'><dt>'.$val['tip'].'</dt><dd>';
if($val['type'] == 'file'){
?>
<a href="<?php echo BASE_URL.'_plugin/app/data/form/'.$form_data[$val['name']];?>" target ="_blank"><?php echo $form_data[$val['name']];?></a>
<?php
}elseif($val['type'] == 'multi_file'){
	foreach($form_data[$val['name']] as $mfile){
?>
<a href="<?php echo BASE_URL.'_plugin/app/data/form/'.$mfile;?>" target ="_blank"><?php echo $mfile;?></a>
<?php
	}
}elseif($val['type'] == 'password'){
	echo '******';
}else{
	echo nl2br($form_data[$val['name']]);
}
echo '</dd></dl>';
endforeach;?></td><td><?php echo date('M d Y H:i',$row['date']);?></td><td>
<a title="<?php _e('Delete');?>" class="action_delete" href="javascript:void(0);"><?php _e('Delete');?></a>
</td></tr>
<?php
	}
?>
</tbody>
</table>
<input type="submit" value="<?php _e('Bulk Delete');?>" class="btn_submit">
</form>
<?php echo $data['pager']['list_put'];?>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.toggle_data').bind('click',function(){
			_(this).parent().find('dl').toggle();
		});
		_('#form').bind('change',function(){
			location.href  = '<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'form_data'));?>&form_id=' + _(this).val();
		});
		bind_checkall('.checkall','.ck_item');
		_('.action_delete').bind('click',function(){
			_(this).parent().parent().find('.ck_item').prop('checked',true);
			_('.btn_submit').run('click');
		});

		_('#bulk_form').bind('submit',function(event){
			var no = 0;   
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					no += 1;
				}
			});
			if(no > 0){
				if(!confirm('<?php _e('Are you sure delete it?');?>')){
					_().stopevent(event);
				}
			}else{
				alert('<?php _e('No Record Selected');?>.');
				_().stopevent(event);
			}
			});
	});
//-->
</script>