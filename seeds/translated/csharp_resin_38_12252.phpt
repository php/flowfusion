--TEST--
Translated seed (inferredbugs-csharp: resin/38)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_session_factory_' . bin2hex(random_bytes(4));
if (!is_dir($dir)) mkdir($dir, 0777, true);

$collectionId = 18446744073709551615;
$keyHash = 9223372036854775807;
$keyFile = $dir . DIRECTORY_SEPARATOR . $collectionId . '.kmap';

file_put_contents($keyFile, pack('P', $keyHash) . pack('P', $keyHash));

$keys = [];
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.kmap') as $file) {
    $base = basename($file, '.kmap');
    $cid = (int)$base;
    if (!isset($keys[$cid])) $keys[$cid] = [];
    $data = file_get_contents($file);
    for ($i = 0; $i + 8 <= strlen($data); $i += 8) {
        $u = unpack('P', substr($data, $i, 8))[1];
        if (!isset($keys[$cid][$u])) $keys[$cid][$u] = intdiv($i, 8);
    }
}

if (!isset($keys[$collectionId][$keyHash])) {
    $keys[$collectionId][$keyHash] = 123;
    file_put_contents($keyFile, pack('P', $keyHash), FILE_APPEND);
}

$docFile = $dir . DIRECTORY_SEPARATOR . 'abc.dix';
file_put_contents($docFile, str_repeat("\0", 24));
$count = intdiv(filesize($docFile), 12);

$mapFile = $dir . DIRECTORY_SEPARATOR . 'a:b\\c.bin';
file_put_contents($mapFile, 'x');
$mapName = str_replace([':', '\\'], ['', '_'], $mapFile);
$cache = [];
$cache[$mapName] = file_get_contents($mapFile);

foreach (glob($dir . DIRECTORY_SEPARATOR . $collectionId . '*') as $f) {
    @unlink($f);
}

foreach (glob($dir . DIRECTORY_SEPARATOR . '*.ix') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.ixp') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.vec') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.pos') as $f) @unlink($f);

?>
--EXPECT--
