--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/7)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_videoinfo_port_' . bin2hex(random_bytes(4));
@mkdir($dir);
$src = $dir . DIRECTORY_SEPARATOR . 'input.mp4';
$dst = $dir . DIRECTORY_SEPARATOR . 'output.png';
file_put_contents($src, str_repeat("0", 1024));
file_put_contents($dst, str_repeat("1", 256));
clearstatcache();
file_exists($src);
file_exists($dst);
pathinfo($src, PATHINFO_EXTENSION);
pathinfo($src, PATHINFO_FILENAME);
basename($src);
$exts = ['.ts', '.webm', '.ogv', '.mp4', '.mp3', '.png'];
foreach ($exts as $ext) {
    $tmp = tempnam($dir, 't');
    $out = substr($tmp, 0, strrpos($tmp, '.')) . $ext;
    rename($tmp, $out);
    file_put_contents($out, file_get_contents($src));
    clearstatcache();
    file_exists($out);
    filesize($out);
}
$h = fopen($src, 'rb');
fseek($h, 0, SEEK_END);
$size = ftell($h);
fclose($h);
$img = imagecreatetruecolor(1280, 720);
imagesetpixel($img, 0, 0, 0);
imagepng($img, $dst);
imagedestroy($img);
clearstatcache();
file_exists($dst);
unlink($src);
unlink($dst);
rmdir($dir);
?>
--EXPECT--
