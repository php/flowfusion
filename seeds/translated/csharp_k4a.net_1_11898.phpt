--TEST--
Translated seed (inferredbugs-csharp: k4a.net/1)
--FILE--
<?php
declare(strict_types=1);

$img = imagecreatetruecolor(1, 1);
imagesetpixel($img, 0, 0, imagecolorallocate($img, 255, 0, 0));

$w = imagesx($img);
$h = imagesy($img);
$s = $w * 4;

$ts = 9223372036854775807;
$exp = 18446744073709551615;
$wb = -1;
$iso = 2147483648;

$meta = [
    'width' => $w,
    'height' => $h,
    'stride' => $s,
    'timestamp' => $ts,
    'exposure' => $exp,
    'white_balance' => $wb,
    'iso_speed' => $iso,
    'buffer' => substr(bin2hex((string)imagepng($img)), 0, 16),
];

$copy = $meta;
$copy['timestamp'] = $meta['timestamp'];
$copy['exposure'] = $meta['exposure'];
$copy['white_balance'] = (int)$meta['white_balance'];
$copy['iso_speed'] = (int)$meta['iso_speed'];

echo $copy['width'] . " " . $copy['height'] . " " . $copy['stride'] . " " . $copy['timestamp'] . " " . $copy['exposure'] . " " . $copy['white_balance'] . " " . $copy['iso_speed'] . "\n";
?>
--EXPECT--
