--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/25)
--FILE--
<?php
$fullName = __FILE__;
$ext = pathinfo($fullName, PATHINFO_EXTENSION);
$dir = dirname($fullName);
$name = basename($fullName);
$size = filesize($fullName);
$width = 0;
$height = 0;
$frameRate = 0.0;
$duration = '0:00:00';
$audioDuration = '0:00:00';
$videoDuration = '0:00:00';
$videoFormat = '';
$audioFormat = '';
$ratio = '';

echo "Video fullName : $fullName\n";
echo "Video root : $dir\n";
echo "Video name: $name\n";
echo "Video extension : $ext\n";
echo "Video duration : $videoDuration\n";
echo "Video format : $videoFormat\n";
echo "Audio format : $audioFormat\n";
echo "Audio duration : $audioDuration\n";
echo "Aspect Ratio : $ratio\n";
echo "Framerate : $ratio fps\n";
echo "Resolution : $width x $height\n";
echo "Size : $size b";
?>
--EXPECT--
