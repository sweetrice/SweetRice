<?php
/**
 * Tinymce for SweetRice template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.6.4
 */
 defined('VALID_INCLUDE') or die();
?>
<input type="hidden" id="tmp_media" />
<script type="text/javascript" src="../_plugin/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
<!--
	function clean_body(content){
		content = content.replace(/<!\-*[^>]+\-*>/g,'');
		content = content.replace(/<style>.+?<\/style>/ig,"");
  		content = content.replace(/style="[^"]+"/ig,'');
		content = content.replace(/&nbsp;/ig,'');
		content = content.replace(/<p><br><\/p>/g,'');
		content = content.replace(/<br\s*\/?>/ig, '');
		content = content.replace(/'/g,'"');
  		content = content.replace(/\s{2,}/g,' ');
		var imgs = content.match(/(<img.+?src="([^"]+?)">)/g),img = '';
		if (!!imgs && imgs.length > 0) {
			for(var i in imgs){
				img = imgs[i].match(/src="(.+?)"/)[1];
				if (/https?:\/\/.+/.test(img) || /data:image.+/.test(img) ) {
					continue ;
				}
				content = content.replace(imgs[i],'<p><img src="'+img+'"></p>');
			}
		}
		setTimeout(function(){
			toLocalImage()
		},1000)
		content = content.replace(/<[a-z]+>\s*<\/[a-z]+>/ig,'');
		content = content.replace(/<p>&nbsp;<\/p>/ig,'');
		content = content.replace(/<p><\/p>/ig,'');
		return content; 
	}
	function getImageBase64(img, ext) {
	    var canvas = document.createElement("canvas");
	    canvas.width = img.width;
	    canvas.height = img.height;
	    var ctx = canvas.getContext("2d");
	    ctx.drawImage(img, 0, 0, img.width, img.height);
	    var dataURL = canvas.toDataURL("image/" + ext);
	    canvas = null;
	    return dataURL;
	}
	function checkLocalImage(){
	    var tmp_content = tinyMCE.activeEditor.getContent();
	    var content_imgs = tmp_content.match(/src="(?!https?:\/\/<?php echo str_replace('.', '\.', $_SERVER['HTTP_HOST']);?>)[^\"]+"/g),imgs = [],tmp_img = '';
	    if (!content_imgs) {return [];}
	    for(i in content_imgs){
	      tmp_img = content_imgs[i].replace('src="','').replace('"','');
	      if (!!tmp_img && tmp_img.indexOf('data:') > -1 || tmp_img.indexOf('blob:') > -1) {
	        imgs.push(tmp_img)
	      }
	    }
	    return imgs;
	}


  function uploadArticleImage(){
    var localImage = checkLocalImage();
    if (localImage.length == 0) {
      _('.form-progress-wrap').html(_('.form-progress-wrap').html()+'<div class="form-progress"><?php _e('Image Uploaded');?></div>');
      return ;
    }
    var filedata = localImage[0]
    _.ajax({
      type:'POST',
      data:{'filedata':filedata,'image_type':0,'_tkv_':_('#_tkv_').attr('value')},
      url:'./?type=image&mode=image_upload',
      success:function(result){
        if (result['status'] == 1) {
          var tmp_content = tinyMCE.activeEditor.getContent();
          tmp_content = tmp_content.replace(filedata,result.location)
          tinyMCE.activeEditor.setContent(tmp_content);
          var localImage = checkLocalImage();
          if (localImage.length > 0) {
          _('.form-progress-wrap').html(_('.form-progress-wrap').html()+'<div class="form-progress">'+localImage.length+' <?php _e('images uploading');?></div>');
            setTimeout(function(){
              uploadArticleImage()
            },500)
          }else{
          	_('.form-progress-wrap').html(_('.form-progress-wrap').html()+'<div class="form-progress"><?php _e('Images upload successfully,next');?></div>');
			_('.button-save').click();
          }
        }else{
          _('.form-progress-wrap').html(_('.form-progress-wrap').html()+'<div class="form-progress">'+result['status_code']+'</div>');
        }
      }
    });
  }

  function detectImage(){
    var tmp_content = tinyMCE.activeEditor.getContent();
    var content_imgs = tmp_content.match(/<img src="(?!https?:\/\/<?php echo str_replace('.', '\.', $_SERVER['HTTP_HOST']);?>)[^\"]+"/g),imgs = [],tmp_img = '';
    if (!content_imgs) {return false;}
    for(i in content_imgs){
      tmp_img = content_imgs[i].replace('<img src="','').replace('"','');
      if (!!tmp_img && tmp_img.indexOf('data:') == -1 && tmp_img.indexOf('blob:') == -1) {
        imgs.push(tmp_img)
      }
    }
    if (!!imgs && imgs.length > 0) {
      return imgs;
    }
    return false;
  }

function readImageFromUrl(imgurl,imgs){
    var img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = function () {
		var tmp_content = tinyMCE.activeEditor.getContent();
      var tmp_img_content = tmp_content.replace(this.src,getImageBase64(this,'png'))
      tinyMCE.activeEditor.setContent(clean_body(tmp_img_content));
      if (imgs.length > 1) {
        toLocalImage()
      }else{
          _('.form-progress-wrap').html(_('.form-progress-wrap').html()+'<div class="form-progress"><?php _e('Images has been downloaded,you can edit it now.');?></div>');
      }
    }
    img.onerror = function(errorMsg){
      var _this = this;
      _.ajax({
        type:'POST',
        data:{'imgurl':this.src,'_tkv_':_('#_tkv_').attr('value')},
        url:'./?type=image&mode=imgload',
        success:function(result){
          if (result['status'] == 1) {
  			var tmp_content = tinyMCE.activeEditor.getContent();
            var tmp_img_content = tmp_content.replace(_this.src,result['imgdata'])
            tinyMCE.activeEditor.setContent(clean_body(tmp_img_content));
            if (imgs.length > 1) {
              toLocalImage()
            }
          }else{
            _('.form-progress-wrap').html(_('.form-progress-wrap').html()+'<div class="form-progress"><?php _e('Image');?><a href="'+_this.src+'" target="_blank">（'+_this.src+'）</a> <?php _e('Server disallow download,please download it manually and upload');?></div>');
          }
        }
      });
      
    }
    img.src = imgurl;
}
function readImageFromLocal(imgurl,imgs){
	if (!imgurl) {
		return ;
	}
	var img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = function () {
	    var reader = new FileReader(); 
	    reader.readAsDataURL(this); 
	    reader.onload = function(e){
	  		var tmp_content = tinyMCE.activeEditor.getContent();	
	        var tmp_img_content = tmp_content.replace(imgurl,this.result)
	        tinyMCE.activeEditor.setContent(tmp_img_content);
	        if (imgs.length > 1) {
	          toLocalImage()
	        }
	    }    	
    }
    img.onerror = function(errorMsg) {
  		var tmp_content = tinyMCE.activeEditor.getContent();	
        var tmp_img_content = tmp_content.replace(imgurl,'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgc3R5bGU9IndpZHRoOiAxZW07aGVpZ2h0OiAxZW07dmVydGljYWwtYWxpZ246IG1pZGRsZTtmaWxsOiBjdXJyZW50Q29sb3I7b3ZlcmZsb3c6IGhpZGRlbjsiIHZpZXdCb3g9IjAgMCAxMDI0IDEwMjQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBwLWlkPSIxNTY0Ij48cGF0aCBkPSJNNjM3LjIxNiA0MzUuODRhNDIuNzg0IDQyLjc4NCAwIDEgMC04NS41NTItMC4wMTYgNDIuNzg0IDQyLjc4NCAwIDAgMCA4NS41NTIgMHogbTQ4IDBhOTAuNzg0IDkwLjc4NCAwIDEgMS0xODEuNTUyIDAgOTAuNzg0IDkwLjc4NCAwIDAgMSAxODEuNTUyIDB6TTI5OS43OTIgNjc0Ljk3NmwtMTEuNzYtMTAuODQ4YTE2IDE2IDAgMCAxLTAuODk2LTIyLjYwOGwxMjguMzUyLTEzOS4wNzJhMTYgMTYgMCAwIDEgMjMuMDcyLTAuNDQ4bDExMy44MDggMTEzLjkyIDcwLjg5Ni02NC43NTJhMTYgMTYgMCAwIDEgMjIuMTc2IDAuNTc2bDg4LjQgODkuNTUyYTE2IDE2IDAgMCAxLTAuMTYgMjIuNjRsLTExLjM2IDExLjIzMmExNiAxNiAwIDAgMS0yMi42NC0wLjE2bC02Ni43NjgtNjcuNjMyLTcwLjc2OCA2NC42MjRhMTYgMTYgMCAwIDEtMjIuMTEyLTAuNTEybC0xMTEuODcyLTExMi0xMDUuNzYgMTE0LjU3NmExNiAxNiAwIDAgMS0yMi42MDggMC45MTJ6TTI0MCA3MzQuNjA4aDUzMC43ODRWMzA0SDI0MHY0MzAuNjA4ek04MTguNzg0IDI3MnY0OTQuNjA4YTE2IDE2IDAgMCAxLTE2IDE2SDIwOGExNiAxNiAwIDAgMS0xNi0xNlYyNzJhMTYgMTYgMCAwIDEgMTYtMTZoNTk0Ljc4NGExNiAxNiAwIDAgMSAxNiAxNnoiIHAtaWQ9IjE1NjUiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guNzc4MTA2OS4xOTk4OTEwNDE5LmkwIj48L3BhdGg+PC9zdmc+')
        tinyMCE.activeEditor.setContent(tmp_img_content);
        if (imgs.length > 1) {
          toLocalImage()
        }
  	}
  	img.src = imgurl
}

function toLocalImage(){
  var tmp_content = tinyMCE.activeEditor.getContent();
  var content_imgs = tmp_content.match(/<img\s.*src="(?!https?:\/\/<?php echo str_replace('.', '\.', $_SERVER['HTTP_HOST']);?>)[^\"]+"/g),imgs = [],tmp_img = '';
  if (!content_imgs) {return [];}
  for(var i in content_imgs){
    tmp_img = content_imgs[i].match(/\ssrc="([^"]+)"/);
    if (!!tmp_img && tmp_img.length == 2 && tmp_img[1].indexOf('data:') == -1 && tmp_img[1].indexOf('blob:') == -1) {
      imgs.push(tmp_img[1])
    }
  }
  if (!!imgs && imgs.length > 0) {
  	var imgurl = imgs[0].replace('src="','').replace('"','');
  	if (/https?:\/\/.+?/.test(imgurl)) {
  		readImageFromUrl(imgurl,imgs)
  	}else{
  		readImageFromLocal(imgurl,imgs)
  	}
  }
}
var editor = [],curr_editor;
_().ready(function(){
	_('.editor_toggle').bind('click',function(){
		_(this).parent().find('.editor_toggle').removeClass('current_label');
		_(this).addClass('current_label');
		var _this = this;
		if (_(this).attr('data') == 'visual')
		{
			if (!!editor[_(this).attr('tid')])
			{
				tinyMCE.get(_(this).attr('tid')).show();
			}else{
				editorEnable(_(this).attr('tid'));
				editor[_(this).attr('tid')] = true;
			}
		}else{
			if (!!editor[_(this).attr('tid')]){
				tinyMCE.get(_(this).attr('tid')).hide();
			}
		}
		_('#'+_(this).attr('tid')).keyup(function(){
			tinyMCE.get(_(_this).attr('tid')).setContent(_('#'+_(_this).attr('tid')).val());
		});
	});
});

