<?php
/**
 * Template Name:User track template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	$dbname = SITE_HOME.'inc/user_track.db';
	if(!file_exists($dbname)){
		$new_track = true;
	}
	$GLOBALS['db_lib_track'] = new sqlite_lib(array('name'=>$dbname));
	$y = $_GET['y'];
	if($y ==''){
		$y = date('Y');
	}
	$m = $_GET['m'];
	if($m ==''){
		$m = date('n');
	}
	$d = date('t',mktime(0,0,0,$m,1,$y));
	if($y == date('Y') && $m <= date('n') || $y < date('Y')){
		$today_start = mktime(0,0,1,date('n'),date('j'),date('Y'));
		$month_start = mktime(0,0,0,$m,1,$y);
		$month_end = mktime(23,59,59,$m,$d,$y);
		$browsers = init_browsers(1);
		$bg_browsers = init_browsers(2);
		if($new_track){
			$GLOBALS['db_lib_track']->query("CREATE TABLE user_agent (id INTEGER PRIMARY KEY ,ip varchar(39) ,user_from varchar(255) ,this_page varchar(255),user_browser varchar(255),time integer)");
			$GLOBALS['db_lib_track']->query("CREATE TABLE agent_month (id INTEGER PRIMARY KEY ,user_browser varchar(255),record_date date,total int(10),UNIQUE(user_browser,record_date))");
		}
		$month_table = $GLOBALS['db_lib_track']->db_total("SELECT COUNT(*) FROM sqlite_master WHERE name='agent_month'");
		if(!$month_table){
			$GLOBALS['db_lib_track']->query("CREATE TABLE agent_month (id INTEGER PRIMARY KEY ,user_browser varchar(255),record_date date,total int(10),UNIQUE(user_browser,record_date))");
		}
		$GLOBALS['db_lib_track']->query("DELETE FROM user_agent WHERE time < '".(time()-5184000)."'");
		$GLOBALS['db_lib_track']->query('vacuum '.$GLOBALS['db_lib_track']->name);
		$max_month = array();
		for($i=1; $i<=$d; $i++){
			$day_start = mktime(0,0,1,$m,$i,$y);
			$day_end = mktime(23,59,59,$m,$i,$y);
			foreach($browsers as $key=>$val){
				$this_date = $y.'-'.str_pad($m,2,0,STR_PAD_LEFT).'-'.str_pad($i,2,0,STR_PAD_LEFT);
				$row = $GLOBALS['db_lib_track']->db_array("SELECT `id`,`total` FROM agent_month WHERE record_date = '$this_date' AND user_browser = '$val'",false);
				if(!$row['id']&&$day_end<$today_start)
				{
					$total = $GLOBALS['db_lib_track']->db_total("SELECT COUNT(*) FROM user_agent WHERE time >= '$day_start' and time <= '$day_end' AND user_browser = '$val'",false);
					$GLOBALS['db_lib_track']->query("INSERT INTO agent_month(id,user_browser,record_date,total)VALUES(null,'$val','$this_date','$total')");
					$row['total'] = $total;
				}
				$total_browser[$key][$i] = $row['total'];
				$total_all[$key] += $row['total'];
				$max_month[$i] += $row['total'];
			}
		}
	}
	if (is_array($max_month)) {
		$all_sum = array_sum($max_month);
	}
	$bs = '';
	$bgs = '';
	$rb = '';
	$tb = '';
	$dba = '';
	if($all_sum){
		foreach($browsers as $key=>$val){
			$v[$key] = number_format($total_all[$key]*100/$all_sum ,2,'.',' ').'%';
			$rb .= '\''.number_format($total_all[$key]*100/$all_sum ,2,'.',' ').'%\',';
			$tb .= '\''.$total_all[$key].'\',';
			$db = '';
			for($i=1; $i<=$d; $i++){
				$db .= intval($total_browser[$key][$i]).',';
				if(!$wms){
					$wm .= '\''.substr(date('l',mktime(0,0,0,$m,$i,$y)),0,2).'\',';
				}
			}
			$dba .= '\''.rtrim($db,',').'\',';
			$bs .= '\''.$val.'\',';
			$bgs .= '\''.($bg_browsers[$key]?$bg_browsers[$key]:'#555555').'\',';
			if(!$wms){
				$wms = rtrim($wm,',');
			}
		}
		$max_y = max($max_month);
		$vv = $max_y/300;
		$x = 18;
		$padding_left = 30;
		$width = 6;
		$l_x = $x*$d+$padding_left+20;
		$doCanvas = 1;
		$top_pages = $GLOBALS['db_lib_track']->db_arrays("SELECT this_page,COUNT(*) AS total from user_agent WHERE time >= '$month_start' and time < '$month_end' GROUP BY this_page ORDER by total DESC LIMIT 0,10;");
		$total_pages = $GLOBALS['db_lib_track']->db_total("SELECT COUNT(*) from (SELECT COUNT(*) from user_agent WHERE time >= '$month_start' and time < '$month_end' GROUP BY this_page )");
		$top_froms = $GLOBALS['db_lib_track']->db_arrays("SELECT user_from,COUNT(*) AS total from user_agent WHERE time >= '$month_start' and time < '$month_end' GROUP BY user_from ORDER by total DESC LIMIT 0,10;");
		$total_froms = $GLOBALS['db_lib_track']->db_total("SELECT COUNT(*) from (SELECT COUNT(*) from user_agent WHERE time >= '$month_start' and time < '$month_end' GROUP BY user_from )");
		$total_ips = $GLOBALS['db_lib_track']->db_total("SELECT COUNT(*) from (SELECT COUNT(*) from user_agent WHERE time >= '$month_start' and time < '$month_end' GROUP BY ip )");
		$top_ips = $GLOBALS['db_lib_track']->db_arrays("SELECT ip,COUNT(*) AS total from user_agent WHERE time >= '$month_start' and time < '$month_end' GROUP BY ip ORDER by total DESC LIMIT 0,10;");
	}else{
		$doCanvas = 0;
	}
	$start_record = $GLOBALS['db_lib_track']->db_array("SELECT time from user_agent ORDER by time ASC LIMIT 0,1;");
?>
<fieldset><legend><?php echo _t('Track').' - '._t('Chart').' - '._t('Click the date to show chart.');?></legend>
<select class="dy">
<?php
	for($i=date('Y',$start_record['time']); $i<= date('Y'); $i++){
?>
<option value="<?php echo $i;?>" <?php echo $i == $y?'selected':'';?>><?php echo $i;?></option>
<?php
	}
?>
</select>
<select class="dm">
<?php
		for($j=1; $j<=12; $j++){
?>
<option value="<?php echo $j;?>" <?php echo $j == $m?'selected':'';?>><?php echo $j;?></option>
<?php
	}
?>
</select>
<input type="button" value="<?php _e('Search');?>" class="dlist">
<?php
	if($y >= date('Y',$start_record['time']) && $y <= date('Y')):
?>
<span class="track_list">
<?php
		for($i=1;$i<=12;$i++):
?>
<a href="javascript:void(0);" <?php echo $i == $m?'class="track_curr"':'';?> y="<?php echo $y;?>" m="<?php echo $i;?>"><?php echo $y.'-'.$i;?></a> 
<?php
		endfor;
?>
</span>
<?php
	endif;
?>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.dlist').bind('click',function(){
			location.href = './?type=track&y='+_('.dy').val()+'&m='+_('.dm').val();
		});
		_('.track_list a').bind('mouseover',function(){
			_('.track_list a').removeClass('track_curr');
			_(this).addClass('track_curr');
		}).bind('click',function(){
			location.href = './?type=track&y='+_(this).attr('y')+'&m='+_(this).attr('m');
		});
	});
//-->
</script>
</fieldset>
<script type="text/javascript" src="../js/excanvas.compiled.js"></script>
<script type="text/javascript">
<!--
	function getTotalByD(b,d,r){
		var dTotal = r[b].split(',');
		return parseInt(dTotal[d-1]);
	}
	function getLastTotalByD(b,d,r){
		var dTotal = r[b].split(',');
		return parseInt(dTotal[d-2]);
	}
	function drawCanvas(){
		var doCanvas = <?php echo $doCanvas;?>;
		if(!doCanvas){
			var canvas = _('#myCanvas').items();
			var context = canvas.getContext('2d');
			context.beginPath();
			context.rect(200, 100, 400, 100);
			context.fillStyle = '#808080';
			context.fill();
			context.stroke();
			context.font = '12pt Verdana';
			context.fillStyle = '#fff';
			context.fillText('No data,please enable User track or visit later.', 210, 140);
			context.stroke();
			context.beginPath();
			context.font = '7pt Verdana';
			context.fillStyle = '#336600';
			context.fillText('Powered by SweetRice', 600, 300);
			context.stroke();
		}else{
			var browsers = [<?php echo rtrim($bs,',');?>];
			var browserBgs = [<?php echo rtrim($bgs,',');?>];
			var browserRates = [<?php echo rtrim($rb,',');?>];
			browserTotal = [<?php echo rtrim($tb,',');?>];
			var dailybrowser = [<?php echo rtrim($dba,',');?>];
			var weekMonth = [<?php echo $wms;?>];
			var vv = <?php echo floatval($vv);?>;
			var x = <?php echo intval($x);?>;
			var d = <?php echo $d;?>;
			var padding_left = 30;
			var width = 6;
			var line_width = 2;
			var l_x = <?php echo intval($l_x);?>;
			var s_line = 310;
			var canvas = _('#myCanvas').items();
			var context = canvas.getContext('2d');
			context.beginPath();
			context.font = '7pt Verdana';
			context.fillStyle = '#336600';
			context.fillText('Powered by SweetRice', l_x, 310);
			context.stroke();
			context.font = '9pt Verdana';
			var _rtop = 50;
			for(i in browsers){
				context.beginPath();
				context.font = '9pt Verdana';
				context.fillStyle = browserBgs[i];
				context.fillText(browsers[i]+':'+browserRates[i], l_x, _rtop);
				context.stroke();
				_rtop += 15;
			}
			context.beginPath();
			context.font = '9pt Verdana';
			context.fillStyle = '#000';
			context.fillText('Daily:100%', l_x, _rtop);
			context.stroke();
			context.fillStyle = '#000000';
			context.fillText('<?php echo $y.'-'.$m;?>', l_x, 30);
			context.font = '7pt Arial';
			var no = 0;
			for(i=0; i<8; i++){
				context.beginPath();
				context.moveTo(padding_left+x-width, s_line);
				context.lineTo(x*d+padding_left+width, s_line);
				context.lineWidth=1;
				context.strokeStyle='#ccc';
				context.fillText((no*50*vv).toFixed(0), 0, s_line+10);
				context.stroke();
				s_line -= 50;
				no += 1;
			}
			for(i=1; i<=d; i++){
				context.beginPath();
				context.lineWidth=1;
				context.moveTo(i*x+padding_left, 310);
				context.lineTo(i*x+padding_left, 315);
				context.strokeStyle = '#ccc';
				context.fillText(i, i*x+padding_left, 326);
				context.fillText(weekMonth[i-1], i*x+padding_left, 337);
				context.stroke();
				if(i>1){
					var tmp_total = last_total = day_total = lastday_total = 0;
					for(j in browsers){
						tmp_total = getTotalByD(j,i,dailybrowser);
						last_total = getLastTotalByD(j,i,dailybrowser);
						context.beginPath();
						context.moveTo((i-1)*x+padding_left, 310-last_total/vv);
						context.lineTo(i*x+padding_left, 310-tmp_total/vv);
						context.lineWidth = line_width;
						context.strokeStyle = browserBgs[j];
						context.stroke();
						day_total += tmp_total;
						lastday_total += last_total;
					}
					context.beginPath();
					context.moveTo((i-1)*x+padding_left, 310-lastday_total/vv);
					context.lineTo(i*x+padding_left, 310-day_total/vv);
					context.lineWidth = line_width;
					context.strokeStyle = '#000';
					context.stroke();					
				}
			}
		}
	}
	window.onload = function(){drawCanvas();};
	</script>
<h1><?php echo $y.' - '.$m;?></h1>
<canvas id="myCanvas" width="720" height="340"></canvas>
<div id="view_chart">
<?php
if(is_array($top_pages) && count($top_pages)){
?>
<div><?php echo vsprintf(_t('Top 10 of %s visited page'),array($total_pages));?></div>
<dl>
<dt class="head"><?php _e('Visited Pages');?></dt><dd class="head"><?php _e('Total');?></dd></dl>
<?php
	foreach($top_pages as $top_page){
?>
<dl><dt><?php echo $top_page['this_page'];?></dt><dd><?php echo $top_page['total'];?></dd></dl>
<?php
	}
}
if(is_array($top_froms) && count($top_froms)){
?>
<div><?php echo vsprintf(_t('Top 10 of %s referrer page'),array($total_froms));?></div>
<dl>
<dt class="head"><?php _e('Referrer Pages');?></dt><dd class="head"><?php _e('Total');?></dd></dl>
<?php
	foreach($top_froms as $top_from){
?>
<dl><dt><?php echo $top_from['user_from'];?></dt><dd><?php echo $top_from['total'];?></dd></dl>
<?php
	}
}
if(is_array($top_ips) && count($top_ips)){
?>
<div><?php echo vsprintf(_t('Top 10 of %s ip'),array($total_ips));?></div>
<dl>
<dt class="head"><?php _e('IP');?></dt><dd class="head"><?php _e('Total');?></dd></dl>
<?php
	foreach($top_ips as $top_ip){
?>
<dl><dt><?php echo $top_ip['ip'];?></dt><dd><?php echo $top_ip['total'];?></dd></dl>
<?php
	}
}
?>
<div class="div_clear"></div>
</div>