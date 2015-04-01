<?php
/**
 * Database example management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.4.2
 */
 defined('VALID_INCLUDE') or die();
?>
<link href="<?php echo APP_HOME;?>css/dashboard.css" rel="stylesheet" type="text/css" media="screen" />

<form method="post" id="menu_form" action="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'menu','mode'=>'save'));?>">
<input type="hidden" name="parent_id" value="<?php echo $id;?>"/>
<ul class="menu_list">
<?php
	foreach($data['rows'] AS $key=>$val ){
?>
<li><input type="button" value=" " class="btn_move"> <?php _e('Link Text');?> <input type="text" value="<?php echo $val['link_text'];?>" class="link_text" name="link_text[<?php echo $key;?>]"/> 
<?php _e('Link URL');?> <input type="text" value="<?php echo $val['link_url'];?>" name="link_url[<?php echo $key;?>]" class="link_url input_text"/><input type="hidden" name="ids[<?php echo $key;?>]" value="<?php echo $val['id'];?>"/><input type="hidden" name="order[<?php echo $key;?>]" value="<?php echo $key;?>" class="list_order"/> <input type="button" value="-" class="btn_remove"> <input type="button" value="<?php _e('Sitemap');?>" class="btn_sitemap"> <a href="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'menu','id'=>$val['id']));?>"><?php _e('Child menus');?></a></li>
<?php
	}
?>
</ul>
<input type="hidden" id="total_key" value="<?php echo $key;?>"/>
<div class="menu_btns"><input type="button" value="+" class="btn_add"> <input type="button" value="<?php _e('Start dragging');?>" class="btn_reorder" runing="0"></div>
<input type="submit" value="<?php _e('Done');?>" class="btn_submit">
<input type="button" value="<?php _e('Back');?>" class="back" url="<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'menu','id'=>$row['parent_id']));?>">
</form>
<script type="text/javascript">
<!--
	var curr_li;
	_().ready(function(){
		init_menu();
		_('.btn_add').bind('click',function(){
			var total_key = parseInt(_('#total_key').val()) + 1;
			_('#total_key').val(total_key);
			var li = document.createElement('li');
			_(li).html('<input type="button" value=" " class="btn_move"> <?php _e('Link Text');?> <input type="text"  class="link_text" name="link_text['+total_key+']"/> <?php _e('Link URL');?> <input type="text" class="link_url input_text" name="link_url['+total_key+']"/><input type="hidden" name="order['+total_key+']" value="'+total_key+'" class="list_order"/> <input type="button" value="-" class="btn_remove"> <input type="button" value="<?php _e('Sitemap');?>" class="btn_sitemap">');
			_('.menu_list').append(li);
			init_menu();
			
			if (_('.btn_reorder').attr('runing') == 1)
			{
				_('.menu_list li').unbind('mousedown');
				_('.btn_reorder').attr('runing',0).val('<?php _e('Start dragging');?>');
				_('.menu_list li').removeClass('draging');
				return ;
			}
		});

	_('.btn_reorder').bind('click',function(){
		if (_(this).attr('runing') == 1)
		{
			_('.menu_list li').unbind('mousedown');
			_(this).attr('runing',0).val('<?php _e('Start dragging');?>');
			_('.menu_list li').removeClass('draging');
			return ;
		}
		_(this).attr('runing',1).val('<?php _e('Complete dragging');?>');
		_('.menu_list li').addClass('draging');
		_('.menu_list li').drag({
				type:'y',
				'start':function(obj){
					obj.attr('draging',1);
					curr_li = obj;
				},
				'move':function(diffX,diffY,obj){
					var top = parseInt(obj.css('top'));
					_('.menu_list li').each(function(){
						if (_(this).attr('draging') == 1 || _(this).attr('id') == 'tmp_li')
						{
							return ;
						}
						var tmp_top = parseInt(_(this).css('top'));
						if ((top - tmp_top < 15 && top > tmp_top) || (top < tmp_top && top - tmp_top > -15))
						{
							_('#tmp_li').remove();
							var tmp_li = document.createElement('li');
							if (top > tmp_top)
							{
								_(this).appendAfter(tmp_li);
							}else{
								_(this).appendBefore(tmp_li);
							}
							_(tmp_li).attr({'id':'tmp_li'}).css({'border':'1px dotted #000','height':'28px','width':obj.width()+'px'});
							var menu_order = 0;
							_('.menu_list li').each(function(){
								if ( _(this).attr('draging') != 1)
								{
									_(this).css({'top':(menu_order * 30)+'px'});
									menu_order += 1;
								}
							});
						}
					});
				},
				'complete':function(obj){
					if (!curr_li){
						return ;
					}
					obj.removeAttr('draging');
					var tmp_obj = curr_li.items();
					_('#tmp_li').appendBefore(tmp_obj).remove();
					init_menu_list();
				}
			});
		});
	});

	function init_menu(){
		init_menu_list();
		_('.btn_remove').unbind('click').bind('click',function(){
			_(this).parent().remove();
			init_menu_list();
		});
		_('.btn_sitemap').unbind('click').bind('click',function(){
			var _this = this;
			_.ajax({
				'type':'get',
				'url':'<?php echo pluginDashboardUrl(THIS_APP,array('app_mode'=>'menu','mode'=>'sitemap'));?>',
				'success':function(result){
					if (result['status'] == 1)
					{
						var sitemap = result['data'],tmp_html = '';
						for (var i in sitemap )
						{
							tmp_html += '<div class="attach_sitemap"><input type="radio" class="btn_attach" value="<?php _e('Use it');?>"> <a href="<?php echo BASE_URL;?>'+sitemap[i]['url']+'" target="_blank">'+sitemap[i]['link_body']+'</a></div>';
						}
						_.dialog({'title':'<?php _e('Sitemap');?>',content:tmp_html,'width':'800'},function(){
							var dobj = this;
							_('.btn_attach').unbind('click').bind('click',function(){
								var link_text = _(this).next().html(),link_url = _(this).next().attr('href');
								_(_this).parent().find('.link_text').val(link_text);
								_(_this).parent().find('.link_url').val(link_url);
								_(dobj).find('.SweetRice_dialog_close').run('click');
							});
						});
					}
				}
			});
		});
		
	}

	function init_menu_list(){
		var menu_order = 0;
		_('.menu_list li').each(function(){
			_(this).css({'top':(menu_order * 30)+'px'});
			_(this).find('.list_order').val(menu_order);
			menu_order += 1;
		});
		_('.menu_list').height((menu_order * 30)+'px');
	}
//-->
</script>