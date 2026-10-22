--TEST--
Translated seed (inferredbugs-csharp: resin/40)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'session_factory_' . getmypid();
if (!is_dir($dir)) mkdir($dir, 0777, true);

$collectionId = 1234567890123456789;
$file = $dir . DIRECTORY_SEPARATOR . $collectionId . '.kmap';
$fh = fopen($file, 'wb');
fwrite($fh, pack('P', 42));
fwrite($fh, pack('P', 42));
fclose($fh);

$keys = [];
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.kmap') as $keyFile) {
    $collectionId2 = (int)basename($keyFile, '.kmap');
    if (!isset($keys[$collectionId2])) $keys[$collectionId2] = [];
    $fh = fopen($keyFile, 'rb');
    $i = 0;
    while (($buf = fread($fh, PHP_INT_SIZE)) !== '' && strlen($buf) === PHP_INT_SIZE) {
        $u = unpack('P', $buf)[1];
        if (!array_key_exists($u, $keys[$collectionId2])) $keys[$collectionId2][$u] = $i++;
    }
    fclose($fh);
}

$keyHash = 42;
if (!isset($keys[$collectionId][$keyHash])) {
    $keys[$collectionId][$keyHash] = 0;
    $fh = fopen($file, 'ab');
    fwrite($fh, pack('P', $keyHash));
    fclose($fh);
}

$docFile = $dir . DIRECTORY_SEPARATOR . 'test.dix';
$fh = fopen($docFile, 'wb');
fwrite($fh, str_repeat("\0", PHP_INT_SIZE + 4));
fclose($fh);

$count = file_exists($docFile) ? intdiv(filesize($docFile), PHP_INT_SIZE + 4) : 0;
echo $count;
?>
--EXPECT--
