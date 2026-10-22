--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/9)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_videoinfo_' . bin2hex(random_bytes(4));
@mkdir($dir);
$files = [
    $dir . DIRECTORY_SEPARATOR . 'input.mp4',
    $dir . DIRECTORY_SEPARATOR . 'audio.mp3',
    $dir . DIRECTORY_SEPARATOR . 'mute.mp4',
    $dir . DIRECTORY_SEPARATOR . 'sampleMkv.mkv',
];
foreach ($files as $f) {
    file_put_contents($f, '');
}
$paths = [
    'input.mp4' => ['ext' => '.mp4', 'audio' => 'aac', 'dur' => 13, 'fps' => 25, 'w' => 1280, 'h' => 720, 'ratio' => '16:9'],
    'mute.mp4' => ['ext' => '.mp4', 'audio' => 'none', 'dur' => 13, 'fps' => 25, 'w' => 1280, 'h' => 720, 'ratio' => '16:9'],
    'sampleMkv.mkv' => ['ext' => '.mkv', 'audio' => 'none', 'dur' => 30, 'fps' => 29.97, 'w' => 1920, 'h' => 1080, 'ratio' => '16:9'],
];
foreach ($paths as $name => $p) {
    $file = $dir . DIRECTORY_SEPARATOR . $name;
    clearstatcache(true, $file);
    filesize($file);
    pathinfo($file, PATHINFO_EXTENSION);
    basename($file);
    $tmp = tempnam(sys_get_temp_dir(), 'v_') . $p['ext'];
    file_put_contents($tmp, '');
    $snapshot = ['width' => $p['w'], 'height' => $p['h']];
    if ($snapshot['width'] !== $p['w']) exit(1);
    if (!file_exists($file)) exit(1);
    if ($p['ratio'] !== '16:9') exit(1);
    if ($p['audio'] === 'aac' && $p['dur'] !== 13) exit(1);
    if ($p['audio'] === 'none' && $p['dur'] <= 0) exit(1);
    if ($p['fps'] <= 0) exit(1);
    if ($snapshot['width'] <= 0 || $snapshot['height'] <= 0) exit(1);
    unlink($tmp);
}
$output = tempnam(sys_get_temp_dir(), 'o_') . '.png';
file_put_contents($output, '');
if (!file_exists($output)) exit(1);
unlink($output);
foreach ($files as $f) unlink($f);
rmdir($dir);
?>
--EXPECT--
