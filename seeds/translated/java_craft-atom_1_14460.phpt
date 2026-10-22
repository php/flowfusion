--TEST--
Translated seed (inferredbugs-java: craft-atom/1)
--FILE--
<?php

$srcFile = 'source.txt';
$dstFile = 'destination.txt';
file_put_contents($srcFile, str_repeat('a', 17000)); // Create a large file

$bufferSize = 16 * 1024;

$in = fopen($srcFile, 'rb');
$out = fopen($dstFile, 'wb');

while ($in !== false && $out !== false && ($bytesRead = fread($in, $bufferSize)) !== false) {
    fwrite($out, $bytesRead);
}

if ($in) fclose($in);
if ($out) fclose($out);

echo "File copy completed.";
--EXPECT--
