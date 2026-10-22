--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/6)
--FILE--
<?php
$base = sys_get_temp_dir() . '/storechangelog_' . bin2hex(random_bytes(6));
$stateDir = $base;
$appId = 'test-storechangelog-app';
$topic = 'store-changelog-topic';
$storeFile = $stateDir . '/' . $appId . '/0-0/store.json';
$checkpointFile = $stateDir . '/' . $appId . '/0-0/.checkpoint';
@mkdir(dirname($storeFile), 0777, true);

$store = [];
$offset = 0;
file_put_contents($storeFile, json_encode($store));
file_put_contents($checkpointFile, "0\n1\n$topic 0 -100\n");

foreach ([['key1', 'value1'], ['key2', 'value2']] as $p) {
    $offset++;
    $store[$p[0]] = $p[1];
}
file_put_contents($storeFile, json_encode($store));
file_put_contents($checkpointFile, "0\n1\n$topic 0 $offset\n");

foreach ([['key3', 'value3'], ['key4', 'value4']] as $p) {
    $offset++;
    $store[$p[0]] = $p[1];
}
file_put_contents($storeFile, json_encode($store));

$store = json_decode(file_get_contents($storeFile), true) ?: [];
if (count($store) !== 4) {
    throw new RuntimeException('restore failed');
}

@unlink($checkpointFile);
@unlink($storeFile);
@rmdir(dirname($storeFile));
@rmdir(dirname(dirname($storeFile)));
@rmdir($base);
?>
--EXPECT--
