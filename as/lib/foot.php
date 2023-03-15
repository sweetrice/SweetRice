<?php
/**
 * Foot section template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
?>
<div id="div_foot">Powered by <a href="https://www.sweetrice.xyz">SweetRice</a> &copy; <?php echo date('Y'); ?> <div class="btn_bgcolor"></div></div>
<script type="text/javascript">
<!--
	_.ready(function(){
		_('.btn_bgcolor').css({'background-color':_.fromColor(_.getCookie('dashboad_bg') || '#555',true)}).click(function(){
			var color = _.randomColor( 0x88 );
			_.setCookie({'name':'dashboad_bg','value':color});
			_(document.body).animate({'background-color':color},200);
			_('.btn_bgcolor').animate({'background-color':_.fromColor(color,true)},200);
		});
	});
//-->
</script>
</body>
</html>