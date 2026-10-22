--TEST--
Translated seed (inferredbugs-csharp: resin/42)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'session_factory_' . bin2hex(random_bytes(4));
if (!is_dir($dir)) mkdir($dir, 0777, true);

$kmap = $dir . DIRECTORY_SEPARATOR . "1.kmap";
file_put_contents($kmap, pack('P', 1) . pack('P', 2) . pack('P', 1));
file_put_contents($dir . DIRECTORY_SEPARATOR . "1.val", "");

$keys = [];
foreach (glob($dir . DIRECTORY_SEPARATOR . "*.kmap") as $keyFile) {
    $collectionId = (int)basename($keyFile, '.kmap');
    if (!isset($keys[$collectionId])) $keys[$collectionId] = [];
    $data = file_get_contents($keyFile);
    $n = intdiv(strlen($data), PHP_INT_SIZE);
    for ($i = 0; $i < $n; $i++) {
        $chunk = substr($data, $i * PHP_INT_SIZE, PHP_INT_SIZE);
        $arr = unpack('P', $chunk);
        $hash = $arr[1];
        if (!isset($keys[$collectionId][$hash])) $keys[$collectionId][$hash] = $i;
    }
}

$collectionId = 1;
$keyHash = 2;
$keyId = $keys[$collectionId][$keyHash] ?? -1;
if ($keyId !== 1) {
    $keys[$collectionId][$keyHash] = 1;
    file_put_contents($kmap, pack('P', $keyHash), FILE_APPEND);
}

$exists = file_exists($dir . DIRECTORY_SEPARATOR . $collectionId . ".val");
$count = $exists ? intdiv(filesize($dir . DIRECTORY_SEPARATOR . "1.kmap"), PHP_INT_SIZE) : 0;

foreach (glob($dir . DIRECTORY_SEPARATOR . "1*") as $f) unlink($f);
rmdir($dir);
echo $count;
?>
--EXPECT--
