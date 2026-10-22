--TEST--
Translated seed (inferredbugs-java: commons-compress/26)
--FILE--
<?php

$input = 'test.txt';
$output = 'test.txt.bz2';
$inputStream = fopen($input, 'rb');
$outputStream = fopen($output, 'wb');

stream_filter_append($outputStream, 'bzip2.compress');
stream_copy_to_stream($inputStream, $outputStream);

fclose($inputStream);
fclose($outputStream);

$input = 'bla.txt.bz2';
$output = 'bla.txt';
$inputStream = fopen($input, 'rb');
$outputStream = fopen($output, 'wb');

stream_filter_append($inputStream, 'bzip2.decompress');
stream_copy_to_stream($inputStream, $outputStream);

fclose($inputStream);
fclose($outputStream);

?>
--EXPECT--
