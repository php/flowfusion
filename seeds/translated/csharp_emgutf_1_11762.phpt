--TEST--
Translated seed (inferredbugs-csharp: emgutf/1)
--FILE--
<?php
$w = 4;
$h = 3;
$img = imagecreatetruecolor($w, $h);
$colors = [
    [255, 0, 0],
    [0, 255, 0],
    [0, 0, 255],
    [255, 255, 0],
    [255, 0, 255],
    [0, 255, 255],
    [128, 128, 128],
    [64, 32, 16],
    [16, 32, 64],
    [200, 100, 50],
    [50, 200, 100],
    [100, 50, 200],
];
$i = 0;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        [$r, $g, $b] = $colors[$i++];
        imagesetpixel($img, $x, $y, imagecolorallocate($img, $r, $g, $b));
    }
}
$tmp = tempnam(sys_get_temp_dir(), 'phpimg_') . '.png';
imagepng($img, $tmp);
imagedestroy($img);

$src = imagecreatefrompng($tmp);
$sw = imagesx($src);
$sh = imagesy($src);
$dst = imagecreatetruecolor($sw, $sh);

$flipUpSideDown = true;
$swapBR = true;
$inputMean = 10.0;
$scale = 0.5;

for ($y = 0; $y < $sh; $y++) {
    $sy = $flipUpSideDown ? ($sh - 1 - $y) : $y;
    for ($x = 0; $x < $sw; $x++) {
        $rgb = imagecolorat($src, $x, $sy);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;
        if ($swapBR) {
            $t = $r;
            $r = $b;
            $b = $t;
        }
        $r = (int)(($r - $inputMean) * $scale);
        $g = (int)(($g - $inputMean) * $scale);
        $b = (int)(($b - $inputMean) * $scale);
        $r = max(0, min(255, $r));
        $g = max(0, min(255, $g));
        $b = max(0, min(255, $b));
        imagesetpixel($dst, $x, $y, imagecolorallocate($dst, $r, $g, $b));
    }
}

ob_start();
imagejpeg($dst, null, 75);
$jpeg = ob_get_clean();
imagedestroy($src);
imagedestroy($dst);
@unlink($tmp);

$ann = imagecreatetruecolor(80, 60);
$bg = imagecolorallocate($ann, 240, 240, 240);
imagefill($ann, 0, 0, $bg);
$red = imagecolorallocate($ann, 255, 0, 0);
imagerectangle($ann, 5, 5, 40, 30, $red);
imagestring($ann, 5, 6, 6, "label", $red);
ob_start();
imagejpeg($ann, null, 80);
$jpeg2 = ob_get_clean();
imagedestroy($ann);
?>
--EXPECT--
