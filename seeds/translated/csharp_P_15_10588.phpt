--TEST--
Translated seed (inferredbugs-csharp: P/15)
--FILE--
<?php
$queue = new SplQueue();
$queue->enqueue([null, null]);
$queue->enqueue([1, 2]);
$queue->enqueue([3, 4]);

$set = [];
$set[""] = true;
$set["0"] = true;
$set[0] = true;
$set[false] = true;
$set[null] = true;

$sum = 0;
while (!$queue->isEmpty()) {
    [$a, $b] = $queue->dequeue();
    if ($a === null || $b === null) {
        $sum += count($set);
        $set[(string)$sum] = true;
    } else {
        $sum += $a + $b;
    }
}

if (isset($set["0"]) && isset($set[0]) && isset($set[false]) && isset($set[null])) {
    $sum += 1;
}

echo $sum, PHP_EOL;
?>
--EXPECT--
