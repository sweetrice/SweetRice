<?php
/**
 * View user track.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
defined('VALID_INCLUDE') or die();
	$dbname = '../inc/user_track.db';
	$font_ttf = '../_plugin/font/arial.ttf';
	$y = $_GET["y"];
	if($y==''){
		$y = date('Y');
	}
	$m = $_GET["m"];
	if($m==''){
		$m = date('n');
	}
	$d = date('t',mktime(0,0,0,$m,1,$y));
	$today_start = mktime(0,0,1,date('n'),date('j'),date('Y'));
	$browsers = init_browsers(1);
	$bg_browsers = init_browsers(2);
	$db_track = sqlite_dbhandle($dbname);
	$max_month = array();
	for($i=1; $i<=$d; $i++){
		foreach($browsers as $key=>$val){
			$this_date = $y.'-'.str_pad($m,2,0,STR_PAD_LEFT).'-'.str_pad($i,2,0,STR_PAD_LEFT);
			$row = sqlite_dbarray($db_track,"SELECT `total` FROM agent_month WHERE record_date = '$this_date' AND user_browser = '$val'",false);
			$total_browser[$key][$i] += $row['total'];
			$total_all[$key] += $row['total'];
			$max_month[$i] += $row['total'];
		}
	}
	
	$all_sum = array_sum($max_month);
	if($all_sum){
		$max_y = max($max_month);
		$vv = $max_y/300;
		$x = 18;
		$padding_left = 30;
		$width = 6;
		$l_x = $x*$d+$padding_left+20;
		$im = imagecreate(120+$l_x,340);
		$bg = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		$font = imagecolorallocate($im, 120, 120, 120);
		$watermark = imagecolorallocate($im, 180, 240, 180);
		foreach($browsers as $key=>$val){
			$v[$key] = number_format($total_all[$key]*100/$all_sum ,2,'.',' ').'%';
			$htmlcolor = trim($bg_browsers[$key]?$bg_browsers[$key]:'#555','#');
			$colorlen = strlen($htmlcolor);
			switch($colorlen){
				case 3:
					$htmlcolor = substr($htmlcolor,0,1).substr($htmlcolor,0,1).substr($htmlcolor,1,1).substr($htmlcolor,1,1).substr($htmlcolor,2,1).substr($htmlcolor,2,1);
				break;
				default:
					for($i=0; $i<6-$colorlen; $i++)
					{
						$htmlcolor .= '0';
					}
			}
			$bg_browser[$key] = imagecolorallocate($im, hexdec(substr($htmlcolor,0,2)), hexdec(substr($htmlcolor,2,2)), hexdec(substr($htmlcolor,4,2)));
		}

		imagettftext($im, 30, 15, 100, 220, $watermark, $font_ttf, 'Powered by SweetRice');
		$_rtop = 40;
		foreach($browsers as $key=>$val){
			imagettftext($im, 11, 0, $l_x, $_rtop, $bg_browser[$key], $font_ttf,$val.':'.$v[$key]);
			$_rtop += 15;
		}
		imagettftext($im, 11, 0, $l_x, $_rtop, $black, $font_ttf,'Daily:100%');
		imagettftext($im, 11, 0, $l_x, 20, $black, $font_ttf,$y.'-'.$m);

		$line_rotage = 0;
		$style = array($black, $white);
		imagesetstyle($im, $style);

		$s_line = 310;
		$no = 0;
		for($i=0; $i<8; $i++){
			imageline ($im,$padding_left+$x-$width,$s_line,$x*$d+$padding_left+$width,$s_line,IMG_COLOR_STYLED);
			imagettftext($im, 7, 0, 0, ($s_line+10), $black, $font_ttf,round($no*50*$vv));
			$s_line -= 50;
			$no +=1;
		}
		for($i=1; $i<=$d; $i++){
			imagesetthickness($im,1);
			imageline ($im,($i*$x+$padding_left),310,($i*$x+$padding_left),315,$font);
			imagettftext($im, 7, 0, ($i*$x+$padding_left), 326, $font, $font_ttf,$i);
			imagettftext($im, 7, 0, ($i*$x+$padding_left), 337, $black, $font_ttf,substr(date('l',mktime(0,0,0,$m,$i,$y)),0,2));
			if($i>1){
				imagesetthickness($im,2);
				$tmp_total = $last_total = $day_total = $lastday_total = 0;
				foreach($browsers as $key=>$val){
					$tmp_total = $total_browser[$key][$i];
					$last_total = $total_browser[$key][$i-1];
					imageline($im,(($i-1)*$x+$padding_left),310-$last_total/$vv,($i*$x+$padding_left),310-$tmp_total/$vv,$bg_browser[$key]);
					$day_total += $tmp_total;
					$lastday_total += $last_total;
				}
				imageline($im,(($i-1)*$x+$padding_left),310-$lastday_total/$vv,($i*$x+$padding_left),310-$day_total/$vv,$black);				
			}
		}
	}else{
		$no_data = true;
	}

if($no_data){
	$im = imagecreate(380,100);
	$bg = imagecolorallocate($im, 128, 128, 128);
	$white = imagecolorallocate($im, 255, 255, 255);
	imagettftext($im, 12, 0, 20, 50, $white, $font_ttf,'No data,please enable User track or visit later.');
}
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>