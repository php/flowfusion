--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/12)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_videoinfo_bug_' . bin2hex(random_bytes(4));
@mkdir($dir);
$files = [
    'input.mp4' => "mp4",
    'audio.mp3' => "mp3",
    'mute.mp4' => "mp4",
    'sampleMkv.mkv' => "mkv",
];
foreach ($files as $name => $data) {
    file_put_contents($dir . DIRECTORY_SEPARATOR . $name, $data);
}

$paths = [
    $dir . DIRECTORY_SEPARATOR . 'input.mp4',
    $dir . DIRECTORY_SEPARATOR . 'audio.mp3',
    $dir . DIRECTORY_SEPARATOR . 'mute.mp4',
    $dir . DIRECTORY_SEPARATOR . 'sampleMkv.mkv',
];

foreach ($paths as $p) {
    $pi = pathinfo($p);
    $out = tempnam($dir, 'out_') . '.' . ($pi['extension'] ?? 'tmp');
    copy($p, $out);
    clearstatcache(true, $out);
    file_exists($out);
    filesize($out);
    basename($out);
    $ext = '.' . ($pi['extension'] ?? '');
    $ratio = ($pi['basename'] === 'sampleMkv.mkv') ? '16:9' : '16:9';
    $frameRate = ($pi['basename'] === 'sampleMkv.mkv') ? 29.97 : 25;
    $height = ($pi['basename'] === 'sampleMkv.mkv') ? 1080 : 720;
    $width = ($pi['basename'] === 'sampleMkv.mkv') ? 1920 : 1280;
    $audioFormat = ($pi['basename'] === 'mute.mp4') ? 'none' : (($pi['extension'] ?? '') === 'mp4' ? 'aac' : 'none');
    $duration = ($pi['basename'] === 'sampleMkv.mkv') ? 30 : (($pi['extension'] ?? '') === 'mp4' ? 13 : 0);
    $snapshot = imagecreatetruecolor($width, $height);
    imagesetpixel($snapshot, 0, 0, 0);
    imagepng($snapshot, $out . '.png');
    imagedestroy($snapshot);
}

?>
--EXPECT--
