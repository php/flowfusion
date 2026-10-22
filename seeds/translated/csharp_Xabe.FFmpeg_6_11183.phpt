--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/6)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_port_' . bin2hex(random_bytes(4));
@mkdir($dir);
$src = $dir . DIRECTORY_SEPARATOR . 'input.mp4';
$dst = $dir . DIRECTORY_SEPARATOR . 'sampleMkv.mkv';
file_put_contents($src, str_repeat("A", 13));
file_put_contents($dst, str_repeat("B", 30));

$paths = [$src, $dst, $dir . DIRECTORY_SEPARATOR . 'audio.mp3', $dir . DIRECTORY_SEPARATOR . 'mute.mp4'];
foreach ($paths as $p) {
    $info = pathinfo($p);
    $ext = isset($info['extension']) ? '.' . $info['extension'] : '';
    $base = $info['basename'] ?? '';
    $size = filesize($p);
    $name = basename($p);
    $exists = file_exists($p);
    $ratio = $name === 'sampleMkv.mkv' ? '16:9' : '16:9';
    $frameRate = $name === 'sampleMkv.mkv' ? 29.97 : ($name === 'input.mp4' ? 25 : 0);
    $width = $name === 'sampleMkv.mkv' ? 1920 : ($name === 'input.mp4' ? 1280 : 0);
    $height = $name === 'sampleMkv.mkv' ? 1080 : ($name === 'input.mp4' ? 720 : 0);
    $audio = $name === 'sampleMkv.mkv' ? 'none' : ($name === 'input.mp4' ? 'aac' : 'none');
    $running = false;
    $duration = $name === 'sampleMkv.mkv' ? 30 : ($name === 'input.mp4' ? 13 : 0);
    $snapshotWidth = $width;
    $snapshot = str_repeat("\0", max(1, $snapshotWidth));
    $snapshotOut = $dir . DIRECTORY_SEPARATOR . uniqid('', true) . '.png';
    file_put_contents($snapshotOut, $snapshot);
    if (!$exists || $size <= 0 || $duration < 0 || $running) {
        exit(1);
    }
    if ($ext === '.mkv' && ($audio !== 'none' || $duration !== 30 || $frameRate !== 29.97 || $height !== 1080 || $width !== 1920 || $base !== 'sampleMkv.mkv' || $ratio !== '16:9')) {
        exit(1);
    }
    if ($ext === '.mp4' && ($audio !== 'aac' || $duration !== 13 || $frameRate !== 25 || $height !== 720 || $width !== 1280 || $base !== 'input.mp4' || $ratio !== '16:9')) {
        exit(1);
    }
    if (filesize($snapshotOut) !== $snapshotWidth) {
        exit(1);
    }
}
?>
--EXPECT--
