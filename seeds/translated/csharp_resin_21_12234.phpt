--TEST--
Translated seed (inferredbugs-csharp: resin/21)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'readsession_' . bin2hex(random_bytes(4));
@mkdir($dir);

$version = 1;
file_put_contents($dir . DIRECTORY_SEPARATOR . $version . '.ix', json_encode([
    'PostingsOffset' => 0,
    'DocHashOffset' => 0,
    'DocAddressesOffset' => 0,
    'Compression' => 0,
]));
file_put_contents($dir . DIRECTORY_SEPARATOR . $version . '.dtbl', "doc");
file_put_contents($dir . DIRECTORY_SEPARATOR . $version . '.kix', "key");
file_put_contents($dir . DIRECTORY_SEPARATOR . $version . '.rdb', str_repeat("\0", 16));

$ix = json_decode(file_get_contents($dir . DIRECTORY_SEPARATOR . $version . '.ix'), true);
$keyIndex = file_get_contents($dir . DIRECTORY_SEPARATOR . $version . '.kix');

$compoundFileName = $dir . DIRECTORY_SEPARATOR . $version . '.rdb';
$compoundFile = fopen($compoundFileName, 'rb');
fseek($compoundFile, $ix['PostingsOffset']);
fseek($compoundFile, $ix['DocHashOffset']);
fseek($compoundFile, $ix['DocAddressesOffset']);

$docFileName = $dir . DIRECTORY_SEPARATOR . $version . '.dtbl';
$docFile = fopen($docFileName, 'rb');
stream_set_read_buffer($compoundFile, 4096);
stream_set_read_buffer($docFile, 4096);

fread($compoundFile, 1);
fread($docFile, 1);

fclose($docFile);
fclose($compoundFile);
?>
--EXPECT--