function editorEnable(id){
	tinyMCE.init({
    // General options
	mode : 'exact',
	convert_urls : false ,
	elements : id,
	invalid_elements : 'div',
	plugins: 'advlist autolink link image lists charmap preview hr anchor searchreplace fullscreen insertdatetime media imagetools quickbars table contextmenu directionality textcolor paste pagebreak wordcount',

	paste_word_valid_elements: 'b,strong,h1,h2,img,p',
	paste_enable_default_filters:false,
	force_br_newlines : true,
	force_p_newlines : false,
	formats: {
	removeformat: [
	  {selector: 'b,strong,em,i,font,u,strike,a,input,button,select', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
	  {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
	  {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
	]
	},
	image_advtab: true,
	autoresize_bottom_margin: 0,
	autosave_restore_when_empty: true,
	autosave_ask_before_unload:false,
	automatic_uploads:false,
	inline_styles:true,
	toolbar: 'bold italic underline strikethrough | bullist numlist | blockquote | hr | link  unlink | alignleft aligncenter alignright alignjustify | searchreplace | outdent indent rtl | UploadImage media | inserttime anchor | removeformat formatselect | forecolor backcolor | undo redo | fontselect fontsizeselect | pagebreak | fullscreen preview',
	font_formats: "Andale Mono=andale mono,times;"+
	    "Arial=arial,helvetica,sans-serif;"+
	    "Arial Black=arial black,avant garde;"+
	    "Book Antiqua=book antiqua,palatino;"+
	    "Comic Sans MS=comic sans ms,sans-serif;"+
	    "Courier New=courier new,courier;"+
	    "Georgia=georgia,palatino;"+
	    "Helvetica=helvetica;"+
	    "Impact=impact,chicago;"+
	    "Microsoft YaHei=Microsoft YaHei;"+
	    "Symbol=symbol;"+
	    "Tahoma=tahoma,arial,helvetica,sans-serif;"+
	    "Terminal=terminal,monaco;"+
	    "Times New Roman=times new roman,times;"+
	    "Trebuchet MS=trebuchet ms,geneva;"+
	    "Verdana=verdana,geneva;"+
	    "Webdings=webdings;"+
	    "Wingdings=wingdings,zapf dingbats",
	fontsize_formats: "10px 11px 12px 14px 16px 18px 20px 22px 24px 25px 28px 30px 35px 40px 50px 60px 70px 0.5rem 1rem 1.5rem 2rem 2.5rem 3rem 3.5rem 4rem",
	menubar: false,
	paste_preprocess: function(plugin, args) {
		args.content = clean_body(args.content)+'<p></p>';
	},
	<?php
	switch($global_setting['lang']):
		case 'zh-cn.php':
	?>
		language : 'zh_CN',
	<?php
		break;
		case 'big5.php':
	?>
		language : 'zh_TW',
	<?php
		break;
	endswitch;
	?>
	// Example content CSS (should be your site CSS)
	content_css : 'tinymce.css'
});
}

//-->
</script>
