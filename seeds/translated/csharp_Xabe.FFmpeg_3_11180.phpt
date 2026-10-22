--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/3)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'vid');
file_put_contents($file, "x");
$p = new SplFileInfo($file);
$path = $p->getRealPath();
echo "Video FilePath : $path\n";
echo "Video Root : " . dirname($path) . "\n";
echo "Video Name: " . basename($path) . "\n";
echo "Video Extension : " . pathinfo($path, PATHINFO_EXTENSION) . "\n";
echo "Video duration : 0:00:00\n";
echo "Audio format : \n";
echo "Video format : \n";
echo "Aspect Ratio : \n";
echo "Framerate : 0fps\n";
echo "Resolution : 0x0\n";
echo "Size : " . (filesize($path) / 1048576) . " MB\n";
unlink($file);
--EXPECT--
