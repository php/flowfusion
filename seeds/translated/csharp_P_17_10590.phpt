--TEST--
Translated seed (inferredbugs-csharp: P/17)
--FILE--
<?php
$buf = [];
$set = [];
$queue = [];
$max = PHP_INT_MAX;
$blocked = false;
$halted = false;
$enabled = true;
$trigger = null;
$payload = null;
$event = ['name' => 'E', 'payloadType' => 'null'];
$sourceSends = ['E' => true];
$targetPerms = ['E' => true];
if ($event === null) {
    throw new Exception("Enqueued event must not be null");
}
if (!isset($sourceSends[$event['name']])) {
    throw new Exception("cannot send");
}
if (!isset($targetPerms[$event['name']])) {
    throw new Exception("permission");
}
if ($event['payloadType'] === 'null') {
    if ($payload !== null) {
        throw new Exception("Did not expect a payload value");
    }
}
if ($halted) {
} else {
    $queue[] = [$event, $payload];
    if (count($queue) > $max) {
        throw new Exception("buffer exceeded");
    }
    if ($blocked) {
        $enabled = true;
    }
}
$dequeued = false;
if (!$dequeued) {
    if ($blocked) {
        throw new Exception("blocked");
    }
    $blocked = true;
}
$steps = 0;
while ($enabled && $steps <= 3) {
    $steps++;
    if ($trigger === null) {
        if (count($queue) > 0) {
            [$currEvent, $payload] = array_shift($queue);
            $trigger = $currEvent['name'];
            $blocked = false;
        } else {
            $blocked = true;
            break;
        }
    }
    if ($trigger === 'E') {
        $trigger = null;
        $payload = null;
    } else {
        $trigger = null;
    }
}
echo "";
?>
--EXPECT--
