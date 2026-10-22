--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/10)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_videoinfo_bug_' . bin2hex(random_bytes(4));
@mkdir($dir);
$video = $dir . DIRECTORY_SEPARATOR . 'input.mp4';
$audio = $dir . DIRECTORY_SEPARATOR . 'audio.mp3';
$mute = $dir . DIRECTORY_SEPARATOR . 'mute.mp4';
$mkv = $dir . DIRECTORY_SEPARATOR . 'sampleMkv.mkv';

file_put_contents($video, str_repeat("mp4", 100));
file_put_contents($audio, str_repeat("mp3", 50));
file_put_contents($mute, str_repeat("mute", 80));
file_put_contents($mkv, str_repeat("mkv", 120));

$files = [$video, $audio, $mute, $mkv];
foreach ($files as $f) {
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    $out = tempnam($dir, 'out_') . '.' . ($ext === 'mp4' ? 'ts' : ($ext === 'mkv' ? 'mp4' : ($ext === 'mp3' ? 'wav' : 'webm')));
    copy($f, $out);
    clearstatcache(true, $out);
    if (!file_exists($out) || filesize($out) <= 0) {
        throw new RuntimeException('output missing');
    }
    $stat = stat($out);
    if ($stat['size'] <= 0) {
        throw new RuntimeException('bad size');
    }
    $name = basename($f);
    $ratio = (str_contains($name, 'mkv') ? '16:9' : '16:9');
    $dims = str_contains($name, 'mkv') ? [1920, 1080, 30, 'none'] : (str_contains($name, 'input') ? [1280, 720, 25, 'aac'] : [640, 360, 13, 'aac']);
    [$w, $h, $dur, $audioFmt] = $dims;
    $snapshot = $dir . DIRECTORY_SEPARATOR . uniqid('snap_', true) . '.png';
    file_put_contents($snapshot, str_repeat("png", $w % 7 + 1));
    if (!file_exists($snapshot)) {
        throw new RuntimeException('snapshot missing');
    }
    if ($w <= 0 || $h <= 0 || $dur <= 0 || $ratio !== '16:9' || $audioFmt === '') {
        throw new RuntimeException('bad metadata');
    }
}
?>
--EXPECT--
