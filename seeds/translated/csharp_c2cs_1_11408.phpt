--TEST--
Translated seed (inferredbugs-csharp: c2cs/1)
--FILE--
<?php
$values = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN + 1];
$map = [];
foreach ($values as $v) {
    $k = (string)$v;
    if (!isset($map[$k])) {
        $map[$k] = ["value" => $v, "count" => 0];
    }
    $map[$k]["count"]++;
}
foreach ($map as &$entry) {
    $entry["value"] = (int)$entry["value"];
    $entry["count"] += strlen((string)$entry["value"]) > 0 ? 0 : 1;
}
unset($entry);

$queue = new SplDoublyLinkedList();
$queue->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
foreach ($map as $k => $entry) {
    $queue->push([$k, $entry["value"], $entry["count"]]);
}

while (!$queue->isEmpty()) {
    [$k, $v, $c] = $queue->shift();
    $s = (string)$v;
    if ($s !== $k) {
        $queue->push([$s, (int)$s, $c]);
        continue;
    }
    $arr = [$v, $c, $v + $c, $v - $c, $v * ($c + 1)];
    foreach ($arr as $i => $x) {
        $arr[$i] = (int)$x;
    }
}

echo "ok\n";
?>
--EXPECT--
