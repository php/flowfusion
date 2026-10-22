--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/5)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_videoinfo_' . uniqid('', true);
mkdir($dir);
$file = $dir . DIRECTORY_SEPARATOR . 'a.b.c';
file_put_contents($file, "x");
$path = $file;
$ext = pathinfo($path, PATHINFO_EXTENSION);
$root = dirname($path);
$name = basename($path);
$size = filesize($path) / 1048576.0;
$duration = '00:00:00';
$audio = $video = $ratio = '';
$frameRate = 0.0;
$width = $height = 0;
echo "Video FilePath : $path\n";
echo "Video Root : $root\n";
echo "Video Name: $name\n";
echo "Video Extension : .$ext\n";
echo "Video duration : $duration\n";
echo "Audio format : $audio\n";
echo "Video format : $video\n";
echo "Aspect Ratio : $ratio\n";
echo "Framerate : {$frameRate}fps\n";
echo "Resolution : {$width}x{$height}\n";
echo "Size : $size MB\n";
$png = $dir . DIRECTORY_SEPARATOR . (string)(int)(microtime(true) * 1000) . '.png';
file_put_contents($png, "\x89PNG\r\n\x1a\n");
$data = file_get_contents($png);
$tmp = $dir . DIRECTORY_SEPARATOR . 'out.png';
file_put_contents($tmp, $data);
$clone = file_get_contents($tmp);
unlink($png);
unlink($tmp);
unlink($file);
rmdir($dir);
echo strlen($clone) . "\n";
?>
--EXPECT--
