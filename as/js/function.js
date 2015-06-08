<!--
function from_bulk(form,success,error){
	_.ajax({
		type:_(form).attr('method'),
		form:'#'+_(form).attr('id'),
		url:_(form).attr('action'),
		success:function(result){
			if (result['status_code'])
			{
				_.ajax_untip(result['status_code']);
			}
			if (result['status'] == 1)
			{
				if (typeof success == 'function')
				{
					success();
				}
			}else{
				if (typeof error == 'function')
				{
					error();
				}
			}
		}
	});
}

function bind_checkall(me,selector){
	_(me).bind('click',function(){
			var cked = false;
			if (_(this).prop('checked'))
			{
				cked = true;
			}
			_(selector).prop('checked',cked);
		});
}
//-->