--TEST--
Translated seed (inferredbugs-csharp: resin/43)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'session_factory_' . bin2hex(random_bytes(4));
if (!is_dir($dir)) mkdir($dir, 0777, true);

$kmap = $dir . DIRECTORY_SEPARATOR . "1.kmap";
file_put_contents($kmap, pack('P', 0x1122334455667788) . pack('P', 0x1122334455667788) . pack('P', 0x99aabbccddeeff00));

$keys = [];
foreach (glob($dir . DIRECTORY_SEPARATOR . "*.kmap") as $keyFile) {
    $collectionId = (int)basename($keyFile, '.kmap');
    if (!isset($keys[$collectionId])) $keys[$collectionId] = [];
    $data = file_get_contents($keyFile);
    for ($i = 0; $i + 8 <= strlen($data); $i += 8) {
        $u = unpack('P', substr($data, $i, 8))[1];
        if (!isset($keys[$collectionId][$u])) $keys[$collectionId][$u] = intdiv($i, 8);
    }
}

$collectionId = 1;
$keyHash = 0x1122334455667788;
if (!isset($keys[$collectionId])) $keys[$collectionId] = [];
if (!array_key_exists($keyHash, $keys[$collectionId])) {
    $keys[$collectionId][$keyHash] = 123;
    $fp = fopen($kmap, 'ab');
    fwrite($fp, pack('P', $keyHash));
    fclose($fp);
}

$doc = $keys[$collectionId][$keyHash];
echo $doc;
?>
--EXPECT--
