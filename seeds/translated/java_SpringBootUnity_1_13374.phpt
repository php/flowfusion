--TEST--
Translated seed (inferredbugs-java: SpringBootUnity/1)
--FILE--
<?php

$directory = 'test_directory/';
if (!is_dir($directory)) {
    mkdir($directory);
}

$filePath = $directory . 'test_file.txt';
$currentTime = time() * 1000;
if (!file_exists($filePath)) {
    echo "file not found: " . basename($filePath) . PHP_EOL;
    echo "Create a new file: " . basename($filePath) . PHP_EOL;
    if (touch($filePath)) {
        echo "Succeeded!" . PHP_EOL;
    } else {
        echo "Create file failed!" . PHP_EOL;
    }
}

if (!touch($filePath, $currentTime)) {
    echo "touch failed: " . basename($filePath) . PHP_EOL;
}
--EXPECT--
