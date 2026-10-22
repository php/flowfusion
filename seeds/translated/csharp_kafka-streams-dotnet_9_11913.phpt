--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/9)
--FILE--
<?php
$base = sys_get_temp_dir() . '/php_task_driver_' . bin2hex(random_bytes(4));
@mkdir($base, 0777, true);

$topics = ['source-a', 'source-b', 'global-x', 'source-a'];
$partitionsByTaskId = [];
foreach (array_unique($topics) as $topic) {
    $taskId = 'task-' . crc32($topic . ':0');
    $partitionsByTaskId[$taskId][] = [$topic, 0];
}

$tasks = [];
foreach (['source-a', 'global-x', 'source-b', 'source-a'] as $topicName) {
    $taskId = 'task-' . crc32($topicName . ':0');
    if (!isset($tasks[$taskId])) {
        $stateDir = $base . '/' . $taskId;
        @mkdir($stateDir, 0777, true);
        $tasks[$taskId] = [
            'id' => $taskId,
            'partitions' => $partitionsByTaskId[$taskId],
            'stateDir' => $stateDir,
            'persistent' => true,
            'stores' => ['store-' . $topicName => true],
        ];
    }
}

$found = null;
foreach ($tasks as $task) {
    if (isset($task['stores']['store-source-a'])) {
        $found = $task['stores']['store-source-a'];
        break;
    }
}

foreach ($tasks as $task) {
    if ($task['persistent'] && is_dir($task['stateDir'])) {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($task['stateDir'], FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $file) {
            $file->isDir() ? @rmdir($file->getPathname()) : @unlink($file->getPathname());
        }
        @rmdir($task['stateDir']);
    }
}

@rmdir($base);
?>
--EXPECT--
