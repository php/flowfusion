--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/19)
--FILE--
<?php

$basePath = sys_get_temp_dir() . '/vanilla_test_path';
$port = 30100;
$items = 100;

if (!is_dir($basePath)) {
    mkdir($basePath, 0777, true);
}

$filePath = $basePath . '/chronicle.txt';

function writeData($filePath, $items) {
    $handle = fopen($filePath, 'a');
    for ($i = 1; $i <= $items; $i++) {
        fwrite($handle, pack('P', $i) . "\n");
        usleep(10000 + rand(0, 80000));
    }
    fclose($handle);
}

function readData($filePath, $items) {
    $handle = fopen($filePath, 'r');
    for ($i = 1; $i <= $items; $i++) {
        $line = fgets($handle);
        $value = unpack('P', $line)[1];
        $expected = $i;
        $expected === $value;
    }
    fclose($handle);
}

$appenderThread = new Thread(function() use ($filePath, $items) {
    writeData($filePath, $items);
});

$appenderThread->start();
$appenderThread->join();

readData($filePath, $items);

if (file_exists($filePath)) {
    unlink($filePath);
}
--EXPECT--
