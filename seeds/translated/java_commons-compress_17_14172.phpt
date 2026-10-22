--TEST--
Translated seed (inferredbugs-java: commons-compress/17)
--FILE--
<?php

$output = 'bla.cpio';

$file1 = 'test1.xml';
$file2 = 'test2.xml';

$fp = fopen($output, 'wb');
fwrite($fp, pack('L', filesize($file1)).$file1);
fwrite($fp, pack('L', filesize($file2)).$file2);
fclose($fp);

$input = fopen($output, 'rb');
$entry1_size = unpack('L', fread($input, 4))[1];
$entry1_name = fread($input, strlen($file1));
$target1 = fopen($entry1_name, 'wb');
fwrite($target1, fread($input, $entry1_size));
fclose($target1);

$entry2_size = unpack('L', fread($input, 4))[1];
$entry2_name = fread($input, strlen($file2));
$target2 = fopen($entry2_name, 'wb');
fwrite($target2, fread($input, $entry2_size));
fclose($target2);

fclose($input);
?>
--EXPECT--
