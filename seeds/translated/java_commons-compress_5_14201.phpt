--TEST--
Translated seed (inferredbugs-java: commons-compress/5)
--FILE--
<?php
$output = 'bla.txt.bz2';
$dir = __DIR__;
$file1 = 'test.txt';
$input = $dir . '/' . $output;

file_put_contents($output, bzcompress(file_get_contents($file1)));

$outputUnarchive = 'test-entpackt.txt';
file_put_contents($outputUnarchive, bzdecompress(file_get_contents($input)));
?>
--EXPECT--
