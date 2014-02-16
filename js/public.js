/**
 * SweetRice javascript function.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
<!--
	function $(selector){
		if ( !selector ) {
			return false;
		}
		var s1 = selector.substring(0,1);
		if (s1 == '.'){
			selector = selector.substring(1,selector.length);
			var a=[],b = document.getElementsByTagName('*');
			for (var i=0; i< b.length; i++ ) {
				if (b[i].className.search(new RegExp("\\b" + selector + "\\b")) != -1) {
					a[a.length] = b[i];
				}
			}	
			if (Object.prototype.toString.call( a ) === '[object Array]' && a.length == 1){
				return a[0];
			}else{
				return a;
			}
		}else{
			if (s1=='#'){
				selector = selector.substring(1,selector.length);
			}
			var b = document.getElementsByTagName('*');
			for (var i=0; i< b.length; i++ ) {
				if (b[i].id == selector) {
					return b[i];
				}
			}
		}
		return false;
	}
	(function( window ) {
		var Sweetrice = function(elm){
			return new SweetRice( elm );
		};
		Sweetrice.event_list = [];
		Sweetrice.handle_list = [];
		Sweetrice.obj_list = [];
		Sweetrice.xmlHttpTimeout = [];
		Sweetrice.ajaxHandle = [];
		Sweetrice.fadeOut_data = [];
		Sweetrice.fadeIn_data = [];
		Sweetrice.animate_data = [];
		function SweetRice(elm){
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
					for (var ii in _b)
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
							a.push(this.getNode(elms[i]));
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
				if(self.pageYOffset){
					yScroll = self.pageYOffset;
					xScroll = self.pageXOffset;
				}else if(document.documentElement&&document.documentElement.scrollTop){
					yScroll = document.documentElement.scrollTop;
					xScroll = document.documentElement.scrollLeft;
				}else if(document.body){
					yScroll = document.body.scrollTop;
					xScroll = document.body.scrollLeft;
				}
				return {'left':xScroll,'top':yScroll};
			};
			this.ready = function(fn){
				if(document.addEventListener){
						document.addEventListener("DOMContentLoaded",function(){
							document.removeEventListener("DOMContentLoaded",arguments.callee,false);  
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
				if (typeof(v) == 'string'){
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
					_(this).css('display','block');
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
					_(this).css('display','none');
				},callback);
			};


			this.text = function(v,callback){
				if (typeof elm != 'object'){
					return ;
				}
				if (typeof(v) == 'string'){
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
							return elm.attributes[i].nodeValue;
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

			this.animate =  function( prop, speed, callback ,animate_fn,animate_complate){				
				if (!speed){
					speed = 500;
				}
				if (!animate_fn){
					animate_fn = function(obj){
						var css = [],pos,cv,ev,add,pix;
						for (var i in prop ){
							if (!_(obj).attr('_'+i)){
								_(obj).attr('_'+i,parseFloat(_(obj).css(i)));
							}
							cv = parseFloat(_(obj).attr('_'+i));
							ev = parseFloat(prop[i]);
							add = ev - cv;
							pix = prop[i].toString().replace(/[0-9\.\-]+/,'');
							pos = parseFloat(cv + add * parseFloat(_this.diff/speed));		
							css[i] = pos + pix;		
						}
						return css;
					};
					animate_complate = function(obj){
						for (var i in prop ){
							_(obj).removeAttr('_'+i);
						}
					};
				}
				var handle = Sweetrice.animate_data.length;
				Sweetrice.animate_data[handle] = [];
				Sweetrice.animate_data[handle]['timer'] = setInterval(function(){
					if (!_this.start_time){
						_this.start_time = new Date().getTime();
					}
					_this.diff = new Date().getTime() - _this.start_time;
					if (_this.diff > speed){
						window.clearInterval(Sweetrice.animate_data[handle]['timer']);
						_this.each(function(){
							_(this).css(prop);
							if (typeof animate_complate == 'function'){
								animate_complate(this);
							}
						});
						if (typeof callback == 'function'){
							callback.apply(elm);
						}
					}else{
						_this.each(function(){
							_(this).css(animate_fn(this));
						});
					}
				},13);
				return this;
			};
			
			this.fadeIn = function(speed, callback){
				if (!speed){
					speed = 500;
				}
				this.show();
				var handle = Sweetrice.fadeIn_data.length;
				Sweetrice.fadeIn_data[handle] = [];
				Sweetrice.fadeIn_data[handle]['timer'] = setInterval(function(){
					if (!_this.start_time){
						_this.start_time = new Date().getTime();
					}
					_this.diff = new Date().getTime() - _this.start_time;
					if (_this.diff > speed){
						_this.each(function(){
							_(this).css({'opacity':1,'filter':'alpha(opacity=100)'});
						});
						window.clearInterval(Sweetrice.fadeIn_data[handle]['timer']);
						if (typeof callback == 'function'){
							callback.apply(elm);
						}
					}else{
						_this.each(function(){
							var cv = parseFloat(_(this).css('opacity'))||0;
							var pos = cv + (1 - cv)*parseFloat(_this.diff/speed);
							_(this).css({'opacity':pos,'filter':'alpha(opacity='+parseInt(pos*100)+')'});
						});
					}
				},13);
				return this;
			};


			this.fadeOut = function(speed, callback){
				if (!speed){
					speed = 500;
				}
				var handle = Sweetrice.fadeOut_data.length;
				Sweetrice.fadeOut_data[handle] = [];
				Sweetrice.fadeOut_data[handle]['timer'] = setInterval(function(){
					if (!_this.start_time){
						_this.start_time = new Date().getTime();
					}
					_this.diff = parseInt(new Date().getTime() - _this.start_time);
					if (_this.diff > speed){
						_this.each(function(){
							_(this).css({'opacity':0,'filter':'alpha(opacity=0)'}).hide();
						});
						window.clearInterval(Sweetrice.fadeOut_data[handle]['timer']);
						if (typeof callback == 'function'){
							callback.apply(elm);
						}
					}else{
						_this.each(function(){
							var cv = parseFloat(_(this).css('opacity')) || 1;
							var pos = cv*(1 - parseFloat(_this.diff/speed));
							_(this).css({'filter':'alpha(opacity='+pos*100+')','opacity':pos});
						});
					}
				},13);
				return this;
			};

			
			this.removeAttr = function(k,callback){ 
				if (typeof elm != 'object' || !k){
					return ;
				}
				return this.each(function(){
					this.attributes.removeNamedItem(k);
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

			this.load = function(url,callback){
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
								switch (this.attributes['type'].nodeValue.toLowerCase())
								{
									case 'text':case 'password':case 'hidden':
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
						if (this.isArray())
						{
							var nodeName = elm[0].nodeName.toLowerCase();
							var nodeType = elm[0].attributes['type'].nodeValue.toLowerCase();
							if (nodeName == 'input' && nodeType == 'radio'){
								var name = elm[0].attributes['name'].nodeValue.toLowerCase()
								for (var i in elm ){
									if (elm[i].attributes['type'].nodeValue.toLowerCase() == 'radio' && elm[i].checked && elm[i].attributes['name'].nodeValue.toLowerCase() == name){
										return elm[i].value;
									}
								}
							}
							if (nodeName == 'input' && nodeType == 'checkbox'){
								var value = [];
								for (var i in elm ){
									if (elm[i].attributes['type'].nodeValue.toLowerCase() == 'checkbox' && elm[i].checked){
										value[value.length] = elm[i].value;
									}
								}
								return value;
							}
						}
						return ;
					}
					switch (elm.nodeName.toLowerCase()){
						case 'input':
							switch (elm.attributes['type'].nodeValue.toLowerCase()){
								case 'text':case 'password':case 'hidden':case 'file':
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
			Sweetrice.ajaxHandle[k].abort();
			if (typeof(fn) == 'function'){
				fn();
			}
	}

	this.ajaxd_post = function(query,url,fn,timeout,fnTimeout){
		var str = '',k='';
		for (var i in query ){
			str += '&'+i+'='+escape(query[i]);
		}
		k = url;
		if (Sweetrice.ajaxHandle[k]){
			Sweetrice.ajaxHandle[k].abort();
		}
		Sweetrice.ajaxHandle[k] = this.ajaxInit();
		if (url.indexOf('?')==-1){
			url = url + '?timeStamp=' + new Date().getTime();
		}else{
			url = url + '&timeStamp=' + new Date().getTime();
		}
		Sweetrice.ajaxHandle[k].open("POST",url,true);
		if (timeout>0){
			Sweetrice.xmlHttpTimeout[k] = setTimeout(function(){ajaxd_timeout(k,fnTimeout);},timeout);
		}
		var _this = this;
		Sweetrice.ajaxHandle[k].onreadystatechange = function(){_this.ajaxd_response(k,fn);};
		Sweetrice.ajaxHandle[k].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		Sweetrice.ajaxHandle[k].send(str.substr(1));
	}

	this.ajaxd_get = function(query,url,fn,timeout,fnTimeout){
		var str = '',k='';
		for (var i in query ){
			str += '&'+i+'='+escape(query[i]);
		}
		k = url+str;
		if (url.indexOf('?') == -1){
			url += '?timeStamp=' + new Date().getTime() + str;
		}else{
			url += '&timeStamp=' + new Date().getTime() + str;
		}
		if (Sweetrice.ajaxHandle[k]){
			Sweetrice.ajaxHandle[k].abort();
		}
		Sweetrice.ajaxHandle[k] = this.ajaxInit();
		Sweetrice.ajaxHandle[k].open("GET", url, true);   
		if (timeout > 0){
			Sweetrice.xmlHttpTimeout[k] = setTimeout(function(){this.ajaxd_timeout(k,fnTimeout);},timeout);
		}
		var _this = this;
	  Sweetrice.ajaxHandle[k].onreadystatechange = function(){_this.ajaxd_response(k,fn);};
	  Sweetrice.ajaxHandle[k].send(null);
	}

	this.ajax = function(param){
		switch (param.type.toUpperCase())
		{
			case 'POST':
				_this.ajaxd_post(param.data,param.url,param.success,param.timeout,param.fnTimeout);
			break;
			case 'GET':
				_this.ajaxd_get(param.data,param.url,param.success,param.timeout,param.fnTimeout);
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
	this.moveto = function(event,t,obj,me){
		event = event ? event : window.event;
		switch (t)
		{
		case 1:
			me.attr({'x':event.clientX,'y':event.clientY});
			_(obj).attr({'left':parseInt(_(obj).position().left),'top':parseInt(_(obj).position().top)});
			this.stopevent(event);
		break;
		case 2:
			if (me.attr('x') > 0)
			{
				var ll = parseInt(_(obj).attr('left')) + parseInt(event.clientX) - parseInt(me.attr('x'));
				var lt = parseInt(_(obj).attr('top')) + parseInt(event.clientY) - parseInt(me.attr('y'));
				_(obj).css({'left':ll+'px','top':lt + 'px'});
				this.stopevent(event);
			}
		break;
		case 3:
			me.attr({'x':0,'y':0});
			this.stopevent(event);
		break;
		}
	};
	this.dialog = function(param,callback){
		var name = param.name || new Date().getTime();
		var w = param.width || 400;
		var h = param.height || w*9/16;
		var title = param.title || '';
		if (param.layer)
		{
			var layerdiv = document.createElement("div");
			layerdiv.id = 'SweetRice_layer_dialog';
			_(document.body).append(layerdiv);
			_(layerdiv).css({'background-color': '#000000','left':0,'opacity': 0.3,'position':'absolute','top': 0,'z-index':65535}).refillscreen();
			_(window).bind('resize', function(){_(layerdiv).refillscreen();});
		}
		var dlgdiv = document.createElement("div");
		dlgdiv.id = 'SweetRice_dialog_'+name;		
		if (_('#'+dlgdiv.id)){
			_('#'+dlgdiv.id).find('.SweetRice_dialog_close').run('click');
		}
		_(document.body).append(dlgdiv);
		_(dlgdiv).html('<div class="SweetRice_menuBar" style="padding:5px;cursor:move;padding:10px;background-color: #f0f0f0;"><div style="width:'+(w-50)+'px;overflow:hidden;float:left;">'+title+'</div><a title="CLOSE" class="SweetRice_dialog_close" href="javascript:void(0);" style="float:right;width:30px;text-align:right;display:inline;">X</a><div style="clear:both;height:0px;line-height:0px;"></div></div><div class="SweetRice_dialog_content" style="padding:10px;border-top:1px solid #ccc;">' + (param.content||'') + '</div>'+(param.button?'<div class="SweetRice_dialog_button" style="text-align:right;background-color: #f0f0f0;border-top:1px solid #ccc;"></div>':'')).css({'width':w+'px','position':'absolute','top':(_.scrollSize().top+(_.pageSize().windowHeight-h)/2)+'px','left':(_.pageSize().pageWidth-w)/2+'px','border':'1px solid #ccc','border-radius':'5px','background-color':'#fff','z-index':65535,'box-shadow':'0 0 5px 2px rgba(0, 0, 0, 0.35);'});
		if (param.button){
			var btn_str = '',btn;
			for (var i in param.button){
				btn = document.createElement("input");
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
		_(window).bind('resize', function(){
			_(dlgdiv).css({'top':(_.scrollSize().top+(_.pageSize().windowHeight-parseInt(_(dlgdiv).css('height')))/2)+'px','left':(_.pageSize().pageWidth-parseInt(_(dlgdiv).css('width')))/2+'px'});
		});
		_(dlgdiv).find('.SweetRice_dialog_close').bind('click',function(){
			_(dlgdiv).remove();
			if (param.layer){
				_(layerdiv).remove();
			}
			_(dlgdiv).find('.SweetRice_menuBar').unbind('mousedown');
			_(document).unbind('mousemove').unbind('mouseup');
			if (param.close && typeof param.close == 'function'){
				param.close();
			}
		});
		var atobj = _(dlgdiv).find('.SweetRice_menuBar');
		atobj.bind('mousedown',function(event){_(this).moveto(event,1,dlgdiv,atobj);});
		_(document).bind('mouseup',function(event){_(this).moveto(event,3,dlgdiv,atobj);}).bind('mousemove',function(event) {_(this).moveto(event,2,dlgdiv,atobj);});
		if (param.url){
			_(dlgdiv).find('.SweetRice_dialog_content').load(param.url,typeof callback == 'function'?function(){callback()}:null);
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
		var i,x,y,a = document.cookie.split(";");
		for (i=0;i<a.length;i++){
			x = a[i].substr(0,a[i].indexOf("="));
			y = a[i].substr(a[i].indexOf("=")+1);
			x = x.replace(/^\s+|\s+$/g,"");
			if (x == k){
				return unescape(y);
			}
		}
		return '';
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
	this.ajax_untip = function(str){
		var aut_dlg = _().dialog({'content':str,'name':'ajax_untip'});
		setTimeout(function(){
			if (aut_dlg){
				aut_dlg.remove();
			}
		},2000);
	};

	}
	var _list = ['ajax','ajax_untip','getCookie','setCookie','getStorage','setStorage','pageSize','scrollSize','ready','dialog','stopevent'];
	for (var i in _list){
		eval('Sweetrice.'+_list[i]+' = Sweetrice().'+_list[i]+';');
	}
	if (!window.getComputedStyle) {
		window.getComputedStyle = function(el, pseudo) {
			this.el = el;
			this.getPropertyValue = function(prop) {
				var re = /(\-([a-z]){1})/g;
				if (prop == 'float') prop = 'styleFloat';
				if (re.test(prop)) {
					prop = prop.replace(re, function () {
						return arguments[2].toUpperCase();
					});
				}
				return el.currentStyle[prop] || null;
			}
			return this;
		}
	}else{
		getComputedStyle = document.defaultView && document.defaultView.getComputedStyle;
	}
	window._ = Sweetrice;
})(window);

//-->