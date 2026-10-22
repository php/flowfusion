--TEST--
Translated seed (inferredbugs-csharp: oxyplot/4)
--FILE--
<?php
$w = 1024;
$h = 768;
$bg = [211, 211, 211];
$labels = ["Apple cake", "Baumkuchen", "Bundt Cake", "Chocolate cake", "Carrot cake"];
$vals = [];
$sum = 0.0;
for ($i = 0; $i < 5; $i++) {
    $v = mt_rand() / mt_getrandmax();
    $vals[] = $v;
    $sum += $v;
}
$img = imagecreatetruecolor($w, $h);
$bgc = imagecolorallocate($img, $bg[0], $bg[1], $bg[2]);
imagefill($img, 0, 0, $bgc);
$margin = 80;
$plotW = $w - 2 * $margin;
$plotH = $h - 2 * $margin;
$barW = (int)($plotW / 5);
for ($i = 0; $i < 5; $i++) {
    $v = $vals[$i] / $sum * 100.0;
    $r = mt_rand(0, 254);
    $g = mt_rand(0, 254);
    $b = mt_rand(0, 254);
    $c = imagecolorallocate($img, $r, $g, $b);
    $x1 = $margin + $i * $barW + 10;
    $x2 = $margin + ($i + 1) * $barW - 10;
    $y2 = $h - $margin;
    $y1 = (int)($y2 - ($v / 100.0) * $plotH);
    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $c);
    imagestringup($img, 3, $x1 + 4, $y2 + 60, $labels[$i], $bgc);
}
imagepng($img, "test-oxyplot-memstream.png");
imagepng($img, "test-oxyplot-file.png");
imagepng($img, "test-oxyplot-file-brush.png");
imagepng($img, "test-oxyplot-stream-export.png");
imagepng($img, "test-oxyplot-exportobitmap.png");
imagepng($img, "test-oxyplot-exportobitmap-brush.png");
imagepng($img, "test-oxyplot-ExportToStream.png");
imagepng($img, "test-oxyplot-ExportToFile.png");
imagedestroy($img);
?>
--EXPECT--
