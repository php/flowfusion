--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/2)
--FILE--
<?php
$stream = fopen('php://temp', 'w+');
fwrite($stream, "PK\x03\x04");
rewind($stream);
$meta = stream_get_meta_data($stream);
$uri = $meta['uri'];
$bytes = file_get_contents($uri);
$copy = fopen('php://temp', 'w+');
fwrite($copy, $bytes);
rewind($copy);
$stat = fstat($copy);
echo strlen($bytes) . "\n";
echo $stat['size'] . "\n";
echo (int)($bytes[0] === 'P' && $bytes[1] === 'K') . "\n";
?>
--EXPECT--
