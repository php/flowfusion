--TEST--
Translated seed (inferredbugs-csharp: resin/22)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'documenttable_' . bin2hex(random_bytes(4));
mkdir($dir);

$version = 1;
$ixPath = $dir . DIRECTORY_SEPARATOR . $version . '.ix';
$rdbPath = $dir . DIRECTORY_SEPARATOR . $version . '.rdb';

$ix = pack('P3', 0, 0, 0);
file_put_contents($ixPath, $ix);
file_put_contents($rdbPath, str_repeat("\0", 16));

$ixData = file_get_contents($ixPath);
$offsets = unpack('Ppostings/Pdochash/Pdocaddresses', $ixData);

$compoundFile = fopen($rdbPath, 'rb');
fseek($compoundFile, $offsets['postings']);
fread($compoundFile, 1);
fseek($compoundFile, $offsets['dochash']);
fread($compoundFile, 1);
fseek($compoundFile, $offsets['docaddresses']);
fread($compoundFile, 1);
fclose($compoundFile);

unlink($ixPath);
unlink($rdbPath);
rmdir($dir);
?>
--EXPECT--
