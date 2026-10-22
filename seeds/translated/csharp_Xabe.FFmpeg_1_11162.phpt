--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/1)
--FILE--
<?php
$f = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($f, "x");
$p = pathinfo($f);
echo "Video FilePath : $f\n";
echo "Video Root : " . $p['dirname'] . "\n";
echo "Video Name: " . $p['basename'] . "\n";
echo "Video Extension : " . ($p['extension'] ?? '') . "\n";
echo "Video duration : 00:00:00\n";
echo "Audio format : \n";
echo "Video format : \n";
echo "Aspect Ratio : \n";
echo "Framerate : 0fps\n";
echo "Resolution : 0x0\n";
echo "Size : " . (filesize($f) / 1048576) . " MB\n";
$png = $f . '.png';
$im = imagecreatetruecolor(1, 1);
imagepng($im, $png);
imagedestroy($im);
$im = imagecreatefrompng($png);
$copy = imagecreatetruecolor(imagesx($im), imagesy($im));
imagecopy($copy, $im, 0, 0, 0, 0, imagesx($im), imagesy($im));
imagedestroy($im);
imagedestroy($copy);
imagedestroy($im);
unlink($png);
unlink($f);
?>
--EXPECT--
