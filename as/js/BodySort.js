/*
	SweetRice tr li sort function
	param 
	orderby : sort by
	cid : target object
*/
function sortBy(orderby,cid,callback){
	if (typeof callback != 'function')
	{
		callback = function(){
			bind_checkall('#checkall','.ck_item');
		}
	}
	var pBody = [],ts = [], tsid = [],gsid = '',thead,sort = 'asc';
	var is_li = _(cid).find('li').items().length > 0 ?true:false;
	if (is_li){
		var lis = _(cid).find('li');
	}else{
		var lis = _(cid).find('tbody').find('tr');
		thead = '<thead>'+_(cid).find('thead').html()+'</thead>';
	}
	var lid,tbody,cname;
	var tmp = 0;
	lis.each(function(){
		lid = parseInt(_(this).attr('id').replace(is_li?'li_':'tr_',''));
		cname = _(this).attr('className')||_(this).attr('class');
		pBody[lid] = is_li?'<li id="'+_(this).attr('id')+'"'+(cname?'  class="'+cname+'"':'')+'>'+_(this).html()+'</li>':'<tr id="'+_(this).attr('id')+'"'+(cname?'  class="'+cname+'"':'')+'>'+_(this).html()+'</tr>';
		tbody = '_'+_('#'+_(orderby).attr('data')+'_'+lid).html();
		if (parseInt(tsid[tbody]) > 0 )
		{
			gsid += escape(tbody)+'^'+lid+'|';
		}else {
			ts[tmp] = tbody;
			tsid[tbody] = lid;
		}
		tmp += 1;
	});
	this.sortType = function(a,b){
		if (_(orderby).attr('stt') != 'number'){
			if(_(orderby).attr('sort') == 'asc'){
				return a.localeCompare(b);
			}else{
				return b.localeCompare(a);
			}
		}else {
			a = parseFloat(a.replace('_',''));
			b = parseFloat(b.replace('_',''));
			if(_(orderby).attr('sort') == 'asc'){
				return a-b;
			}else{
				return b-a;
			}
		}
	};
	this.showGbody = function(tbody){
		var tmp = gsid.split('|');
		var glist = '';
		for (i in tmp )
		{
			var temp = tmp[i].split('^');
			if (temp[0] == escape(tbody))
			{
				glist += temp[1]+',';
			}
		}
		return glist;
	};
	this.newSort = function (){
		var str = '';
		var tmp = new Array();
		ts.sort(sortType);
		var tl = ts.length;
		for (var i=0;i<tl;i++)
		{
			if (pBody[tsid[ts[i]]])
			{
				str += pBody[tsid[ts[i]]];
				tmp = this.showGbody(ts[i]).split(',');
				for (k in tmp)
				{
					if (tmp[k] && pBody[tmp[k]])
					{
						str += pBody[tmp[k]];
					}
				}
			}
		}
		return str;
	};
	if (is_li)
	{
		_(cid).html(newSort());
	}else{
		_(cid).html('<table>'+thead + newSort()+'</table>');
	}
	if (_(orderby).attr('sort') == 'asc'){
		sort = 'desc';
	}else{
		sort = 'asc';
	}
	_('.btn_sort').each(function(){
		if (_(this).attr('data') == _(orderby).attr('data')){
			_(this).attr('sort',sort);
		}
	});
	_('.btn_sort').unbind('click').bind('click',function(){
			sortBy(this,cid,callback);
	});
	if (is_li){
		lis = _(cid).find('li');
	}else{
		lis = _(cid).find('tbody').find('tr');
	}
	var tmp = 0;
	lis.each(function(){
		if (tmp%2 == 0)
		{
			_(this).removeClass('tr_double').addClass('tr_sigle');
		}else{
			_(this).removeClass('tr_sigle').addClass('tr_double');
		}
		tmp += 1;
	});
	callback();
}