--TEST--
Translated seed (inferredbugs-java: craft-atom/2)
--FILE--
<?php

$bufferSize = 16 * 1024;
$filePath = 'source.txt';
$destPath = 'copy.txt';

file_put_contents($filePath, str_repeat('A', $bufferSize * 2)); // create a source file

$in = fopen($filePath, 'rb');
$out = fopen($destPath, 'wb');

while (!feof($in)) {
    $buffer = fread($in, $bufferSize);
    fwrite($out, $buffer);
}

fclose($in);
fclose($out);

unlink($filePath); // clean up
--EXPECT--
