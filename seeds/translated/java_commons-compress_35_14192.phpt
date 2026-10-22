--TEST--
Translated seed (inferredbugs-java: commons-compress/35)
--FILE--
<?php

$output = 'bla.ar';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

// Create archive
$out = fopen($output, 'wb');
$archive = fopen('php://temp', 'wb');
fwrite($archive, pack('a*', $file1) . pack('N', filesize($file1)) . file_get_contents($file1));
fwrite($archive, pack('a*', $file2) . pack('N', filesize($file2)) . file_get_contents($file2));
fclose($archive);
fclose($out);

// Unarchive operation
$is = fopen($output, 'rb');
$sum = 0;

while (!feof($is)) {
    $name = fread($is, 256);
    $length = unpack('N', fread($is, 4))[1];
    if ($name === false || $length === false) break;
    $content = fread($is, $length);
    $sum += $length;
}
fclose($is);

echo $sum;
--EXPECT--
