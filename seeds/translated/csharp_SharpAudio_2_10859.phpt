--TEST--
Translated seed (inferredbugs-csharp: SharpAudio/2)
--FILE--
<?php
$mp3 = __DIR__ . '/test.mp3';
if (!is_file($mp3)) {
    $mp3 = tempnam(sys_get_temp_dir(), 'mp3_');
    file_put_contents($mp3, str_repeat("\0", 16));
}

$h = fopen($mp3, 'rb');
if ($h === false) {
    exit(0);
}
stream_get_contents($h);
fclose($h);

$h = fopen($mp3, 'rb');
if ($h === false) {
    exit(0);
}
stream_get_contents($h);
fclose($h);

unlink($mp3);
?>
--EXPECT--
