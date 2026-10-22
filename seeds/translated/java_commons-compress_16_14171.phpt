--TEST--
Translated seed (inferredbugs-java: commons-compress/16)
--FILE--
<?php

$output = 'bla.cpio';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$fp = fopen($output, 'wb');
$archive = fopen('php://temp', 'r+');

fwrite($archive, pack('C*', 0xC7, 0x00, 0x00)); // Simulate CPIO header for test1.xml
fwrite($archive, pack('N', filesize($file1)));
fwrite($archive, file_get_contents($file1));
fwrite($archive, "\0"); // End entry

fwrite($archive, pack('C*', 0xC7, 0x00, 0x00)); // Simulate CPIO header for test2.xml
fwrite($archive, pack('N', filesize($file2)));
fwrite($archive, file_get_contents($file2));
fwrite($archive, "\0"); // End entry

rewind($archive);
fwrite($fp, stream_get_contents($archive));
fclose($fp);
fclose($archive);

$input = fopen($output, 'rb');
$archive = fopen('php://temp', 'r+');
fwrite($archive, stream_get_contents($input));
rewind($archive);
$entry = fread($archive, 4); // Read the header size
$size = unpack('N', fread($archive, 4))[1]; // Get size of the first file
$contents = fread($archive, $size);
file_put_contents('unarchived_test1.xml', $contents);
fclose($input);
fclose($archive);
--EXPECT--
