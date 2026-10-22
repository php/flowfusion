--TEST--
Translated seed (inferredbugs-csharp: P/21)
--FILE--
<?php
$queue = new SplQueue();
$queue->enqueue([1, 'a']);
$queue->enqueue([1, 'b']);
$queue->enqueue([2, 'c']);

$set = [];
while (!$queue->isEmpty()) {
    [$k, $v] = $queue->dequeue();
    $set[(string)$k] = true;
    $set[$v] = true;
}

$copy = $set;
$copy["1"] = false;
$copy["a"] = false;

$queue2 = new SplQueue();
foreach ($copy as $k => $v) {
    if ($v) {
        $queue2->enqueue($k);
    }
}

while (!$queue2->isEmpty()) {
    $x = $queue2->dequeue();
    if ($x === null) {
        continue;
    }
    $tmp = [$x => 1];
    $tmp[$x] += 1;
}

echo "ok\n";
?>
--EXPECT--
