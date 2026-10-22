--TEST--
Translated seed (inferredbugs-csharp: resin/41)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_term_index_' . bin2hex(random_bytes(4));
@mkdir($dir);

$dirty = [];
$writers = [];

for ($i = 0; $i < 3; $i++) {
    $keyId = 42;
    if (!isset($dirty[$keyId])) $dirty[$keyId] = [];
    $dirty[$keyId][] = ['docId' => $i, 'vector' => [$i, $i + 1, $i + 2]];
}

$pos = fopen($dir . DIRECTORY_SEPARATOR . '1.pos', 'ab');
$vec = fopen($dir . DIRECTORY_SEPARATOR . '1.vec', 'ab');

foreach ($dirty as $keyId => $column) {
    if (!isset($writers[$keyId])) $writers[$keyId] = ['count' => 0];
    foreach ($column as $item) {
        fwrite($vec, pack('l*', ...$item['vector']));
        fwrite($pos, pack('l', $item['docId']));
        $writers[$keyId]['count']++;
    }
}

$dirty = [];

fclose($pos);
fclose($vec);

echo "ok\n";
?>
--EXPECT--
