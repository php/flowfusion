--TEST--
Translated seed (inferredbugs-csharp: SharpAudio/3)
--FILE--
<?php
$path = __DIR__ . '/test.mp3';
if (!is_file($path)) {
    $path = tempnam(sys_get_temp_dir(), 'mp3_');
    file_put_contents($path, '');
}

$h = fopen($path, 'rb');
$data = $h === false ? '' : stream_get_contents($h);
if (is_resource($h)) fclose($h);

$s1 = fopen('php://temp', 'r+');
fwrite($s1, $data);
rewind($s1);
stream_get_contents($s1);
fclose($s1);

$h = fopen($path, 'rb');
$data = $h === false ? '' : stream_get_contents($h);
if (is_resource($h)) fclose($h);

$s2 = fopen('php://temp', 'r+');
fwrite($s2, $data);
rewind($s2);
stream_get_contents($s2);
$volume = 1.0;
fclose($s2);
?>
--EXPECT--
