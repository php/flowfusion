--TEST--
Translated seed (inferredbugs-csharp: k4a.net/2)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, "x");
rewind($h);

$refs = [];
$refs[] = $h;
$refs[] = $h;

$meta = stream_get_meta_data($h);
$pos = ftell($h);
fseek($h, 0, SEEK_END);
fwrite($h, "y");
rewind($h);
$first = fread($h, 2);

$copy = $h;
unset($h);
$meta2 = stream_get_meta_data($copy);
fclose($copy);

echo $meta['mode'] . "\n";
echo $pos . "\n";
echo $first . "\n";
echo (int)$meta2['seekable'] . "\n";
?>
--EXPECT--
