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
	plugins: [
					'advlist autolink autosave link image lists charmap preview hr anchor',
					'searchreplace fullscreen insertdatetime media',
					'table contextmenu directionality textcolor pagebreak'
	],

	toolbar1: 'bold | italic | underline | strikethrough | bullist | numlist | blockquote | hr | link | unlink | alignleft | aligncenter | alignright | alignjustify | searchreplace | outdent | indent | rtl | UploadImage',
	toolbar2: 'image | media | inserttime | anchor | removeformat | formatselect | forecolor | backcolor | undo | redo | fontselect | fontsizeselect | pagebreak | fullscreen | preview',
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
  setup: function (editor) {
    editor.addButton('UploadImage', {
      tooltip: '<?php _e('Upload');?>',
      icon: 'image',
      onclick: function () {
      	curr_editor = editor;
		_.dialog({'title':'<?php _e('Image List');?>','content':'<iframe src="./?type=image" style="border:none;width:100%;height:'+((_.pageSize().windowHeight-200) > 300?(_.pageSize().windowHeight-200):300)+'px;"></iframe>','width':_.pageSize().windowWidth,'height':_.pageSize().windowHeight-150,'layer':true});
      }
    });
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
