--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/13)
--FILE--
<?php
$input = $scale = $video = $speed = $audio = $threads = $disabled = $size = $codec = $filter = $copy = $seek = $frameCount = $loop = $reverse = $shortestInput = $output = '';

$input = '-i "concat:' . implode('|', ['a.mp4', 'b.mp4', 'c.mp4']) . '" ';
$scale = '-vf scale=720 ';
$video = '-codec:v h264 -b:v 800k ';
$speed = '-preset fast ';
$audio = '-codec:a aac -b:a 128k -strict experimental ';
$threads = '-threads ' . (string) max(1, (int) (getenv('NUMBER_OF_PROCESSORS') ?: 1)) . ' ';
$disabled = '-vn ';
$size = '-s 1920x1080 ';
$codec = '-f mp4 ';
$filter = '-bsf:v h264_mp4toannexb ';
$copy = '-c copy ';
$seek = '-ss 00:00:01 ';
$frameCount = '-vframes 10 ';
$loop = '-loop 2 ';
$reverse = '-vf reverse -af areverse ';
$shortestInput = '-shortest ';
$output = '"out.mp4"';

$cmd = $input . $scale . $video . $speed . $audio . $threads . $disabled . $size . $codec . $filter . $copy . $seek . $frameCount . $loop . $reverse . $shortestInput . $output;
echo $cmd;
?>
--EXPECT--
