<?php
/**
 * Image captcha.
 *
 * @package SweetRice
 * @Plugin subscriber
 * @since 0.5.4
 */
session_name('sweetrice');
session_start();
function string_big()
{
    return chr(rand(65, 90));
}
function string_small()
{
    return chr(rand(97, 122));
}
function number_code()
{
    return chr(rand(48, 57));
}
function rand_code($im, $color, $font)
{
    $codes = '';
    $x     = rand(0, 5);
    $y     = rand(18, 25);
    $r     = rand(0, 45);
    $type  = rand(1, 3);
    switch ($type) {
        case 1:
            for ($i = 0; $i < 4; $i++) {
                $r = rand(-30, 30);
                $x += 15;
                $code = string_big() . ' ';
                imagettftext($im, 15, $r, $x, $y, $color, $font, $code);
                $codes .= $code;
            }
            break;
        case 2:
            for ($i = 0; $i < 4; $i++) {
                $r = rand(-30, 30);
                $x += 15;
                $code = string_small() . ' ';
                imagettftext($im, 15, $r, $x, $y, $color, $font, $code);
                $codes .= $code;
            }
            break;
        default:
            for ($i = 0; $i < 5; $i++) {
                $r = rand(-30, 30);
                $x += 15;
                $code = number_code();
                imagettftext($im, 15, $r, $x, $y, $color, $font, $code);
                $codes .= $code;
            }
    }
    return $codes;
}

header("Content-type: image/png");

// Create the image
$im = imagecreatetruecolor(100, 30);

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey  = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 100, 30, $white);
imageline($im, rand(0, 5), rand(0, 8), rand(90, 100), rand(20, 30), $black);
imageline($im, rand(0, 5), rand(8, 18), rand(90, 100), rand(0, 8), $black);
imageline($im, rand(0, 5), rand(20, 30), rand(90, 100), rand(8, 18), $black);
$font                 = '../inc/font/captcha.ttf';
$code                 = rand_code($im, $black, $font);
$_SESSION["hashcode"] = md5(str_replace(' ', '', $code));
imagepng($im);
imagedestroy($im);
