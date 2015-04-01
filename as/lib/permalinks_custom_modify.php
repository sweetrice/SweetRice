<?php
/**
 * Links management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
?>
<form method="post" action="./?type=permalinks&linkType=custom&mode=save" id="pform">
<input type="hidden" name="id" value="<?php echo $row['lid'];?>"/>
<input type="hidden" name="plugin" value="<?php echo $row['plugin'];?>"/>
<fieldset><legend><strong><?php _e('URL');?>:</strong></legend>
<input type="text" name="url" class="input_text" value="<?php echo $row['url'];?>"/> <div class="tip"><?php _e('Without host name,just input directory or page name ,example: /custom_dir/custom_page.html /custom_dir/custom_page/ etc.');?></div>
</fieldset>
<fieldset><legend><strong><?php _e('Request');?>:</strong></legend>
<?php
$no = 0;
$reqs = unserialize($row['request']);
if(is_array($reqs)){
	foreach($reqs AS $key=>$val){
		$no +=1;
?>
<div class="att_list" id="req_<?php echo $no;?>">
<li>
<?php _e('Keys');?> <input type="text" name="keys[]" value="<?php echo $key;?>"/>  
<?php _e('Vals');?> <input type="text" name="vals[]" value="<?php echo $val;?>"/> <input type="button" value="<?php _e('Remove parameter');?>" class="btn_del" data="<?php echo $no;?>">
</li>
</div>
<?php
	}
}
?>
<input type="hidden" id="no" name="no" value="<?php echo $no;?>"  >
<div id="multi_request"></div>
<input type="button" value="<?php _e('Add parameter');?>" class="btn_add">
</fieldset>
<script type="text/javascript">
	<!--
	var reqNo = <?php echo $no;?>;
	var currentNo = 0;
	//-->
	</script>
<input type="submit" value=" <?php _e('Done');?> " class="input_submit"/> <input type="button" value="<?php _e('Back');?>" url="./" class="input_submit back">
</form>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.back').bind('click',function(){
			location.href = './?type=permalinks&linkType=custom';
		});
		_('.btn_add').bind('click',function(){
			reqNo += 1;
			_('#no').val(reqNo);
			var new_req = document.createElement('div');
			_(new_req).attr('id','req_'+reqNo).addClass('att_list').html('<li><?php _e('Keys');?> <input type="text" name="keys[]"/> <?php _e('Vals');?> <input type="text" name="vals[]"/> <input type="button" value="<?php _e('Remove parameter');?>" data="'+reqNo+'" id="btn_'+reqNo+'" class="btn_del"></li>');
			_('#multi_request').append(new_req);
			_('#btn_'+reqNo).bind('click',function(){
				_('#req_'+_(this).attr('data')).remove();
			});
		});

		_('.btn_del').bind('click',function(){
			_('#req_'+_(this).attr('data')).remove();
		});

		_('#pform').bind('submit',function(event){
			_.ajax({
				type:_(this).attr('method'),
				form:'#pform',
				url:_(this).attr('action'),
				success:function(result){
					_.dialog({
						'content':result.status_code,
						'close':function(){
							if (result.status == 1){
								location.href = './?type=permalinks&linkType=custom';
							}
						}
					});
				}
			});
			_.stopevent(event);
			return false;
		});
	});
//-->
</script>