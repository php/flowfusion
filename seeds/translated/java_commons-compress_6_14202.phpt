--TEST--
Translated seed (inferredbugs-java: commons-compress/6)
--FILE--
<?php
$inputFile = 'test.txt';
$outputFile = 'bla.txt.bz2';
$compressedOutput = bzcompress(file_get_contents($inputFile));
file_put_contents($outputFile, $compressedOutput);

$uncompressedOutput = bzdecompress(file_get_contents($outputFile));
file_put_contents('test-entpackt.txt', $uncompressedOutput);
?>
--EXPECT--
