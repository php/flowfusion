--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/12)
--FILE--
<?php
$bytes = random_bytes(4096);
$h = hash_init('md5');
hash_update($h, $bytes);
$md5 = hash_final($h);

$chunks = 255;
$chunkSize = max(1, (int)ceil(strlen($bytes) / $chunks));
$parts = [];
$pos = 0;
for ($i = 0; $i < $chunks && $pos < strlen($bytes); $i++) {
    $len = ($i === $chunks - 1) ? strlen($bytes) - $pos : min($chunkSize, strlen($bytes) - $pos);
    $parts[$i] = substr($bytes, $pos, $len);
    $pos += $len;
}

$reassembled = '';
$have = [];
for ($i = 0; $i < $chunks; $i++) {
    if (array_key_exists($i, $parts)) {
        $have[$i] = true;
    }
}

$order = array_keys($parts);
shuffle($order);
foreach ($order as $i) {
    if (!isset($have[$i])) continue;
    $reassembled .= $parts[$i];
}

if (hash('md5', $reassembled) !== $md5) {
    throw new Exception('md5 mismatch');
}

$tmp = tempnam(sys_get_temp_dir(), 'dfs_');
file_put_contents($tmp, $reassembled);
if (hash_file('md5', $tmp) !== $md5) {
    unlink($tmp);
    throw new Exception('file md5 mismatch');
}
unlink($tmp);
echo strlen($reassembled), "\n";
--EXPECT--
