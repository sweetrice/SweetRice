/*
	SweetRice dashboard control center
*/
_().ready(function(){
	_('#dashboard_nav ul li div div').bind('mouseout',function(){
		_(this).removeClass('show_').addClass('hidden_');
	});
	
	_('#dashboard_nav ul li div').bind('mouseover',function(){
		_(this).find('div').removeClass('hidden_').addClass('show_');
	});
	_('#top_line').bind('click',function(){
		var top_height = _(this).attr('data');
		if (top_height !='normal'){
			_.setCookie({'name':'top_height','value':'normal'});
		}else {
			_.setCookie({'name':'top_height','value':'small'});
		}
		window.location.reload();
	});
});