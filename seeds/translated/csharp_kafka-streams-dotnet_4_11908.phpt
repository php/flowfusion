--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/4)
--FILE--
<?php
$base = sys_get_temp_dir() . '/storechangelog_' . bin2hex(random_bytes(8));
@mkdir($base, 0777, true);
$checkpointDir = $base . '/test-storechangelog-app/0-0';
@mkdir($checkpointDir, 0777, true);

$log = [];
$store = [];
$offsets = ['store-changelog-topic' => 0];
$metadata = ['state' => 'PENDING', 'end' => 0, 'restored' => 0];

$produce = function (string $topic, string $key, string $value) use (&$log, &$offsets) {
    $log[] = [$topic, $key, $value];
    $offsets[$topic] = count($log);
};

$restore = function () use (&$log, &$store, &$metadata, &$offsets) {
    $start = $offsets['store-changelog-topic'] ?? 0;
    $count = count($log);
    for ($i = $start; $i < $count; $i++) {
        [$topic, $key, $value] = $log[$i];
        if ($topic === 'store-changelog-topic') {
            $store[$key] = $value;
            $metadata['restored']++;
        }
    }
    $metadata['state'] = 'COMPLETED';
    $metadata['end'] = count($log);
    $offsets['store-changelog-topic'] = count($log);
};

$produce('store-changelog-topic', 'key1', 'value1');
$produce('store-changelog-topic', 'key2', 'value2');
$restore();
$checkpoint = "0\n1\nstore-changelog-topic 0 " . $offsets['store-changelog-topic'] . "\n";
file_put_contents($checkpointDir . '/.checkpoint', $checkpoint);

$produce('store-changelog-topic', 'key3', 'value3');
$produce('store-changelog-topic', 'key4', 'value4');
$restore();

file_put_contents($checkpointDir . '/.checkpoint', "0\n1\nstore-changelog-topic 0 -100\n");
$restore();

if (count($store) !== 4 || $metadata['state'] !== 'COMPLETED' || $metadata['end'] !== 4 || $metadata['restored'] !== 4) {
    throw new RuntimeException('unexpected restore result');
}

@unlink($checkpointDir . '/.checkpoint');
@rmdir($checkpointDir);
@rmdir($base . '/test-storechangelog-app');
@rmdir($base);
?>
--EXPECT--
