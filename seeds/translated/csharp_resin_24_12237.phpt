--TEST--
Translated seed (inferredbugs-csharp: resin/24)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'doc_table_' . bin2hex(random_bytes(4));
mkdir($dir);

file_put_contents($dir . DIRECTORY_SEPARATOR . '2.ix', "2");
file_put_contents($dir . DIRECTORY_SEPARATOR . '10.ix', "10");
file_put_contents($dir . DIRECTORY_SEPARATOR . '2.rdb', "compound");
file_put_contents($dir . DIRECTORY_SEPARATOR . '10.rdb', "compound");

$files = glob($dir . DIRECTORY_SEPARATOR . '*.ix');
$versions = array_map(fn($f) => (int)pathinfo($f, PATHINFO_FILENAME), $files);
sort($versions, SORT_NUMERIC);
$version = $versions[0];

$compoundFileName = $dir . DIRECTORY_SEPARATOR . $version . '.rdb';
$compoundFile = fopen($compoundFileName, 'rb');

$ixFile = $dir . DIRECTORY_SEPARATOR . '10.ix';
$ix = ['DocHashOffset' => 0, 'DocAddressesOffset' => 4];

fseek($compoundFile, $ix['DocHashOffset']);
fread($compoundFile, 4);
fseek($compoundFile, $ix['DocAddressesOffset']);
fread($compoundFile, 4);

fclose($compoundFile);

foreach (glob($dir . DIRECTORY_SEPARATOR . '*') as $f) unlink($f);
rmdir($dir);
?>
--EXPECT--
