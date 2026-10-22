--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/14)
--FILE--
<?php
$input = $scale = $video = $speed = $audio = $threads = $disabled = $size = $codec = $bsf = $copy = $seek = $frameCount = $loop = $reverse = $shortest = $videoSpeed = $audioSpeed = $output = '';

$input = '-i "concat:' . implode('|', ['a.mp4', 'b.mp4']) . '" ';
$scale = '-vf scale=1280:720 ';
$video = '-codec:v h264 -b:v 800k ';
$speed = '-preset fast ';
$audio = '-codec:a aac -b:a 128k -strict experimental ';
$threads = '-threads ' . (string) max(1, (int) shell_exec('nproc 2>/dev/null') ?: 1) . ' ';
$disabled = '-vn ';
$size = '-s 640x360 ';
$codec = '-f mp4 ';
$bsf = '-bsf:v h264_mp4toannexb ';
$copy = '-c copy ';
$seek = '-ss 00:00:01 ';
$frameCount = '-frames:v 3 ';
$loop = '-loop 1 ';
$reverse = '-vf reverse -af areverse ';
$shortest = '-shortest ';
$videoSpeed = 'setpts=1.5*PTS ';
$audioSpeed = 'atempo=0.5 ';
$output = '"out.mp4"';

$cmd = $input . $scale . $video . $speed . $audio . $threads . $disabled . $size . $codec . $bsf . $copy . $seek . $frameCount . $loop . $reverse . $shortest . '-filter:v ' . $videoSpeed . '-filter:a ' . $audioSpeed . $output;
echo $cmd;
?>
--EXPECT--
