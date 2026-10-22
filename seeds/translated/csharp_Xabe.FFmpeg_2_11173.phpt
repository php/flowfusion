--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/2)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'vid_') . '.mp4';
file_put_contents($file, "x");
$path = $file;
echo "Video FilePath : " . $path . PHP_EOL .
     "Video Root : " . dirname($path) . PHP_EOL .
     "Video Name: " . basename($path) . PHP_EOL .
     "Video Extension : " . pathinfo($path, PATHINFO_EXTENSION) . PHP_EOL .
     "Video duration : 0" . PHP_EOL .
     "Audio format : " . PHP_EOL .
     "Video format : " . PHP_EOL .
     "Aspect Ratio : " . PHP_EOL .
     "Framerate : 0fps" . PHP_EOL .
     "Resolution : 0x0" . PHP_EOL .
     "Size : 0 MB" . PHP_EOL;
unlink($file);
--EXPECT--
