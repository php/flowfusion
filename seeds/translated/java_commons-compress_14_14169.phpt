--TEST--
Translated seed (inferredbugs-java: commons-compress/14)
--FILE--
<?php
$output = 'bla.cpio';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$out = fopen($output, 'wb');
$archiveData = '';

$archiveData .= pack('a*', 'test1.xml') . pack('N', filesize($file1)) . file_get_contents($file1);
$archiveData .= pack('a*', 'test2.xml') . pack('N', filesize($file2)) . file_get_contents($file2);

fwrite($out, $archiveData);
fclose($out);

$input = fopen($output, 'rb');
while (!feof($input)) {
    $name = fread($input, 12);
    $size = unpack('N', fread($input, 4))[1];
    $content = fread($input, $size);
    file_put_contents($name, $content);
}
fclose($input);
--EXPECT--
