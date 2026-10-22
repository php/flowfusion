--TEST--
Translated seed (inferredbugs-csharp: Statiq.Framework/3)
--FILE--
<?php
$useStringContentFiles = (bool)random_int(0, 1);
$content = random_int(0, 1) ? null : (random_int(0, 1) ? '' : "abc\0def");
$stream = fopen('php://temp', 'r+');
fwrite($stream, "hello");
rewind($stream);

if ($stream !== null) {
    if (stream_get_meta_data($stream)['seekable']) {
        rewind($stream);
    }
    $buffer = stream_get_contents($stream);
}

if ($content === null) {
    $provider = null;
} elseif ($useStringContentFiles) {
    $temp = tempnam(sys_get_temp_dir(), 'php');
    if ($content !== '') {
        file_put_contents($temp, $content);
    }
    $provider = ['file' => $temp, 'mediaType' => null];
} else {
    $provider = ['string' => $content, 'mediaType' => null];
}

$bytes = random_bytes(8);
$index = 2;
$count = 4;
$segment = substr($bytes, $index, $count);
$mem = fopen('php://memory', 'r+');
fwrite($mem, $segment);
rewind($mem);
$copied = stream_get_contents($mem);

echo "";
?>
--EXPECT--
