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
<form method="post" action="./?type=permalinks&linkType=custom&mode=save">
<input type="hidden" name="id" value="<?php echo $row['lid'];?>"/>
<input type="hidden" name="plugin" value="<?php echo $row['plugin'];?>"/>
<fieldset><legend><strong><?php echo URL;?>:</strong></legend>
<input type="text" name="url" class="input_text" value="<?php echo $row['url'];?>"/> <?php echo CUSTOM_URL_TIP;?>
</fieldset>
<fieldset><legend><strong><?php echo REQUEST;?>:</strong></legend>
<?php
$no = 0;
$reqs = unserialize($row['request']);
if(is_array($reqs)){
	foreach($reqs AS $key=>$val){
		$no +=1;
?>
<div class="att_list" id="req_<?php echo $no;?>">
<li>
Keys <input type="text" name="keys[]" value="<?php echo $key;?>"/>  
Vals <input type="text" name="vals[]" value="<?php echo $val;?>"/> <input type="button" value="<?php echo REMOVE_PARAMETER;?>" class="btn_del" data="<?php echo $no;?>">
</li>
</div>
<?php
	}
}
?>
<input type="hidden" id="no" name="no" value="<?php echo $no;?>"  >
<div id="multi_request"></div>
<input type="button" value="<?php echo ADD_PARAMETER;?>" class="btn_add">
</fieldset>
<script type="text/javascript">
	<!--
	var reqNo = <?php echo $no;?>;
	var currentNo = 0;
	var REMOVE_PARAMETER = '<?php echo REMOVE_PARAMETER?>';
	//-->
	</script>
<input type="submit" value=" <?php echo DONE;?> " class="input_submit"/> <input type="button" value="<?php echo BACK;?>" class="input_submit back">
</form>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.back').bind('click',function(){
			location.href="./?type=permalinks&linkType=custom";
		});
		_('.btn_add').bind('click',function(){
			reqNo += 1;
			_('#no').val(reqNo);
			var new_req = document.createElement("div");
			_(new_req).attr('id','req_'+reqNo).addClass('att_list').html('<li>Keys <input type="text" name="keys[]"/> Vals <input type="text" name="vals[]"/> <input type="button" value="'+REMOVE_PARAMETER+'" data="'+reqNo+'" id="btn_'+reqNo+'" class="btn_del"></li>');
			_('#multi_request').append(new_req);
			_('#btn_'+reqNo).bind('click',function(){
				_('#req_'+_(this).attr('data')).remove();
			});
		});

		_('.btn_del').bind('click',function(){
			_('#req_'+_(this).attr('data')).remove();
		});
	});
//-->
</script>