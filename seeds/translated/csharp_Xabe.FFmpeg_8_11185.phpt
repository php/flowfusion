--TEST--
Translated seed (inferredbugs-csharp: Xabe.FFmpeg/8)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_videoinfo_bug_' . bin2hex(random_bytes(4));
@mkdir($dir);
chdir($dir);

file_put_contents('input.mp4', "mp4");
file_put_contents('audio.mp3', "mp3");
file_put_contents('mute.mp4', "mute");
file_put_contents('sampleMkv.mkv', "mkv");

$files = ['input.mp4', 'audio.mp3', 'mute.mp4', 'sampleMkv.mkv'];
foreach ($files as $f) {
    $p = $dir . DIRECTORY_SEPARATOR . $f;
    clearstatcache(true, $p);
    file_exists($p);
    pathinfo($p, PATHINFO_EXTENSION);
    basename($p);
    filesize($p);
}

$tmp = tempnam(sys_get_temp_dir(), 'v');
$targets = [$tmp . '.ts', $tmp . '.webm', $tmp . '.ogv', $tmp . '.mp4', $tmp . '.mp3', $tmp . '.png'];
foreach ($targets as $t) {
    file_put_contents($t, '');
    file_exists($t);
    pathinfo($t, PATHINFO_EXTENSION);
}

$src = $dir . DIRECTORY_SEPARATOR . 'sampleMkv.mkv';
$dst = $tmp . '.mp4';
copy($src, $dst);
rename($dst, $tmp . '.mkv');
copy($dir . DIRECTORY_SEPARATOR . 'input.mp4', $tmp . '.ts');
copy($dir . DIRECTORY_SEPARATOR . 'input.mp4', $tmp . '.webm');
copy($dir . DIRECTORY_SEPARATOR . 'input.mp4', $tmp . '.ogv');
copy($dir . DIRECTORY_SEPARATOR . 'mute.mp4', $tmp . '.mp4');
copy($dir . DIRECTORY_SEPARATOR . 'input.mp4', $tmp . '.mp3');
copy($dir . DIRECTORY_SEPARATOR . 'input.mp4', $tmp . '.png');

foreach (glob($tmp . '.*') as $f) {
    file_exists($f);
    basename($f);
    pathinfo($f, PATHINFO_EXTENSION);
}
?>
--EXPECT--
