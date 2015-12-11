/**
 * SweetRice javascript function.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
<!--
(function( window ) {
	if (!window.getComputedStyle) {
		window.getComputedStyle = function(el, prop) {
			this.el = el;
			this.getPropertyValue = function(prop) {
				var re = /(\-([a-z]){1})/g;
				if (prop == 'float') prop = 'styleFloat';
				if (re.test(prop)) {
					prop = prop.replace(re, function () {
						return arguments[2].toUpperCase();
					});
				}
				for (var i in el.currentStyle)
				{
					if (i == prop)
					{
						switch (prop)
						{
							case 'width':
								if (el.currentStyle[i] == 'auto')
								{
									return _(el).width()+'px';
								}
							break;
							case 'height':
								if (el.currentStyle[i] == 'auto')
								{
									return _(el).height()+'px';
								}
							break;
						}
						return el.currentStyle[i];
					}
				}
				return false;
			}
			return this;
		}
	}else{
		getComputedStyle = document.defaultView && document.defaultView.getComputedStyle;
	}
	function SweetRice(elm){
		this.ready = function(fn){
			if(document.addEventListener){
					document.addEventListener('DOMContentLoaded',function(){
						document.removeEventListener('DOMContentLoaded',arguments.callee,false);  
						fn();
					},false);  
			}else if(document.attachEvent){ 
				 IEContentLoaded (window, fn);
			}

			function IEContentLoaded (w, fn) {
					var d = w.document, done = false,
					// only fire once
					init = function () {
							if (!done) {
									done = true;
									fn();
							}
					};
					// polling for no errors
					(function () {
							try {
									// throws errors until after ondocumentready
									d.documentElement.doScroll('left');
							} catch (e) {
									setTimeout(arguments.callee, 50);
									return;
							}
							// no errors, fire

							init();
					})();
					// trying to always fire before onload
					d.onreadystatechange = function() {
							if (d.readyState == 'complete') {
									d.onreadystatechange = null;
									init();
							}
					};
			}
		};
		if (typeof elm == 'function') {
			this.ready(elm);
			return ;
		}
		this.inArray = function(a,b){
			for (var i in a )
			{
				if (a[i] == b)
				{
					return true;
				}
			}
			return false;
		};
		this.getNode = function(selector,ele){
			if ( !selector ) {
				return false;
			}
			ele = ele || document;
			var a = [],b,_a = [],l,_b,_selector,_elm = [];
			if (selector.substring(0,1) == '.' || selector.substring(0,1) == '#'){
				_selector = selector.substring(1,selector.length);
			}
			if (Object.prototype.toString.call( ele ) !== '[object Array]')
			{
				_a.push(ele);
			}else{
				_a = ele;
			}
			for (var i in _a)
			{
				_b = [];
				if (selector.substring(0,1) == '.'){
					if (_a[i].getElementsByClassName){
						_b = _a[i].getElementsByClassName(_selector);
					}else{
						b = _a[i].getElementsByTagName('*');
						for (var j=0; j< b.length; j++ ) {
							if (b[j].className){
								var tmp = b[j].className.split(' ');
								for (var k in tmp ){
									if (tmp[k] == _selector){
										_b.push(b[j]);
									}
								}
							}
						}
					}
				}else if (selector.substring(0,1) == '#'){
					if (document.getElementById(_selector)){
						a.push(document.getElementById(_selector));
					}
				}else if(_a[i].getElementsByTagName){
					_b = _a[i].getElementsByTagName(selector);
				}
				for (var ii=0;ii<_b.length;ii++)
				{
					if (!this.inArray(a,_b[ii]))
					{
						a.push(_b[ii]);
					}
				}
			}
			for (var i in a )
			{
				if (typeof a[i] == 'object')
				{
					_elm.push(a[i]);
				}
			}
			return _elm;
		};
		
		this.init_elm = function(elm){
			var _elm = [];
			for (var i in elm )
			{
				if (elm[i].replace(/^\s+|\s+$/g, ''))
				{
					_elm.push(elm[i]);
				}
			}
			return _elm;
		}
		this.init = function(){
			var a = [];
			if (elm.indexOf(',') != -1)
			{
				var elms = elm.split(',');
				elms = this.init_elm(elms);
				if (elms.length > 0)
				{
					for (var i in elms )
					{
						var tmp = this.getNode(elms[i]);
						for (var ti in tmp ){
							a.push(tmp[ti]);
						}
					}
				}
				return a;
			}
			var elms = elm.split(' ');
			elms = this.init_elm(elms);
			if (elms.length > 1)
			{
				a = this.getNode(elms[0]);
				var n = 1;
				while (n < elms.length)
				{
					a = this.getNode(elms[n],a);
					n += 1;
				}
				return a;
			}
			return this.getNode(elms[0]);
		}
		if (typeof elm == 'string'){
			var _elm = this.init();
			if (Object.prototype.toString.call( _elm ) === '[object Array]' && _elm.length == 1){
				elm = _elm[0];
			}else{
				elm = _elm;
			}
		}	
		var _this = this;
		this.parent = function(){
			var p_elm = [];
			if (this.isArray()){
				for (var i in elm ){
					p_elm.push(elm[i].parentNode);
				}
				elm = p_elm;
			}else{
				elm = elm.parentNode;
			}
			return this;
		};

		this.prev = function(){
			var p_elm = [];
			if (this.isArray()){
				for (var i in elm ){
					if (elm.previousSibling.nodeName == '#text'){
						p_elm.push(elm[i].previousSibling.previousSibling);
					}else{
						p_elm.push(elm[i].previousSibling);
					}
				}
				elm = p_elm;
			}else{
				elm = elm.previousSibling;
				if (elm.nodeName == '#text'){
					elm = elm.previousSibling;
				}
			}
			return this;
		};

		this.next = function(){
			var p_elm = [];
			if (this.isArray()){
				for (var i in elm ){
					if (elm.nextSibling.nodeName == '#text'){
						p_elm.push(elm[i].nextSibling.nextSibling);
					}else{
						p_elm.push(elm[i].nextSibling);
					}
				}
				elm = p_elm;
			}else{
				elm = elm.nextSibling;
				if (elm.nodeName == '#text'){
					elm = elm.nextSibling;
				}
			}
			return this;
		};

		this.find = function(selector){
			var _elm;
			_elm = this.getNode(selector,elm);
			if (Object.prototype.toString.call( _elm ) === '[object Array]' && _elm.length == 1){
				elm = _elm[0];
			}else{
				elm = _elm;
			}
			return this;
		};

		this.width = function(v){
			if (this.isArray()){
				return false;
			}
			if (v){
				return this.css('width',v);
			}
			return elm.offsetWidth;
		};

		this.height = function(v){
			if (this.isArray() && !v){
				return false;
			}
			if (v){
				return this.css('height',v);
			}
			return elm.offsetHeight;
		};

		this.pageSize = function(){
			var xScroll,yScroll;
			if(window.innerHeight&&window.scrollMaxY){
				xScroll = window.innerWidth+window.scrollMaxX;
				yScroll = window.innerHeight+window.scrollMaxY;
			}else if(document.body.scrollHeight > document.body.offsetHeight){
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
				}else{
					xScroll = document.body.offsetWidth;
					yScroll = document.body.offsetHeight;
				}
			var windowWidth,windowHeight;
			if(self.innerHeight){
				if(document.documentElement.clientWidth){
					windowWidth = document.documentElement.clientWidth;
				}else{
					windowWidth = self.innerWidth;
				}
				windowHeight = self.innerHeight;
			}else	if(document.documentElement&&document.documentElement.clientHeight){
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
			}else if(document.body){
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}
			pageHeight = Math.max(yScroll,windowHeight);
			pageWidth = Math.min(xScroll,windowWidth);
			return {'pageWidth':pageWidth,'pageHeight':pageHeight,'windowWidth':windowWidth,'windowHeight':windowHeight};
		}

		this.scrollSize = function(){
			var xScroll,yScroll;
			if(self.pageYOffset || self.pageXOffset){
				yScroll = self.pageYOffset;
				xScroll = self.pageXOffset;
			}else if(document.documentElement && (document.documentElement.scrollTop || document.documentElement.scrollLeft)){
				yScroll = document.documentElement.scrollTop;
				xScroll = document.documentElement.scrollLeft;
			}else if(document.body){
				yScroll = document.body.scrollTop;
				xScroll = document.body.scrollLeft;
			}
			return {'left':xScroll,'top':yScroll};
		};

		this.scrollTop = function(dist,speed, callback,animate_fn,animate_complete){
			var obj = elm;
			if (elm == document.body)
			{
				if(document.documentElement && document.documentElement.scrollTop){
					obj = document.documentElement;
				}else if(document.body){
					obj = document.documentElement;
				}
			}
			if (typeof dist == 'undefined' || isNaN(parseInt(dist)))
			{
				return obj.scrollTop;
			}
			return _(obj).animate({'scrollTop':dist},speed, callback,animate_fn,animate_complete);
		}

		this.scrollLeft = function(dist,speed, callback,animate_fn,animate_complete){
			var obj = elm;
			if (elm == document.body)
			{
				if(document.documentElement && document.documentElement.scrollLeft){
					obj = document.documentElement;
				}else if(document.body){
					obj = document.documentElement;
				}
			}
			if (!dist)
			{
				return obj.scrollLeft;
			}
			return _(obj).animate({'scrollLeft':dist},speed, callback,animate_fn,animate_complete);
		}

		this.isArray = function(){
			return Object.prototype.toString.call( elm ) === '[object Array]';
		};
		this.each = function(fn,callback){
				if (typeof fn != 'function'){
					return ;
				}
				if (this.isArray()){
					for (var i in elm ){
						if (elm[i].nodeName != undefined){
							(function(){fn.apply(elm[i]);})();
						}
					}
				}else{
					(function(){fn.apply(elm);})();
				}
				if (typeof callback == 'function'){
					callback.apply(elm);
				}
				return this;
		};
		

		this.html = function(v,callback){
			if (typeof elm != 'object'){
				return ;
			}
			if (typeof(v) != 'undefined'){
				return this.each(function(){
					this.innerHTML = v;
					var tmp = this;
					_(tmp).find('*').each(function(){
						if (this.tagName.toUpperCase() == 'SCRIPT'){
							if (_(this).attr('src')){
								_.ajax({
									'type':'GET',
									'url':_(this).attr('src'),
									'success':function(result){
										eval(result.replace(/\<\!\-\-/ig,'').replace(/\/\/\-\-\>/ig,'').replace(/\r/ig, '').replace(/\n/ig, ''));
									}
								});
							}else{
								eval(this.innerHTML.replace(/\<\!\-\-/ig,'').replace(/\/\/\-\-\>/ig,'').replace(/\r/ig, '').replace(/\n/ig, ''));
							}
						}
					});
				},callback);
			}else{
				if (this.isArray()){
					return ;
				}
				return elm.innerHTML;	
			}
		};

		this.run = function(cmd,callback){
			if (typeof elm != 'object'){
				return ;
			}
			if (typeof(cmd) == 'string'){
				return this.each(function(){
					eval('this.'+cmd+'()');
				},callback);
			}
		};

		this.show = function(callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				_(this).css({'display':'block','visibility':'visible'});
			},callback);
		}; 
		
		this.toggle = function(callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				if (_(this).css('display') != 'none'){
					_(this).hide();	
				}else{
					_(this).show();
				}
			},callback);
		};

		this.hide = function(callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				_(this).css({'display':'none','visibility':'hidden'});
			},callback);
		};


		this.text = function(v,callback){
			if (typeof elm != 'object'){
				return ;
			}
			if (typeof(v) != 'undefined'){
				return this.each(function(){
					if (this.textContent)
					{
						this.textContent = v;
					}else{
						this.innerText = v;
					}
				},callback);
			}else{
				if (this.isArray()){
					return ;
				}
				return elm.innerText || elm.textContent;	
			}
		};

		this.attr = function(k,v,callback){
			if (typeof elm != 'object' || !k){
				return ;
			}
			if ((typeof(k) == 'string' && typeof(v)!='undefined') || typeof(k) == 'object'){
				return this.each(function(){
					if (typeof(k) == 'object')
					{
						for (var i in k)
						{
							this.setAttribute(i,k[i]);
						}
					}else{
						this.setAttribute(k,v);
					}
				},callback);
			}else{
				if (this.isArray() || typeof(k) == 'object' ){
					return ;
				}
				var l = elm.attributes.length;
				for (var i=0;i<l;i++)
				{
					if (elm.attributes[i].nodeName == k){
						return elm.attributes[i].value;
					}
				}
				return '';
			}
		};
		this.prop = function(k,v,callback){
			if (typeof elm != 'object' || !k){
				return ;
			}
			if ((typeof(k) == 'string' && typeof(v)!='undefined') || typeof(k) == 'object'){
				return this.each(function(){
					if (typeof(k) == 'object')
					{
						for (var i in k)
						{
							this[i] = k[i];
						}
					}else{
						this[k] = v;
					}
				},callback);
			}else{
				if (this.isArray() || typeof(k) == 'object' ){
					return ;
				}
				return elm[k];
			}
		};

		this.position = function(){
			if (typeof elm != 'object' || this.isArray()){
				return ;
			}
			var pos = new Object();
			pos.left = elm.offsetLeft;
			pos.top = elm.offsetTop;
			var current = elm.offsetParent;
			while (current !== null){
				pos.left += current.offsetLeft;
				pos.top += current.offsetTop;
				current = current.offsetParent;
			}
			return pos;
		};

		this.stop = function(stopAll,complete){
			var items = this.items();
			if (!items[0]){
				items[0] = items;
			}
			if (!_(items[0]).attr('_animate_')){
				return this;
			}
			var tmp = (_(items[0]).attr('_animate_')||'').split(',');
			if (stopAll)
			{	
				for (var i=0;i<tmp.length ;i++ )
				{
					Sweetrice.animate_handle[tmp[i]]['stop'] = true;
					Sweetrice.animate_handle[tmp[i]]['complete'] = complete;
				}
			}else{
				Sweetrice.animate_handle[tmp[0]]['stop'] = true;
				Sweetrice.animate_handle[tmp[0]]['complete'] = complete;
			}
			return this;
		};

		this.animate =  function( prop, speed, callback ,animate_fn,animate_complete){				
			if (typeof speed == 'undefined'){
				speed = 500;
			}
			color2Array = function(data){
				var a = [];
				if (data == 'transparent'){
					return 'transparent';
				}
				if (!/^#[0-9a-zA-Z]{3}/.test(data) && !/^#[0-9a-zA-Z]{6}/.test(data) && !/[a-zA-Z]+/.test(data))
				{
					return false;
				}
				var is_color_name = false;
				var color_map = {'ALICEBLUE':'#F0F8FF','ANTIQUEWHITE':'#FAEBD7','AQUA':'#00FFFF','AQUAMARINE':'#7FFFD4','AZURE':'#F0FFFF','BEIGE':'#F5F5DC','BISQUE':'#FFE4C4','BLACK':'#000000','BLANCHEDALMOND':'#FFEBCD','BLUE':'#0000FF','BLUEVIOLET':'#8A2BE2','BROWN':'#A52A2A','BURLYWOOD':'#DEB887','CADETBLUE':'#5F9EA0','CHARTREUSE':'#7FFF00','CHOCOLATE':'#D2691E','CORAL':'#FF7F50','CORNFLOWERBLUE':'#6495ED','CORNSILK':'#FFF8DC','CRIMSON':'#DC143C','CYAN':'#00FFFF','DARKBLUE':'#00008B','DARKCYAN':'#008B8B','DARKGOLDENROD':'#B8860B','DARKGRAY':'#A9A9A9','DARKGREEN':'#006400','DARKKHAKI':'#BDB76B','DARKMAGENTA':'#8B008B','DARKOLIVEGREEN':'#556B2F','DARKORANGE':'#FF8C00','DARKORCHID':'#9932CC','DARKRED':'#8B0000','DARKSALMON':'#E9967A','DARKSEAGREEN':'#8FBC8F','DARKSLATEBLUE':'#483D8B','DARKSLATEGRAY':'#2F4F4F','DARKTURQUOISE':'#00CED1','DARKVIOLET':'#9400D3','DEEPPINK':'#FF1493','DEEPSKYBLUE':'#00BFFF','DIMGRAY':'#696969','DODGERBLUE':'#1E90FF','FELDSPAR':'#D19275','FIREBRICK':'#B22222','FLORALWHITE':'#FFFAF0','FORESTGREEN':'#228B22','FUCHSIA':'#FF00FF','GAINSBORO':'#DCDCDC','GHOSTWHITE':'#F8F8FF','GOLD':'#FFD700','GOLDENROD':'#DAA520','GRAY':'#808080','GREEN':'#008000','GREENYELLOW':'#ADFF2F','HONEYDEW':'#F0FFF0','HOTPINK':'#FF69B4','INDIANRED':'#CD5C5C','INDIGO':'#4B0082','IVORY':'#FFFFF0','KHAKI':'#F0E68C','LAVENDER':'#E6E6FA','LAVENDERBLUSH':'#FFF0F5','LAWNGREEN':'#7CFC00','LEMONCHIFFON':'#FFFACD','LIGHTBLUE':'#ADD8E6','LIGHTCORAL':'#F08080','LIGHTCYAN':'#E0FFFF','LIGHTGOLDENRODYELLOW':'#FAFAD2','LIGHTGREY':'#D3D3D3','LIGHTGREEN':'#90EE90','LIGHTPINK':'#FFB6C1','LIGHTSALMON':'#FFA07A','LIGHTSEAGREEN':'#20B2AA','LIGHTSKYBLUE':'#87CEFA','LIGHTSLATEBLUE':'#8470FF','LIGHTSLATEGRAY':'#778899','LIGHTSTEELBLUE':'#B0C4DE','LIGHTYELLOW':'#FFFFE0','LIME':'#00FF00','LIMEGREEN':'#32CD32','LINEN':'#FAF0E6','MAGENTA':'#FF00FF','MAROON':'#800000','MEDIUMAQUAMARINE':'#66CDAA','MEDIUMBLUE':'#0000CD','MEDIUMORCHID':'#BA55D3','MEDIUMPURPLE':'#9370D8','MEDIUMSEAGREEN':'#3CB371','MEDIUMSLATEBLUE':'#7B68EE','MEDIUMSPRINGGREEN':'#00FA9A','MEDIUMTURQUOISE':'#48D1CC','MEDIUMVIOLETRED':'#C71585','MIDNIGHTBLUE':'#191970','MINTCREAM':'#F5FFFA','MISTYROSE':'#FFE4E1','MOCCASIN':'#FFE4B5','NAVAJOWHITE':'#FFDEAD','NAVY':'#000080','OLDLACE':'#FDF5E6','OLIVE':'#808000','OLIVEDRAB':'#6B8E23','ORANGE':'#FFA500','ORANGERED':'#FF4500','ORCHID':'#DA70D6','PALEGOLDENROD':'#EEE8AA','PALEGREEN':'#98FB98','PALETURQUOISE':'#AFEEEE','PALEVIOLETRED':'#D87093','PAPAYAWHIP':'#FFEFD5','PEACHPUFF':'#FFDAB9','PERU':'#CD853F','PINK':'#FFC0CB','PLUM':'#DDA0DD','POWDERBLUE':'#B0E0E6','PURPLE':'#800080','RED':'#FF0000','ROSYBROWN':'#BC8F8F','ROYALBLUE':'#4169E1','SADDLEBROWN':'#8B4513','SALMON':'#FA8072','SANDYBROWN':'#F4A460','SEAGREEN':'#2E8B57','SEASHELL':'#FFF5EE','SIENNA':'#A0522D','SILVER':'#C0C0C0','SKYBLUE':'#87CEEB','SLATEBLUE':'#6A5ACD','SLATEGRAY':'#708090','SNOW':'#FFFAFA','SPRINGGREEN':'#00FF7F','STEELBLUE':'#4682B4','TAN':'#D2B48C','TEAL':'#008080','THISTLE':'#D8BFD8','TOMATO':'#FF6347','TURQUOISE':'#40E0D0','VIOLET':'#EE82EE','VIOLETRED':'#D02090','WHEAT':'#F5DEB3','WHITE':'#FFFFFF','WHITESMOKE':'#F5F5F5','YELLOW':'#FFFF00','YELLOWGREEN':'#9ACD32'};
				for (var i in color_map){
					if (data.toUpperCase().search(new RegExp("\\b" + i + "\\b")) != -1){
						data = data.toUpperCase().replace(i,color_map[i]);
						is_color_name = true;
					}
				}
				if (is_color_name){
					data = data.toLowerCase();
				}
				if (/^#[0-9A-Z]{3}$/i.test(data))
				{
					a.push(parseInt(data.substring(1,2)+data.substring(1,2),16));
					a.push(parseInt(data.substring(2,3)+data.substring(2,3),16));
					a.push(parseInt(data.substring(3,4)+data.substring(3,4),16));
					return a;
				}
				if (/^#[0-9A-Z]{6}$/i.test(data))
				{
					a.push(parseInt(data.substring(1,3),16));
					a.push(parseInt(data.substring(3,5),16));
					a.push(parseInt(data.substring(5,7),16));
					return a;
				}
				if (/^rgb\([0-9]{1,3},\s*[0-9]{1,3},\s*[0-9]{1,3}\)$/i.test(data))
				{
					var t = data.toLowerCase().split(',');
					a.push(t[0].replace('rgb(',''));
					a.push(t[1]);
					a.push(t[2].replace(')',''));
					return a;
				}
				return false;
			};
			if (typeof animate_fn != 'function'){
				animate_fn = function(cv,ev,diff,speed){
					return parseFloat(cv + (ev - cv) * parseFloat(diff/speed,10),10);
				};
			}
			if (typeof animate_complete != 'function')
			{
				animate_complete = function(obj,prop,handle){
					for (var i in prop ){
						var cv_list = [],ev_list = [];
						var evs = color2Array(prop[i]);
						if (!!evs)
						{
							if (evs == 'transparent')
							{
								_(obj).removeAttr('_background-color_transparent');
							}else if(evs.length == 3){
								for (var j=0;j<evs.length ;j++ )
								{
									_(obj).removeAttr('_'+i+'_'+j);
								}
							}
						}else{
							_(obj).removeAttr('_'+i);
						}
					}
					var tmp = (_(obj).attr('_animate_')||'').split(','),al='';
					for (var i=0;i<tmp.length ;i++ ){
						if (tmp[i] != handle){
							al += tmp[i]+',';
						}
					}
					if (!al){
						_(obj).removeAttr('_animate_');
					}else{
						_(obj).attr('_animate_',al.substring(0,al.length-1));
					}
					var l = obj.attributes.length;
					for (var j=0;j<=l;j++)
					{
						if (!!obj.attributes[j])
						{
							if (obj.attributes[j].nodeName.substring(0,1) == '_'){
								obj.attributes.removeNamedItem(obj.attributes[j].nodeName);
							}
						}
					}
				};
			}
			
			var handle = Sweetrice.animate_handle.length;
			if (!!_this.attr('_animate_')){
				_this.attr('_animate_',_this.attr('_animate_')+','+handle);
			}else{
				_this.attr('_animate_',handle);
			}
			Sweetrice.animate_handle[handle] = [];
			Sweetrice.animate_handle[handle]['timer'] = setInterval(function(){
				if (!Sweetrice.animate_handle[handle]['start']){
					Sweetrice.animate_handle[handle]['start'] = new Date().getTime();
				}
				Sweetrice.animate_handle[handle]['diff'] = new Date().getTime() - Sweetrice.animate_handle[handle]['start'];
				if (Sweetrice.animate_handle[handle]['stop'])
				{
					window.clearInterval(Sweetrice.animate_handle[handle]['timer']);
					Sweetrice.animate_handle[handle]['start'] = null;
					Sweetrice.animate_handle[handle]['diff'] = null;
					_this.each(function(){
						if (!!Sweetrice.animate_handle[handle]['complete']){
							_(this).css(prop);
						}
						animate_complete(this,prop,handle);
					},function(){
						if (typeof callback == 'function'){
							callback.apply(elm);
						}
					});
					return ;
				}
				if (Sweetrice.animate_handle[handle]['diff'] >= speed){
					window.clearInterval(Sweetrice.animate_handle[handle]['timer']);
					Sweetrice.animate_handle[handle]['start'] = null;
					Sweetrice.animate_handle[handle]['diff'] = null;
					_this.each(function(){
						var css = [];
						for (var i in prop )
						{
							switch (i)
							{
								case 'scrollTop':
									this.scrollTop = prop[i];
								break;
								case 'scrollLeft':
									this.scrollLeft = prop[i];
								break;
								default:
									css[i] = prop[i];
							}
						}
						_(this).css(css);
						animate_complete(this,prop,handle);
					},function(){
						if (typeof callback == 'function'){
							callback.apply(elm);
						}
					});
				}else{
					_this.each(function(){
						var css = [],pos = [],cv,ev,add,pix,attr = [];
						for (var i in prop ){
							var cvs = color2Array(_(this).css(i));
							var evs = color2Array(prop[i]);
							if (evs && !cvs)
							{
								cvs = 'transparent';
							}
							if (!evs && cvs)
							{
								evs = 'transparent';
							}
							if (!!cvs && !!evs || parseInt(_(this).attr('_'+i+'_transparent')) > 0)
							{ 
								if (cvs == 'transparent' && evs == 'transparent'){
									continue;
								}else	if (cvs == 'transparent')
								{
									if (!_(this).attr('_'+i+'_transparent')){
										_(this).attr('_'+i+'_transparent',1);
									}
								}else	if (evs == 'transparent')
								{
									if (!_(this).attr('_'+i+'_transparent')){
										_(this).attr('_'+i+'_transparent',2+','+cvs.join());
									}
								}
								if (parseInt((_(this).attr('_'+i+'_transparent').toString()).substring(0,1)) == 1)
								{
									css[i] = 'rgba('+parseInt(evs[0])+','+parseInt(evs[1])+','+parseInt(evs[2])+','+animate_fn(0,1,Sweetrice.animate_handle[handle]['diff'],speed)+')';
								}else if(parseInt(_(this).attr('_'+i+'_transparent').toString().substring(0,1)) == 2){
									cvs = _(this).attr('_'+i+'_transparent').split(',');
									css[i] = 'rgba('+parseInt(cvs[1])+','+parseInt(cvs[2])+','+parseInt(cvs[3])+','+animate_fn(1,0,Sweetrice.animate_handle[handle]['diff'],speed)+')';
								}else{
									var pos = [];
									for (var j=0;j<cvs.length ;j++ )
									{
										if (!_(this).attr('_'+i+'_'+j)){
											_(this).attr('_'+i+'_'+j,cvs[j]);
										}
										cv = parseFloat(_(this).attr('_'+i+'_'+j).toString().replace(' ',''),10);
										ev = evs[j];
										pos[j] = animate_fn(cv,ev,Sweetrice.animate_handle[handle]['diff'],speed);
									}
									css[i] = 'rgb('+parseInt(pos[0])+','+parseInt(pos[1])+','+parseInt(pos[2])+')';
								}
							}else{
								var _i = i.toLowerCase();
								if (!_(this).attr('_'+_i)){
									switch (i)
									{
										case 'scrollTop':
											_(this).attr('_'+_i,this.scrollTop);
										break;
										case 'scrollLeft':
											_(this).attr('_'+_i,this.scrollLeft);
										break;
										default:
											_(this).attr('_'+_i,_(this).css(i));
									}
								}
								cv = parseFloat(parseInt(_(this).attr('_'+_i)),10);
								ev = parseFloat(prop[i],10);
								pix = prop[i].toString().replace(/[0-9\.\-]+/,'');
								var pos = animate_fn(cv,ev,Sweetrice.animate_handle[handle]['diff'],speed);
								switch (i){
									case 'opacity':
										css[i] =  pos + pix;
										css['filter'] = 'alpha(opacity='+parseInt(pos*100)+')';
									break;
									case 'scrollTop':
										this.scrollTop = pos;
									break;
									case 'scrollLeft':
										this.scrollLeft = pos;
									break;
									default:
										css[i] =  pos + pix;
								}
							}
						}
						_(this).css(css);
					});
				}
			},13);
			return this;
		};
		
		this.fadeIn = function(speed, callback,animate_fn,animate_complete){
			return this.show().animate({'opacity':1},speed, callback,animate_fn,animate_complete);
		};


		this.fadeOut = function(speed, callback,animate_fn,animate_complete){
			return this.animate({'opacity':0},speed, callback,animate_fn,animate_complete);
		};

		
		this.removeAttr = function(k,callback){ 
			if (typeof elm != 'object' || !k){
				return ;
			}
			return this.each(function(){
				if (_(this).attr(k))
				{
					this.attributes.removeNamedItem(k);
				}
			},callback);
		};
		this.getStyle = function(){
			return getComputedStyle(elm,'');
		};
		this.css = function (k,v,callback){
			if (typeof elm != 'object' || !k){
				return ;
			}
			if ((typeof(k) != 'object' && v) || typeof k == 'object'){
				return this.each(function(){
					if (this.nodeName != undefined){
						var css = [];
						var tmp = this.style.cssText.split(';')||'';
						for (var i in tmp){
							var _tmp = tmp[i].split(':');
							css[_tmp[0].toLowerCase().replace(' ','')] = _tmp[1];
						}
						if (typeof k == 'object'){
							for (var i in k ){
								css[i.toLowerCase().replace(' ','')] = k[i];
							}
						}else{
							css[k.replace(' ','')] = v;
						}
						var cssText = this.style.cssText + ';';
						for (var i in css ){
							if (!i){
								continue;
							}
							cssText += i+':'+css[i]+';';
						}
						this.style.cssText = cssText;
					}
				},callback);
			}else {
				if (this.isArray()){
					return ;
				}
				return this.getStyle().getPropertyValue(k)||'';
			}
		};
		
		this.addClass = function(v,callback){
			if (typeof elm != 'object' || !v){
				return ;
			}
			return this.each(function(){
				var this_class = _(this).attr('class') || this.className;
				if (this_class)
				{
					if (this_class.search(new RegExp("\\b" + v + "\\b")) == -1)
					{
						_(this).attr('class',this_class+' '+v);
						this.className = this_class+' '+v;
					}	
				}else{
					_(this).attr('class',v);
					this.className = v;
				}
			},callback);
		};

		this.removeClass = function(v,callback){
			if (typeof elm != 'object' || !v){
				return ;
			}
			return this.each(function(){
				var this_class = _(this).attr('class') || this.className;
				if (this_class)
				{ 
					_(this).attr('class',this_class.replace(new RegExp(v, 'g'),''));
					this.className = this_class.replace(new RegExp(v, 'g'),'');
				}
			},callback);
		};
		
		this.hasClass = function(v){
			if (this.isArray()){
				return ;
			}
			var this_class = _(elm).attr('class') || elm.className;
			return !(this_class.search(new RegExp("\\b" + v + "\\b")) == -1);
		};

		this.remove = function(callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				if (this.parentNode){
					this.parentNode.removeChild(this);
				}
			},callback);
		};


		this.append = function(obj,callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				this.appendChild(obj);
			},callback);
		};

		this.appendBefore = function(obj,callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				var _parent = this.parentNode;
				_parent.insertBefore(obj,this);
			},callback);
		};

		this.appendAfter = function(obj,callback){
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){          
				var _parent = this.parentNode;
        if(_parent.lastChild == this)
        {
          _parent.appendChild(obj);
        }else
        {
          _parent.insertBefore(obj,_(this).next().items());
        }    
			},callback);
		};

		this.load = function(url,callback){
			if (typeof url == 'function') {
				this.bind('load',function(){
					url();
				},false,callback);
				return ;
			}
			var query = new Object();
			_.ajax({
				'type':'GET',
				'data':query,
				'url':url,
				'success':function(result){
					return _this.each(function(){
					 _(this).html(result);
					},callback);
				}
			});
		};

		this.val = function (v,callback){
			if (typeof elm != 'object'){
				return ;
			}
			if (typeof v != 'undefined')
			{
				return this.each(function(){
					switch (this.nodeName.toLowerCase())
					{
						case 'input':
							switch (this.attributes['type'].value.toLowerCase())
							{
								case 'text':
								case 'password':
								case 'hidden':
								case 'file':
								case 'button':
									this.value = v;
								break;
								case 'checkbox':
									if (this.value == v){
										this.checked = true;
									}
								break;
								case 'radio':
									if (this.value == v){
										this.checked = true;
									}else{
										this.checked = false;
									}
								break;
							}
						break;
						case 'textarea':
							this.value = v;
						break;
						case 'select':
							for (var i=0; i < this.options.length; i++ ){
								if (this.options[i].value == v){
									this.options[i].selected = true;
								}else{
									this.options[i].selected = false;
								}
							}
						break;
					}
				},callback);
			}else{
				var value = '';
				if (this.isArray() || typeof elm.nodeName != 'string'){
					if (this.isArray() && elm[0])
					{
						var nodeName = elm[0].nodeName.toLowerCase();
						var nodeType = elm[0].attributes['type'].value.toLowerCase();
						if (nodeName == 'input' && nodeType == 'radio'){
							var name = elm[0].attributes['name'].value.toLowerCase()
							for (var i in elm ){
								if (elm[i].attributes['type'].value.toLowerCase() == 'radio' && elm[i].checked && elm[i].attributes['name'].value.toLowerCase() == name){
									return elm[i].value;
								}
							}
						}
						if (nodeName == 'input' && nodeType == 'checkbox'){
							var value = [];
							for (var i in elm ){
								if (elm[i].attributes['type'].value.toLowerCase() == 'checkbox' && elm[i].checked){
									value[value.length] = elm[i].value;
								}
							}
							return value;
						}
					}
					return '';
				}
				switch (elm.nodeName.toLowerCase()){
					case 'input':
						switch (elm.attributes['type'].value.toLowerCase()){
							case 'text':
							case 'password':
							case 'hidden':
							case 'file':
							case 'button':
								value = elm.value;
							break;
							case 'checkbox':
								if (elm.checked){
									value = elm.value;
								}
							break;
							case 'radio':
								if (elm.checked){
									value = elm.value;
								}
							break;
						}
					break;
					case 'textarea':
						value = elm.value;
					break;
					case 'select':
						for (var i=0; i < elm.options.length; i++ ){
							if (elm.options[i].selected){
								value = elm.options[i].value;
							}
						}
					break;
				}
				return value;
			}
		};
		this.bind_handle = function(obj,evType,fn){
			var ek = false;
			for (var i in Sweetrice.obj_list)
			{
				if (Sweetrice.obj_list[i] == obj && Sweetrice.event_list[i] == evType && Sweetrice.handle_list[i] == fn)
				{
					ek = i;
					break;
				}
			}
			if (!ek)
			{
				ek = Sweetrice.obj_list.length;
				Sweetrice.obj_list[ek] = obj;
				Sweetrice.event_list[ek] = evType;
				if(document.addEventListener && !document.attachEvent) {
					Sweetrice.handle_list[ek] = fn;
				} else  {
					Sweetrice.handle_list[ek] = function(){return fn.apply(obj);};
				}
			}
			
			return ek;
		};
		this.unbind_handle = function(obj,evType,fn){
			var _fn;
			if (fn)
			{
				if(document.addEventListener && !document.attachEvent) {
					_fn = fn;
				}else {
					_fn = function(){return fn.apply(obj);};
				}
				var ek = false;
				for (var i in Sweetrice.obj_list)
				{
					if (Sweetrice.obj_list[i] == obj && Sweetrice.event_list[i] == evType && Sweetrice.handle_list[i].toString() == _fn)
					{
						ek = i;
						break;
					}
				}
				return ek;
			}
			var ek = [];
			for (var i in Sweetrice.obj_list)
			{
				if (Sweetrice.obj_list[i] == obj && Sweetrice.event_list[i] == evType)
				{
					ek.push(i);
				}
			}
			return ek;
		};
		this.bind = function(evType,fn, useCapture,callback){
			if (typeof elm != 'object' || typeof fn != 'function'){
				return ;
			}
			var _this = this;
			return this.each(function(){
				if(document.addEventListener && !document.attachEvent) {
					this.addEventListener(evType, Sweetrice.handle_list[_this.bind_handle(this,evType,fn)], useCapture);
				} else if(this.attachEvent){
					this.attachEvent('on'+evType, Sweetrice.handle_list[_this.bind_handle(this,evType,fn)],useCapture);
				}else{
					this['on' + evType] = Sweetrice.handle_list[_this.bind_handle(this,evType,fn)];
				}
			},callback);
		};

		this.unbind = function(evType,fn, useCapture,callback){
			if (typeof elm != 'object'){
				return ;
			}
			var _this = this;
			return this.each(function(){
				if(document.removeEventListener && !document.attachEvent) {
					if (fn){
						this.removeEventListener(evType, Sweetrice.handle_list[_this.unbind_handle(this,evType,fn)], useCapture);
					}else{
						var ek = _this.unbind_handle(this,evType,fn);
						for (var i in ek ){
							if (!ek[i]){
								continue;
							}
							this.removeEventListener(evType, Sweetrice.handle_list[ek[i]], useCapture);
						}
					}
				} else if (elm.detachEvent) {
					if (fn)
					{
						this.detachEvent('on' + evType, Sweetrice.handle_list[_this.unbind_handle(this,evType,fn)], useCapture);
					}else{
						var ek = _this.unbind_handle(this,evType,fn);
						for (var i in ek ){
							if (!ek[i]){
								continue;
							}
							this.detachEvent('on' + evType, Sweetrice.handle_list[ek[i]], useCapture);
						}
					}
				} else {
					this['on' + evType] = null;
				}
				return this;
			},callback);
		};

		this.refillscreen = function(callback){				
			if (typeof elm != 'object'){
				return ;
			}
			return this.each(function(){
				_(this).css({'height': _.pageSize().pageHeight + 'px','width':_.pageSize().pageWidth + 'px'});
			},callback);
		};

		this.ajaxInit = function(){
			/* Create a new XMLHttpRequest object to talk to the Web server */
			var xmlHttp = null;
			if (typeof XMLHttpRequest != 'undefined') {
				xmlHttp = new XMLHttpRequest();
			}else{
				/*@cc_on @*//*@if (@_jscript_version >= 5)
				try {
					xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try {
						xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e2) {
						xmlHttp = false;
					}
				}
				@end @*/
			}
			return xmlHttp;
		}
		this.ajaxd_response = function(k,fn){
		if (!Sweetrice.ajaxHandle[k]){
			return ;
		}
		var result = new Object();
		if (Sweetrice.ajaxHandle[k].readyState == 4 && Sweetrice.ajaxHandle[k].status == 200) {
			clearTimeout(Sweetrice.xmlHttpTimeout[k]);
			var response = Sweetrice.ajaxHandle[k].responseText;
			if (!response){return false;}
			try {
				result = eval('(' + response + ')');
			} catch (e) {
				result = response;
			}
			Sweetrice.ajaxHandle[k] = null;
			return fn(result);
		}else{			
			return false;
		}
	}

	this.ajaxd_timeout = function(k,fn) {
		if (!Sweetrice.ajaxHandle[k]){
			return ;
		}
		Sweetrice.ajaxHandle[k].abort();
		if (typeof(fn) == 'function'){
			fn();
		}
	}

	this.ajaxd_post = function(query,url,fn,timeout,fnTimeout){
		var k = url;
		if (Sweetrice.ajaxHandle[k]){
			Sweetrice.ajaxHandle[k].abort();
		}
		Sweetrice.ajaxHandle[k] = this.ajaxInit();
		if (url.indexOf('?')==-1){
			url = url + '?timeStamp=' + new Date().getTime();
		}else{
			url = url + '&timeStamp=' + new Date().getTime();
		}
		var _this = this;
		Sweetrice.ajaxHandle[k].open('POST',url,true);
		if (timeout>0){
			Sweetrice.xmlHttpTimeout[k] = setTimeout(function(){_this.ajaxd_timeout(k,fnTimeout);},timeout);
		}
		Sweetrice.ajaxHandle[k].onreadystatechange = function(){_this.ajaxd_response(k,fn);};
		Sweetrice.ajaxHandle[k].setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		Sweetrice.ajaxHandle[k].send(query.substr(1));
	}

	this.ajaxd_get = function(query,url,fn,timeout,fnTimeout){
		var k = url + query;
		if (url.indexOf('?') == -1){
			url += '?timeStamp=' + new Date().getTime() + query;
		}else{
			url += '&timeStamp=' + new Date().getTime() + query;
		}
		if (Sweetrice.ajaxHandle[k]){
			Sweetrice.ajaxHandle[k].abort();
		}
		var _this = this;
		Sweetrice.ajaxHandle[k] = this.ajaxInit();
		Sweetrice.ajaxHandle[k].open('GET', url, true);   
		if (timeout > 0){
			Sweetrice.xmlHttpTimeout[k] = setTimeout(function(){_this.ajaxd_timeout(k,fnTimeout);},timeout);
		}
	  Sweetrice.ajaxHandle[k].onreadystatechange = function(){_this.ajaxd_response(k,fn);};
	  Sweetrice.ajaxHandle[k].send(null);
	}

	this.serializeForm = function(){
		var data = '';
		_this.find('*').each(function(){
			if (!!_(this).attr('name'))
			{
				var submit = false;
				switch (_(this).attr('type'))
				{
					case 'checkbox':
						if (_(this).prop('checked')){
							submit = true;
						}
					break;
					case 'radio':
						if (_(this).prop('checked')){
							submit = true;
						}
					break;
					default:
						submit = true;
				}
				if (submit){
						data += '&'+_(this).attr('name')+'='+_(this).val();
				}
			}
		});
		return data;
	};

	this.ajax = function(param){
		if (!param.timeout){
			param.timeout = 60000;
		}
		if (typeof param.fnTimeout != 'function')
		{
			param.fnTimeout = function(){
				_.dialog({'content':'Script execute time more than 60 seconds and no response from the server,maybe network problem.'});
			};
		}
		var query = '';
		if (param.form)
		{
			query = _(param.form).serializeForm();
			if (!param.type && _(param.form).attr('method')) {
				param.type = _(param.form).attr('method');
			}
			if (!param.url && _(param.form).attr('action')) {
				param.url = _(param.form).attr('action');
			}
		}else{
			var data = param.data;
			for (var i in data ){
				query += '&'+i+'='+escape(data[i]);
			}
		}
		switch (param.type.toUpperCase())
		{
			case 'POST':
				_this.ajaxd_post(query,param.url,param.success,param.timeout,param.fnTimeout);
			break;
			case 'GET':
				_this.ajaxd_get(query,param.url,param.success,param.timeout,param.fnTimeout);
			break;
		}
	};
	this.stopevent = function(event){
		e = event ? event : window.event;
		if(e.preventDefault) {
			e.preventDefault();
			e.stopPropagation();
		} else if(e) {
			e.cancelBubble = true;
			e.returnValue = false;
		}
	};

	this.drag = function(param){
		if (!param){
			var param = new Object();
		}
		return this.each(function(){
			var me = _(this);
			me.unbind('mousedown').bind('mousedown',function(event){
				var event = event || window.event;
				me.attr({'x':event.clientX,'y':event.clientY,'left':parseInt(me.css('left') || me.position().left),'top':parseInt(me.css('top') || me.position().top)});
				if (typeof param.start == 'function')
				{
					param.start(me);
				}
				_(this).stopevent(event);
			}).unbind('touchstart').bind('touchstart',function(event){
				var event = event || window.event;
				me.attr({'x':event.clientX||event.touches[0].pageX,'y':event.clientY||event.touches[0].pageY,'left':parseInt(me.css('left') || me.position().left),'top':parseInt(me.css('top') || me.position().top)});
				if (typeof param.start == 'function')
				{
					param.start(me);
				}
				_(this).stopevent(event);
			}).bind('touchmove',function(event){
				var event = event || window.event;
				if (me.attr('x') > 0)
				{
					var diffX = parseInt(event.clientX||event.touches[0].pageX) - parseInt(me.attr('x')),diffY = parseInt(event.clientY||event.touches[0].pageY) - parseInt(me.attr('y'));
					switch (param.type)
					{
						case 'none':

						break;
						case 'x':
							me.css({left:(parseInt(me.attr('left')) + diffX)+'px'});
						break;
						case 'y':
							me.css({top:(parseInt(me.attr('top')) + diffY)+'px'});
						break;
						default:
							me.css({left:(parseInt(me.attr('left')) + diffX)+'px',top:(parseInt(me.attr('top')) + diffY)+'px'});
					}
					if (typeof param.move == 'function')
					{
						param.move(diffX,diffY,me);
					}
					_(this).stopevent(event);
				}
			}).bind('touchend',function(event){
				me.removeAttr('x');
				me.removeAttr('y');
				me.removeAttr('left');
				me.removeAttr('top');
				if (typeof param.complete == 'function')
				{
					param.complete(me);
				}
			});
			_(document).bind('mousemove',function(event){
				var event = event || window.event;
				if (me.attr('x') > 0)
				{
					var diffX = parseInt(event.clientX||event.touches[0].pageX) - parseInt(me.attr('x')),diffY = parseInt(event.clientY||event.touches[0].pageY) - parseInt(me.attr('y'));
					switch (param.type)
					{
						case 'none':

						break;
						case 'x':
							me.css({left:(parseInt(me.attr('left')) + diffX)+'px'});
						break;
						case 'y':
							me.css({top:(parseInt(me.attr('top')) + diffY)+'px'});
						break;
						default:
							me.css({left:(parseInt(me.attr('left')) + diffX)+'px',top:(parseInt(me.attr('top')) + diffY)+'px'});
					}
					if (typeof param.move == 'function')
					{
						param.move(diffX,diffY,me);
					}
					_(this).stopevent(event);
				}
			});
			_(document).bind('mouseup',function(event){
				me.removeAttr('x');
				me.removeAttr('y');
				me.removeAttr('left');
				me.removeAttr('top');
				if (typeof param.complete == 'function')
				{
					param.complete(me);
				}
			});
		});
	};

	this.hover = function(fn_mouseover,fn_mouseout){
		if (typeof fn_mouseover != 'function')
		{
			fn_mouseover = function(){}
		}
		if (typeof fn_mouseout != 'function')
		{
			fn_mouseout = function(){}
		}
		return this.unbind('mouseover').unbind('mouseut').bind('mouseover',fn_mouseover).bind('mouseout',fn_mouseout);
	};
	

	this.touch = function(param){
		if (!param){
			var param = new Object();
		}
		if (!param.stopevent)
		{
			param.stopevent = {'start':true,'move':true,'end':true,'leave':true,'cancel':true};
		}
		window.savedTouches = [];
		return this.each(function(){
			var me = _(this);
			me.unbind('touchstart').bind('touchstart',function(event){
				var touches = event.changedTouches;
				for (var i=0; i<touches.length; i++) {
					savedTouches[touches[i].identifier] = {pageX:touches[i].pageX,pageY:touches[i].pageY};
					if (typeof param.start == 'function')
					{
						param.start(savedTouches,touches[i].identifier,me);
					}
				}
				if (param.stopevent.start)
				{
					me.stopevent(event);
				}
			}).unbind('touchmove').bind('touchmove',function(event){
				var touches = event.changedTouches;
				for (var i=0; i<touches.length; i++) {
					if (typeof param.move == 'function')
					{
						param.move(savedTouches,touches[i].identifier,touches,i,me);
					}
				}
				if (param.stopevent.move)
				{
					me.stopevent(event);
				}
			}).unbind('touchend').bind('touchend',function(event){
				var touches = event.changedTouches;
				for (var i=0; i<touches.length; i++) {
					if (typeof param.end == 'function')
					{
						param.end(savedTouches,touches[i].identifier,touches,i,me);
					}
					savedTouches[touches[i].identifier] = null;
				}
				if (param.stopevent.end)
				{
					me.stopevent(event);
				}
			}).unbind('touchcancel').bind('touchcancel',function(event){
				var touches = event.changedTouches;
				for (var i=0; i<touches.length; i++) {
					savedTouches[touches[i].identifier] = null;
				}
				if (param.stopevent.end)
				{
					me.stopevent(event);
				}
			});
		});
	};

	this.dialog = function(param,callback){
		var name = param.name || new Date().getTime();
		var w = param.width || 400;
		w = _.pageSize().pageWidth > w ? w:_.pageSize().pageWidth - 50;
		var h = param.height || w*9/16;
		var title = param.title || '';
		var dlgdiv = document.createElement('div');
		dlgdiv.id = 'SweetRice_dialog_'+name;		
		if (_('#'+dlgdiv.id)){
			_('#'+dlgdiv.id).find('.SweetRice_dialog_close').run('click');
		}
		_(document.body).append(dlgdiv);
		_(dlgdiv).html('<div class="SweetRice_menuBar" style="height:20px;cursor:move;padding:10px;background-color: #fafafa;"><div style="overflow:hidden;float:left;">'+title+'</div><div style="clear:both;height:0px;line-height:0px;"></div></div><a title="CLOSE" class="SweetRice_dialog_close" href="javascript:void(0);" style="float:right;width:25px;text-align:center;display:inline;border: 1px solid #ccc;border-radius: 5px;color:#555;text-decoration: none;position:absolute;top:10px;right:10px;">&times;</a><div class="SweetRice_dialog_content" style="padding:10px;">' + (param.content||'') + '</div>'+(param.button?'<div class="SweetRice_dialog_button" style="text-align:right;background-color: #f0f0f0;border-top:1px solid #ccc;"></div>':'')).css({'width':w+'px','min-height':param.height?h+'px':'auto','position':'absolute','top':(_.scrollSize().top+(_.pageSize().windowHeight > param.height?(_.pageSize().windowHeight-param.height)/2:20))+'px','left':(_.pageSize().pageWidth-w)/2+'px','border':'1px solid #ccc','border-radius':'5px','background-color':'#fff','z-index':65535,'box-shadow':'0 0 15px 0px rgba(0, 0, 0, 0.35)'});
		if (param.button){
			var btn_str = '',btn;
			for (var i in param.button){
				btn = document.createElement('input');
				btn.type = 'button';
				btn.value = param.button[i].label;
				_(dlgdiv).find('.SweetRice_dialog_button').append(btn);
				_(btn).css({'margin':'3px'});
				var bind = param.button[i].bind;
				if (bind){
					for (j in bind ){
						_(btn).bind(j,bind[j]);
					}
				}
			}
		}
		
		if (param.layer)
		{
			var layerdiv = document.createElement('div');
			layerdiv.id = 'SweetRice_layer_dialog';
			_(document.body).append(layerdiv);
			_(layerdiv).css({'background-color': '#000000','left':0,'opacity': 0.3,'position':'absolute','top': 0,'z-index':65534}).refillscreen();
			_(window).bind('resize', function(){_(layerdiv).refillscreen();});
		}
		_(window).bind('resize', function(){
			var new_w = _.pageSize().pageWidth > w ? w:_.pageSize().pageWidth - 50;
			var h = _.pageSize().windowHeight > _(dlgdiv).height() ? _(dlgdiv).height() : _.pageSize().windowHeight - 50;
			_(dlgdiv).css({'width':new_w+'px','top':(_.scrollSize().top+(_.pageSize().windowHeight - h)/2)+'px','left':((_.pageSize().pageWidth - new_w)/2)+'px'});
		});
		_(dlgdiv).find('.SweetRice_dialog_close').bind('click',function(){
			_(dlgdiv).remove();
			if (param.layer){
				_(layerdiv).remove();
			}
			if (param.close && typeof param.close == 'function'){
				param.close();
			}
		});

		_(dlgdiv).find('.SweetRice_menuBar').drag({
		type:'none',
		'start':function(){
			_(dlgdiv).attr({'left':parseInt(_(dlgdiv).position().left),'top':parseInt(_(dlgdiv).position().top)});
		},
		'move':function(diffX,diffY){
			var ll = parseInt(_(dlgdiv).attr('left')) + diffX;
			var lt = parseInt(_(dlgdiv).attr('top')) + diffY;
			if (lt > 0){
				_(dlgdiv).css({'left':ll+'px','top':lt + 'px'});
			}
		},
		'complete':function(){
			_(dlgdiv).removeAttr('left');
			_(dlgdiv).removeAttr('top');
		}
		});
		if (param.url){
			_(dlgdiv).find('.SweetRice_dialog_content').load(param.url,function(){
				if (param.layer)
				{
					_(layerdiv).refillscreen();
				}
				if (typeof callback == 'function')
				{
					callback();
				}
			});
		}else	if (typeof callback == 'function'){
			callback.apply(dlgdiv);
		}
		return _(dlgdiv);
	};
	this.setCookie = function(param){
		if (!param.name){
			return ;
		}
		var i = param.expired || 30*3600*1000*24;
		var date = new Date();
		date.setTime(date.getTime()+i);
		var str = '';
		str += param.name+'='+param.value;
		str += ';expires='+date.toGMTString();
		document.cookie = str;
	};
	this.getCookie = function(k){
		var i,x,y,a = document.cookie.split(';');
		for (i=0;i<a.length;i++){
			x = a[i].substr(0,a[i].indexOf('='));
			y = a[i].substr(a[i].indexOf('=')+1);
			x = x.replace(/^\s+|\s+$/g,'');
			if (x == k){
				return unescape(y);
			}
		}
		return '';
	};
	this.delCookie = function(param){
		if (!param.name){
			return ;
		}
		if (!param.path)
		{
			param.path = '/';
		}
		var i = -1;
		var date = new Date();
		date.setTime(date.getTime()+i);
		var str = '';
		str += param.name+'='+_.getCookie(param.name);
		str += ';expires='+date.toGMTString()+';path='+param.path;
		document.cookie = str;
	};
	this.setStorage = function(k,v){
		if(!window.localStorage || !k){
			return ;
		}
		localStorage.setItem(k,v);
	}

	this.getStorage = function(k){
		if(!window.localStorage || !k){
			return ;
		}
		return localStorage.getItem(k) || false;
	}

	this.items = function(){
		return elm;
	}
	this.size = function(){
		var notEmpty = false;
		for (var i in elm )
		{
			notEmpty = true;
			break;
		}
		if (notEmpty)
		{
			if (this.isArray())
			{
				return elm.length;
			}else{
				return 1;
			}
		}else{
			return 0;
		}
	}

	this.ajax_untip = function(str,timeout,callback){
		var aut_dlg = _().dialog({'content':str,'name':'ajax_untip'});
		setTimeout(function(){
			if (aut_dlg){
				aut_dlg.remove();
			}
			if (typeof callback == 'function')
			{
				callback();
			}
		},timeout >= 0 ? timeout:2000);
	};

	this.randomColor = function( max ,toggle) {  
		if (!max)
		{
			max = 0xFF;
		}
		var color = '';
		var rand = 0;
		for (var i=0;i<3 ;i++ )
		{
			rand = Math.floor( toggle ? 0xFF  - Math.random( ) * max : Math.random( ) * max ).toString(16)
			color += (rand.length == 1 ? 0:'') + rand;
		}
		return '#'+color;
	};

	this.fromColor = function ( color ) { 
		color = color.substring(1);
		if (!color)
		{
			return '';
		}
		var newcolor = '';
		for (var i = 0;i < 6 ;i++ )
		{
			newcolor += (0xFF - parseInt(color[i]+color[i+1],16)).toString(16);
			i++ ;
		}
		return '#'+newcolor ;
	}

	var events = ['click','change','focus','blur','dblclick','mousedown','mouseup','mouseover','mouseout','mousemove','keypress','keydown','keyup','abort','beforeonload','beforeunload','error','load','move','resize','scroll','stop','unload','reset','submit','bounce','finish','start','beforecopy','beforecut','beforeeditfocus','beforepaste','beforeupdate','contextmenu','copy','cut','drag','dragdrop','dragend','dragenter','dragleave','dragover','dragstart','drop','losecapture','paste','select','selectstart','afterupdate','cellchange','dataavailable','datasetchanged','datasetcomplete','errorupdate','rowenter','rowexit','rowsdelete','rowsinserted','beforeprint','filterchange','help','propertychange','readystatechange','message','wheel','offline','online','pagehide','pageshow','popstate','storage','input','invalid','search','canplay','canplaythrough','cuechange','durationchange','emptied','ended','loadeddata','loadedmetadata','loadstart','pause','play','playing','progress','ratechange','seeked','seeking','stalled','suspend','timeupdate','volumechange','waiting','show','toggle'];
	for (var i in events )
	{
		(function(name){
			if (typeof this[name] == 'function')
			{
				var name_fn = '_'+name;
			}else{
				var name_fn = name;
			}
			this[name_fn] = function(fn){
				if (typeof fn == 'function')
				{
					return this.unbind(name).bind(name,fn);
				}else{
					return this.run(name);
				}
				return this;
			};
		}).call(this,events[i]);
	}
	return this;
}

	
	var Sweetrice = function(elm){
		return new SweetRice( elm );
	};
	Sweetrice.event_list = [];
	Sweetrice.handle_list = [];
	Sweetrice.obj_list = [];
	Sweetrice.xmlHttpTimeout = [];
	Sweetrice.ajaxHandle = [];
	Sweetrice.fade_handle = [];
	Sweetrice.animate_handle = [];
	var _list = ['ajax','ajax_untip','getCookie','setCookie','delCookie','getStorage','setStorage','pageSize','scrollSize','ready','dialog','stopevent','randomColor','fromColor'];
	for (var i in _list){
		eval('Sweetrice.'+_list[i]+' = Sweetrice().'+_list[i]+';');
	}

	window._ = Sweetrice;
	return Sweetrice;
})(window);

//-->