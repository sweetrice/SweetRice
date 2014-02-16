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
var editor = false;
function doEditor(t,id){
	if(t=='visual'){
		if(editor==false){
			editorEnable(id);
			editor = true;
		}else{
			tinyMCE.get(id).show();
		}
		_('#lbVisual').addClass('current_label');
		_('#lbHtml').removeClass('current_label');
	}else{
		if(editor==false){
			return ;
		}
		tinyMCE.get(id).hide();
		_('#lbVisual').removeClass('current_label');
		_('#lbHtml').addClass('current_label');
	}
}
function editorEnable(id){
	tinyMCE.init({
        // General options
	mode : "exact",
	convert_urls : false ,
	elements : id,
  plugins: [
					"advlist autolink autosave link image lists charmap preview hr anchor autoresize",
					"searchreplace code fullscreen insertdatetime media",
					"table contextmenu directionality textcolor fullpage"
	],

	toolbar1: "fullpage undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect | table",
	toolbar2: "bullist numlist | searchreplace | outdent indent rtl blockquote | link unlink anchor | image media inserttime hr | removeformat forecolor backcolor | code preview fullscreen",
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
	content_css : "tinymce.css"
});
}

//-->
</script>
