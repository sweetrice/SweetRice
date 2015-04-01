/*
	SweetRice dashboard control center
*/
_().ready(function(){
	_('#toggle_nav').bind('click',function(){
		_('#dashboard_nav').toggle();
	});
	_('#admin_right').css({'min-height':_.pageSize().windowHeight+'px'});
	_('#dashboard_nav ul li div div').bind('mouseout',function(){
		_(this).removeClass('show_').addClass('hidden_',function(){
			_('#admin_right').css({'min-height':_('#admin_left').height()+'px'});
		});
	});
	
	_('#dashboard_nav ul li div').bind('mouseover',function(){
		_(this).find('div').removeClass('hidden_').addClass('show_',function(){
			_('#admin_right').css({'min-height':_('#admin_left').height()+'px'});
		});
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
	_('.toggle').bind('click',function(){
		_(_(this).attr('data')).toggle();
	});
	_('.ncr').bind('click',function(event){
		_.dialog({name:'ncrpop',url:_(this).attr('href')});
		_.stopevent(event);
	});
	_('.back').bind('click',function(){
		if (_(this).attr('url')){
			location.href = _(this).attr('url');
		}
	});
});
_(window).bind('resize',function(){
	if (_.pageSize().windowWidth > 960 && _('#dashboard_nav').css('display') == 'none')
	{
		_('#dashboard_nav').show();
	}
	if (_.pageSize().windowWidth < 960 && _('#dashboard_nav').css('display') == 'block')
	{
		_('#dashboard_nav').hide();
	}
});