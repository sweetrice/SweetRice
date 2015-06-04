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
var editor = [];
_().ready(function(){
	_('.editor_toggle').bind('click',function(){
		_(this).parent().find('.editor_toggle').removeClass('current_label');
		_(this).addClass('current_label');
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

	toolbar1: 'bold | italic | underline | hr | bullist | numlist | blockquote | hr | strikethrough | fullscreen',
	toolbar2: 'alignleft | aligncenter | alignright | alignjustify | searchreplace | outdent | indent | rtl | link | unlink',
	toolbar3: 'image | media | inserttime | anchor | removeformat | forecolor | backcolor | undo | redo',
	toolbar4: 'formatselect | fontselect | fontsizeselect | table | pagebreak | preview',
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

	menubar: false,
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
