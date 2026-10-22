--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/3)
--FILE--
<?php
$stateDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('state_', true);
$appId = 'test-storechangelog-app';
$checkpointDir = $stateDir . DIRECTORY_SEPARATOR . $appId . DIRECTORY_SEPARATOR . '0-0';
$checkpointFile = $checkpointDir . DIRECTORY_SEPARATOR . '.checkpoint';
$topic = 'store-changelog-topic';

@mkdir($checkpointDir, 0777, true);

$store = [];
$log = [];

$produce = function(string $k, string $v) use (&$log) {
    $log[] = [$k, $v];
};

$restore = function() use (&$store, &$log) {
    for ($i = 0; $i < count($log); $i++) {
        $store[$log[$i][0]] = $log[$i][1];
    }
};

$checkpoint = function(string $file, int $offset) use ($topic) {
    file_put_contents($file, "0\n1\n{$topic} 0 {$offset}\n");
};

$produce('key1', 'value1');
$produce('key2', 'value2');
$restore();
$checkpoint($checkpointFile, 2);

$produce('key3', 'value3');
$produce('key4', 'value4');
$restore();

@unlink($checkpointFile);
$checkpoint($checkpointFile, -100);
$restore();

$count = count($store);
if ($count !== 4) {
    throw new RuntimeException('unexpected count');
}

@unlink($checkpointFile);
@rmdir($checkpointDir);
@rmdir(dirname($checkpointDir));
@rmdir($stateDir);
--EXPECT--
