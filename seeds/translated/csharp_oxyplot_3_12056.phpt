--TEST--
Translated seed (inferredbugs-csharp: oxyplot/3)
--FILE--
<?php
$w = 1;
$h = 1;
$bg = [0, 0, 0, 0];
$img = imagecreatetruecolor($w, $h);
imagesavealpha($img, true);
imagealphablending($img, false);
$transparent = imagecolorallocatealpha($img, $bg[0], $bg[1], $bg[2], $bg[3]);
imagefill($img, 0, 0, $transparent);
$fg = imagecolorallocatealpha($img, 255, 0, 0, 0);
imagesetpixel($img, 0, 0, $fg);
ob_start();
imagepng($img);
$data = ob_get_clean();
imagedestroy($img);
$stream = fopen('php://memory', 'r+');
fwrite($stream, $data);
rewind($stream);
fread($stream, 8);
fclose($stream);
?>
--EXPECT--
