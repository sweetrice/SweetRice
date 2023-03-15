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
<link href="<?php echo APP_HOME; ?>css/dashboard.css" rel="stylesheet" type="text/css" media="screen" />

<form method="post" id="menu_form" action="<?php echo pluginDashboardUrl(THIS_APP, array('app_mode' => 'menu', 'mode' => 'save')); ?>">
<input type="hidden" name="parent_id" value="<?php echo $id; ?>"/>
<ul class="menu_list" id="menu_list">
<?php
foreach ($data['rows'] as $key => $val) {
    ?>
<li class="list_item" draggable="true">
<span class="media_content"> <?php _e('Link Text');?></span>
<input type="text" value="<?php echo $val['link_text']; ?>" class="link_text mw120" name="link_text[<?php echo $key; ?>]" title="<?php echo $val['link_url']; ?>"/>
<span class="media_content"> <?php _e('Link URL');?>
<input type="text" value="<?php echo $val['link_url']; ?>" name="link_url[<?php echo $key; ?>]" class="link_url input_text"/></span><input type="hidden" name="ids[<?php echo $key; ?>]" value="<?php echo $val['id']; ?>"/><input type="hidden" name="order[<?php echo $key; ?>]" value="<?php echo $key; ?>" class="list_order"/>
<input type="button" value="-" class="btn_remove"> <input type="button" value="<?php _e('Sitemap');?>" class="btn_sitemap"> <a href="<?php echo pluginDashboardUrl(THIS_APP, array('app_mode' => 'menu', 'id' => $val['id'])); ?>"><?php _e('Sub');?></a>

<div class="div_clear mb10"></div>
</li>
<?php
}
?>
</ul>
<input type="hidden" id="total_key" value="<?php echo $key; ?>"/>
<div class="menu_btns"><input type="button" value="+" class="btn_add"></div>
<input type="submit" value="<?php _e('Done');?>" class="btn_submit">
<input type="button" value="<?php _e('Back');?>" class="back" url="<?php echo pluginDashboardUrl(THIS_APP, array('app_mode' => 'menu', 'id' => $row['parent_id'])); ?>">
</form>
<script type="text/javascript">
<!--

  function _index(item) {
    var index = 0;
    if (!item || !item.parentNode) {
        return -1;
    }
    while (item && (item = item.previousElementSibling)) {
        index++;
    }
    return index;
  }

  function init_reorder(){
    var node = _("#menu_list").items();
    var draging = null;
    node.ondragstart = function(event) {
      if (_(event.target).hasClass('list_item')) {
        draging = event.target;
      }else{
        draging = _(event.target).parent().items();
      }
    }
    node.ondragover = function(event) {
        event.preventDefault();
        var target = event.target;
        var this_parent = _(target).parent().items(),curr_target = null;
        if (_(this_parent).hasClass('list_item')) {
          curr_target = this_parent;
        }else if(_(target).hasClass('list_item')){
          curr_target = target;
        }
        if (!!curr_target && curr_target !== draging) {
          if (_index(draging) < _index(curr_target)) {
              curr_target.parentNode.insertBefore(draging, curr_target.nextSibling);
          } else {
              curr_target.parentNode.insertBefore(draging, curr_target);
          }
        }
    }
  }
  var curr_li;
  _.ready(function(){
      init_reorder();
    _('.btn_add').bind('click',function(){
      var total_key = parseInt(_('#total_key').val()) + 1;
      _('#total_key').val(total_key);
      var li = document.createElement('li');
      _(li).addClass('list_item').attr({'draggable':'true'}).html('<span class="media_content"> <?php _e('Link Text');?></span> <input type="text" class="link_text mw120" name="link_text['+total_key+']"/><span class="media_content"> <?php _e('Link URL');?> <input type="text" class="link_url input_text" name="link_url['+total_key+']"/></span><input type="hidden" name="order['+total_key+']" value="'+total_key+'" class="list_order"/> <input type="button" value="-" class="btn_remove"> <input type="button" value="<?php _e('Sitemap');?>" class="btn_sitemap">');
      _('.menu_list').append(li);
    });

  });

//-->
</script>