--TEST--
Translated seed (inferredbugs-csharp: resin/39)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'session_factory_' . bin2hex(random_bytes(4));
if (!is_dir($dir)) mkdir($dir, 0777, true);

$collectionId = 18446744073709551615;
$keyFile = $dir . DIRECTORY_SEPARATOR . $collectionId . '.kmap';
file_put_contents($keyFile, pack('P', 0x0102030405060708) . pack('P', 0x1112131415161718));

$keys = [];
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.kmap') as $file) {
    $base = pathinfo($file, PATHINFO_FILENAME);
    $cid = (string)$base;
    if (!isset($keys[$cid])) $keys[$cid] = [];
    $data = file_get_contents($file);
    for ($i = 0, $n = strlen($data); $i + 8 <= $n; $i += 8) {
        $u = unpack('P', substr($data, $i, 8))[1];
        if (!isset($keys[$cid][$u])) $keys[$cid][$u] = intdiv($i, 8);
    }
}

$hash = 0x0102030405060708;
if (!isset($keys[(string)$collectionId][$hash])) {
    $keys[(string)$collectionId][$hash] = 123;
    file_put_contents($keyFile, pack('P', $hash), FILE_APPEND);
}

$docFile = $dir . DIRECTORY_SEPARATOR . 'sample.dix';
file_put_contents($docFile, str_repeat(pack('P', 1) . pack('V', 2), 3));
$count = filesize($docFile) / (PHP_INT_SIZE + 4);

$pageFile = $dir . DIRECTORY_SEPARATOR . 'page.ixp';
file_put_contents($pageFile, pack('P', 10) . pack('P', 20));
$pageInfo = [];
if (!isset($pageInfo[$pageFile])) {
    $data = file_get_contents($pageFile);
    $pageInfo[$pageFile] = [];
    for ($i = 0, $n = strlen($data); $i + 16 <= $n; $i += 16) {
        $pageInfo[$pageFile][] = ['offset' => unpack('P', substr($data, $i, 8))[1], 'length' => unpack('P', substr($data, $i + 8, 8))[1]];
    }
}

foreach (glob($dir . DIRECTORY_SEPARATOR . $collectionId . '*') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . $collectionId . '*.ix') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . $collectionId . '*.ixp') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . $collectionId . '*.vec') as $f) @unlink($f);
foreach (glob($dir . DIRECTORY_SEPARATOR . $collectionId . '*.pos') as $f) @unlink($f);

?>
--EXPECT--
