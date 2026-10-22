--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/5)
--FILE--
<?php
$base = sys_get_temp_dir() . '/storechangelog_' . bin2hex(random_bytes(6));
@mkdir($base, 0777, true);
$stateDir = $base;
$appId = 'test-storechangelog-app';
$checkpointDir = $stateDir . '/' . $appId . '/0-0';
@mkdir($checkpointDir, 0777, true);
$checkpointFile = $checkpointDir . '/.checkpoint';
$topic = 'store-changelog-topic';
$storeFile = $stateDir . '/store.json';

$messages = [
    [$topic, 'key1', 'value1'],
    [$topic, 'key2', 'value2'],
];

$loadStore = function () use ($storeFile) {
    if (is_file($storeFile)) {
        $d = json_decode(file_get_contents($storeFile), true);
        return is_array($d) ? $d : [];
    }
    return [];
};

$saveStore = function ($store) use ($storeFile) {
    file_put_contents($storeFile, json_encode($store));
};

$restore = function () use (&$messages, &$loadStore, &$saveStore, $checkpointFile, $topic, $stateDir, $appId) {
    $store = $loadStore();
    $offset = -1;
    if (is_file($checkpointFile)) {
        $lines = file($checkpointFile, FILE_IGNORE_NEW_LINES);
        if (isset($lines[2])) {
            $parts = preg_split('/\s+/', trim($lines[2]));
            if (count($parts) >= 3 && $parts[0] === $topic) {
                $offset = max(-1, (int)$parts[2]);
            }
        }
    }
    for ($i = $offset + 1; $i < count($messages); $i++) {
        [$t, $k, $v] = $messages[$i];
        $store[$k] = $v;
    }
    $saveStore($store);
    return $store;
};

$store = $restore();
file_put_contents($checkpointFile, "0\n1\n$topic 0 2\n");
$store = $restore();

file_put_contents($checkpointFile, "0\n1\n$topic 0 -100\n");
$store = $restore();

$store = array_merge($store, ['key3' => 'value3', 'key4' => 'value4']);
$saveStore($store);

if (count($store) !== 4) {
    throw new RuntimeException('unexpected store size');
}

@unlink($checkpointFile);
@unlink($storeFile);
@rmdir($checkpointDir);
@rmdir($stateDir . '/' . $appId);
@rmdir($stateDir);
?>
--EXPECT--
