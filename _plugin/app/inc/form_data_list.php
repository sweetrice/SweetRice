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
.formdata{display:none;}
.toggle_data{text-align:center;cursor:pointer;}
.btn_preview{width:100%;word-wrap: break-word; word-break: break-all;}
.formdata_title{margin-bottom:5px;font-weight:bold;}
.formdata_content{margin:5px 0px;border-bottom:1px solid #ccc;padding:5px;}
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
	<tr>
		<th class="data_no"><input type="checkbox" class="checkall"/></th>
		<th class="max50"><?php _e('Form Name');?></th>
	<?php if($form_id > 0):?>
	<?php foreach($this_form['fields'] as $field):?>
		<th><?php echo $field['name'];?></th>
	<?php endforeach;?>
	<?php else:?>
		<th><?php _e('Data');?></th>
	<?php endif;?>
	<th style="width:130px;" class="td_admin"><?php _e('Date');?></th><th style="width:30px;"><?php _e('Admin');?></th></tr>
</thead>
<tbody>
<?php
	foreach($data['rows'] AS $key => $row ){
		$fields = unserialize($row['fields']);
		$form_data = unserialize($row['data']);
?>
<tr>
	<td><input type="checkbox" name="plist[]" value="<?php echo $row['id'];?>" class="ck_item"/></td>
	<td data-label="<?php _e('Form Name');?>"><a href="<?php echo BASE_URL.pluginHookUrl(THIS_APP,array('app_mode'=>'form','id'=>$row['form_id']))?>" target="_blank"><?php echo $row['name'];?></a></td>
	<?php if($form_id > 0):?>
	<?php foreach($this_form['fields'] as $field):?>
		<td data-label="<?php echo $field['name'];?>"><?php echo $form_data[$field['name']];?></td>
	<?php endforeach;?>
	<?php else:?>
<td class="form_data" data-label="<?php _e('Data');?>">
<div class="toggle_data">---</div>
<div class="formdata">
<?php foreach($fields as $val):
?>
<div class="formdata_title"><?php echo $val['tip'];?></div>
<div class="formdata_content">
<?php
if($val['type'] == 'file'){
?>
<a href="javascript:void(0);" url="<?php echo BASE_URL.'_plugin/app/data/form/'.$form_data[$val['name']];?>" class="btn_preview"><?php echo $form_data[$val['name']];?></a>
<?php
}elseif($val['type'] == 'multi_file'){
	foreach($form_data[$val['name']] as $mfile){
?>
<a href="javascript:void(0);" url="<?php echo BASE_URL.'_plugin/app/data/form/'.$mfile;?>" class="btn_preview"><?php echo $mfile;?></a>
<?php
	}
}elseif($val['type'] == 'password'){
?>
	<input type="password" value="<?php echo $form_data[$val['name']];?>" onclick="if(this.type == 'text'){this.type = 'password'}else{this.type = 'text'};" readonly>
<?php
}elseif($val['type'] == 'select'){
	foreach($form_data[$val['name']] as $tmp){
?>
<?php echo $tmp;?> 
<?php
	}
}else{
	echo nl2br($form_data[$val['name']]);
}
?>
</div> 
<div>
<?php
endforeach;?></td>
	<?php endif;?>
<td data-label="<?php _e('Date');?>"><?php echo date('M d Y H:i',$row['date']);?></td>
<td data-label="<?php _e('Admin');?>">
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
			_.dialog({'title':'<?php _e('View form data');?>','content':_(this).parent().find('.formdata').html(),'layer':1});
			_('.btn_preview').bind('click',function(){
				_.dialog({'content':'<img src="'+_(this).attr('url')+'" style="width:320px;"></iframe>','width':340});
			});
		});
		_('#form').bind('change',function(){
			location.href  = '<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'form_data'));?>&form_id=' + _(this).val();
		});
		bind_checkall('.checkall','.ck_item');
		_('.action_delete').bind('click',function(){
			_('.ck_item').prop('checked',false);
			_(this).parent().parent().find('.ck_item').prop('checked',true);
			_('.btn_submit').run('click');
		});

		_('#bulk_form').bind('submit',function(event){
			_.stopevent(event);
			var no = 0;   
			_('.ck_item').each(function(){
				if (_(this).prop('checked')){
					no += 1;
				}
			});
			if(no > 0){
				if(!confirm('<?php _e('Are you sure delete it?');?>')){
					return ;
				}
			}else{
				alert('<?php _e('No Record Selected');?>.');
				return ;
			}
			from_bulk(this,function(){
				_('.ck_item').each(function(){
					if (_(this).prop('checked')){
						var _this = this;
						_(this).fadeOut(500,function(){
							_(_this).parent().parent().remove();
						});
					}
				});
			});
		});
	});
//-->
</script>