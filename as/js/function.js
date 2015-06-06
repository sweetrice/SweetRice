<!--
function deleteAction(c,v,no){
	var url = '';
	var query = new Object();
	v = escape(v);
	switch (c){
		case 'category':
			url = './?type=category&mode=delete';
			query.id = v; 
		break;
		case 'post':
			url = './?type=post&mode=delete';
			query.id = v; 
		break;
		case 'comment':
			url = './?type=comment&mode=delete';
			query.id = v; 
		break;
		case 'media_center':
			url = './?type=media_center&mode=delete';
			query.file = v;
		break;
		case 'media':
			url = './?type=media&mode=delete';
			query.file = v; 
		break;
		case 'db_backup':
			url = './?type=data&mode=db_import&form_mode=delete';
			query.db_file = v;
		break;
		case 'ad':
			url = './?type=ad&mode=delete';
			query.adk = v;
		break;
		case 'links':
			url = './?type=permalinks&mode=custom&submode=delete';
			query.id = v; 
		break;
		case 'sites':
			url = './?type=sites&mode=delete';
			query.host = v;
		break;
	}
	query.no = no;
	var ajax_dlg = _.dialog({'content':'<img src="../images/ajax-loader.gif">','name':'ajax_tip'});
	_.ajax({
		'type':'POST',
		'data':query,
		'url':url,
		'success':function(result){
			ajax_dlg.remove();
			if (typeof(result) == 'object'){
				if (result['status']==1){
					_('#tr_'+result['no']+',#li_'+result['no']).fadeOut(500,function(){
						_('#tr_'+result['no']+',#li_'+result['no']).remove();
					});
				}
				if (result['status_code'])
				{
					_.ajax_untip(result['status_code']);
				}
			}
		}
	});
}

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