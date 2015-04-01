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
					'advlist autolink autosave link image lists charmap preview hr anchor autoresize',
					'searchreplace code fullscreen insertdatetime media',
					'table contextmenu directionality textcolor'
	],

	toolbar1: 'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect | table',
	toolbar2: 'bullist numlist | searchreplace | outdent indent rtl blockquote | link unlink anchor | image media inserttime hr | removeformat forecolor backcolor | code preview fullscreen',


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
