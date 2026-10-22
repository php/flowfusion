--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/11)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_video_bug_' . bin2hex(random_bytes(4));
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
    $info = pathinfo($p);
    $out = tempnam($dir, 'out_') . '.' . ($info['extension'] ?? 'bin');
    copy($p, $out);
    clearstatcache(true, $out);
    file_exists($out);
    filesize($out);
    pathinfo($out, PATHINFO_EXTENSION);
    basename($out);
    unlink($out);
}

$src = $dir . DIRECTORY_SEPARATOR . 'sampleMkv.mkv';
$dst = tempnam($dir, 'snap_') . '.png';
file_put_contents($dst, "png");
clearstatcache(true, $dst);
$w = strlen(file_get_contents($src));
$h = $w * 2;
if ($w > 0 && $h > 0 && file_exists($dst)) {
    unlink($dst);
}

foreach ($files as $name => $_) {
    @unlink($dir . DIRECTORY_SEPARATOR . $name);
}
@rmdir($dir);
?>
--EXPECT--
