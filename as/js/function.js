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
			url = './?type=permalinks&linkType=custom&mode=delete';
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
					_('#tr_'+result['no']).fadeOut(500,function(){
						_('#tr_'+result['no']).css('display','none');
					});
				}
				_.ajax_untip(result['status_code']);
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

function showMedia(event){
	event = event||window.event;
	_('#ImagePreview').show(function(){
		_('#media').css({'left':parseInt((_.pageSize().pageWidth-800)/2)+'px','top':_.scrollSize().top+'px'}).fadeIn();
	});
	
	_('#ImagePreview').refillscreen();
	mouse_bind(_('#media'),'#menuBar');
	_(window).bind('resize', function(){
		_('#media').css({'left':parseInt((_.pageSize().pageWidth-800)/2)+'px','top':(scrollSize.top+(_.pageSize().windowHeight-480)/2)+'px'+'px'});
		_('#ImagePreview').refillscreen();
	});
}


function replaceAtt(no,event){
	currentNo = no;
	 _.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','title':'Choose file','name':'media','width':800,'height':500,'layer':true});
}
function closeMedia(){
	_('#SweetRice_dialog_media').remove();
	_('#SweetRice_layer_dialog').remove();
}
function addfile(event){
	attNo += 1;
	currentNo = attNo;
	_('#no').val(attNo);
	var new_file = document.createElement('div');
	_(new_file).attr('id','f_'+attNo).html('<div class="att_list">New <input type="text" name="att_'+attNo+'" id="att_'+attNo+'" class="input_text"/><span id="attname_'+attNo+'"></span> <input type="button" value="'+REMOVE_FILE+'" onclick="delfile('+attNo+',event);"> <input type="button" value="'+REPLACE_TIP+'" onclick="replaceAtt('+attNo+',event);" ></div>');
	_('#muti_files').append(new_file);
	_.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','name':'media','width':800,'height':500,'layer':true});
}
function delfile(f_no){
	_('#f_'+f_no).remove();
	closeMedia();
}

//-->