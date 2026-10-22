--TEST--
Translated seed (inferredbugs-java: commons-imaging/31)
--FILE--
<?php

$file = 'testfile.bin';
file_put_contents($file, str_repeat("A", 2048));

$start = 1070;
$length = 1000;

if ($start < 0 || $length < 0 || $start + $length < 0 || $start + $length > filesize($file)) {
    throw new Exception("Could not read block (block start: $start, block length: $length, data length: " . filesize($file) . ").");
}

$handle = fopen($file, 'rb');
fseek($handle, $start);
$data = fread($handle, $length);
fclose($handle);
--EXPECT--
