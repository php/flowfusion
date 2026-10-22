--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/18)
--FILE--
<?php

$port = 301;
$basePath = sys_get_temp_dir() . '/vanilla_test_path_' . uniqid();
$items = 100;
$latchCount = 5;

if (!is_dir($basePath)) {
    mkdir($basePath);
}

$source = fopen($basePath . '/source.txt', 'w');
$sink = fopen($basePath . '/sink.txt', 'w');
$latch = $latchCount;
$random = new Random();

$appenderThread = new Thread(function() use ($source, $items, &$latch) {
    for ($i = 1; $i <= $items; $i++) {
        if ($latch > 0) {
            $latch--;
        }

        fwrite($source, pack('P', $i));
        usleep(10000 + rand(0, 80000));
    }
    fclose($source);
});

$appenderThread->start();

while ($latch > 0) {
    usleep(1000);
}

$tailer = fopen($basePath . '/sink.txt', 'r');
for ($i = 1; $i <= $items; $i++) {
    $data = fread($tailer, 8);
    $value = unpack('P', $data)[1];
    fclose($tailer);
}

$appenderThread->join();

?>
--EXPECT--
