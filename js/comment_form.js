<!--
var comment_ajax = false;
_().ready(function(){
	_('#comment_button').bind('click',function(){
		if (comment_ajax){
			return ;
		}
		var name = _("#name").val();
		if(!name){
			alert(cmt_tip_enter_name);
			_("#name").run('focus');
			return ;
		}
		var email =_("#email").val();
		if(!CheckEmail(email)){
			alert(cmt_tip_enter_email);
			_("#email").run('focus');
			return ;
		}
		var code = _('#code').val();
		if(!code){
			alert(cmt_tip_enter_code);
			_("#code").run('focus');
			return ;
		}
		var info = _("#info").val();
		if(info==""||info==null){
			alert(cmt_tip_enter_comment);
			_("#info").run('focus');
			return ;
		}
		if (_('#remember').prop('checked')){
			var remember = 1;
		}else{
			var remember = 0;
		}
		var website = _('#website').val();
		var postID = _('#postID').val();
		comment_ajax = true;
		var query = new Object();
		query.email = escape(email);
		query.name = escape(name);
		query.website = escape(website);
		query.info = escape(info);
		query.postID = escape(postID);
		query.code = escape(code);
		query.remember = remember;
		var ajax_dlg = _.dialog({'content':'<img src="images/ajax-loader.gif">','name':'ajax_tip'});
		_.ajax({
			'type':'POST',
			'data':query,
			'url':'./?action=comment&mode=insert',
			'success':function(result){
					comment_ajax = false;
					ajax_dlg.remove();
					if (typeof(result) == 'object'){
						switch (result['status']){
							case '0':
								_.ajax_untip(result['status_code']);
							break;
							case '1':
								_('#info').val('');
								_('#code').val('');
								_('#captcha').attr('src','images/captcha.png');
								_.ajax_untip(result['status_code']);
							break;
							default:
								_.ajax_untip(cmt_tip_noresponse);
						}
					}
			}
		});
	});
});
//-->