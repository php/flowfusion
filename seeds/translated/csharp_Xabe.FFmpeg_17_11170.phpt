--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/17)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_port_' . bin2hex(random_bytes(4));
@mkdir($dir);
$w = 1280;
$h = 720;
$img = imagecreatetruecolor($w, $h);
$bg = imagecolorallocate($img, 20, 40, 80);
imagefill($img, 0, 0, $bg);
$png = $dir . DIRECTORY_SEPARATOR . uniqid('', true) . '.png';
imagepng($img, $png);
imagedestroy($img);
clearstatcache(true, $png);
if (!is_file($png) || filesize($png) <= 0) {
    exit(1);
}
$info = getimagesize($png);
if ($info[0] !== $w || $info[1] !== $h) {
    exit(1);
}
unlink($png);
@rmdir($dir);
?>
--EXPECT--
