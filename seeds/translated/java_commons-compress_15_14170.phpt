--TEST--
Translated seed (inferredbugs-java: commons-compress/15)
--FILE--
<?php

$output = tempnam(sys_get_temp_dir(), 'bla') . '.cpio';
$file1 = tempnam(sys_get_temp_dir(), 'test1') . '.xml';
$file2 = tempnam(sys_get_temp_dir(), 'test2') . '.xml';

file_put_contents($file1, 'content of test1');
file_put_contents($file2, 'content of test2');

$archive = fopen($output, 'wb');

fwrite($archive, 'CPIO_HEADER'); // Mock header (not functional)

fwrite($archive, pack('N', strlen($file1)) . $file1); // Mock entry for file1
fwrite($archive, pack('N', strlen($file2)) . $file2); // Mock entry for file2

fclose($archive);

$input = fopen($output, 'rb');
$entry1_size = unpack('N', fread($input, 4))[1];
fread($input, $entry1_size); // Read file1 content

$entry2_size = unpack('N', fread($input, 4))[1];
fread($input, $entry2_size); // Read file2 content

fclose($input);
--EXPECT--
