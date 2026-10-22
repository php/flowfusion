--TEST--
Translated seed (inferredbugs-csharp: P/1)
--FILE--
<?php
$buf = [];
$max = 3;
for ($i = 0; $i < 4; $i++) {
    $buf[] = $i;
    if ($max !== -1 && count($buf) > $max) {
        $buf = array_slice($buf, 0, $max);
    }
}
$set = [];
$k = "0";
$set[$k] = true;
$trigger = null;
$payload = null;
if ($trigger !== null) {
    $payload = null;
} elseif (isset($set[$trigger])) {
    $payload = null;
} else {
    $status = "blocked";
}
if ($payload === null && $status === "blocked") {
    $status = "enabled";
}
$queue = new SplQueue();
$queue->enqueue(["evt" => "e", "arg" => null]);
$queue->enqueue(["evt" => "e", "arg" => 1]);
while (!$queue->isEmpty()) {
    $x = $queue->dequeue();
    if ($x["arg"] === null) {
        $y = $x["evt"];
    } else {
        $y = $x["arg"] + 1;
    }
}
echo "ok\n";
?>
--EXPECT--
