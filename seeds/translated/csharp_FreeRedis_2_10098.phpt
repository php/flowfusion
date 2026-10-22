--TEST--
Translated seed (inferredbugs-csharp: FreeRedis/2)
--FILE--
<?php
$flag = 0;
$sendQueue = new SplQueue();
$receiverQueue = new SplQueue();
$sendTask = null;
$count = 0;
$frequence = 20000;
$sw = hrtime(true);

for ($i = 0; $i < 100000; $i++) {
    $key = (string)$i;
    $v = "Natasha\r\nNatasha";
    if ($v !== "Natasha\r\nNatasha") {
        throw new Exception("not equal");
    }
}

for ($i = 0; $i < 100000; $i++) {
    $key = (string)$i;
    $v = $key;
    if ($v !== $key) {
        throw new Exception("not equal");
    }
}

for ($i = 0; $i < 100000; $i++) {
    $key = (string)$i;
    $result = ($key === $key);
    if (!$result) {
        throw new Exception("not equal");
    }
}

for ($i = 0; $i < 100000; $i++) {
    $key = (string)$i;
    $result = ($key === $key);
    if (!$result) {
        throw new Exception("not equal");
    }
}

for ($i = 0; $i < 10000; $i++) {
    $bytes = (string)$i;
    $task = [$bytes, true];
    $sendQueue->enqueue($task);
    if ($sendTask === null) {
        $sendTask = true;
    }
    while (!$sendQueue->isEmpty()) {
        $task = $sendQueue->dequeue();
        $receiverQueue->enqueue($task[1]);
    }
}

for ($i = 0; $i < $frequence; $i++) {
    $count += substr_count("PONG+OK", "+");
}
if ($count === $frequence) {
    $elapsed = (hrtime(true) - $sw) / 1000000;
    echo "OK {$elapsed}ms\n";
}
?>
--EXPECT--
