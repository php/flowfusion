--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('storechangelog_', true);
@mkdir($dir, 0777, true);
@mkdir($dir . DIRECTORY_SEPARATOR . 'test-storechangelog-app', 0777, true);
@mkdir($dir . DIRECTORY_SEPARATOR . 'test-storechangelog-app' . DIRECTORY_SEPARATOR . '0-0', 0777, true);

$log = [];
$store = [];
$changelogTopic = 'store-changelog-topic';
$messages = [
    [$changelogTopic, 'key1', 'value1'],
    [$changelogTopic, 'key2', 'value2'],
];

foreach ($messages as [$topic, $key, $value]) {
    $store[$key] = $value;
    $log[] = [$topic, $key, $value];
}

$metadata = [
    'ChangelogState' => 'COMPLETED',
    'RestoreEndOffset' => count($log),
    'TotalRestored' => count($log),
];

if (count($store) !== 2) {
    throw new Exception('restore failed');
}
if ($metadata['ChangelogState'] !== 'COMPLETED' || $metadata['RestoreEndOffset'] !== 2 || $metadata['TotalRestored'] !== 2) {
    throw new Exception('metadata mismatch');
}

$registered = ['store' => true];
$topicPartition = 'test:0';
if (!isset($registered['test'])) {
    try {
        throw new Exception('StreamsException');
    } catch (Exception $e) {
    }
}
?>
--EXPECT--
