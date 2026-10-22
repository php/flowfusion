--TEST--
Translated seed (inferredbugs-csharp: P/3)
--FILE--
<?php
$queue = [];
$receiveSet = [];
$maxBufferSize = 1;
$currentStatus = 'Enabled';
$currentTrigger = null;
$currentPayload = null;

$event = ['name' => 'E', 'payloadType' => 'int'];
$source = ['name' => 'S', 'instanceNumber' => 1];

$enqueue = function($e, $arg) use (&$queue, &$receiveSet, &$currentStatus, $maxBufferSize, $source) {
    if ($e === null) {
        throw new Exception('Enqueued event must be non-null');
    }
    if ($arg !== null && !is_int($arg)) {
        throw new Exception('Payload type mismatch');
    }
    if ($currentStatus === 'Halted') {
        return;
    }
    $queue[] = [$e, $arg];
    if ($maxBufferSize !== -1 && count($queue) > $maxBufferSize) {
        throw new Exception('Event Buffer Size Exceeded');
    }
    if ($currentStatus === 'Blocked' && count($queue) > 0) {
        $currentStatus = 'Enabled';
    }
};

$dequeue = function($hasNullTransition) use (&$queue, &$receiveSet, &$currentStatus, &$currentTrigger, &$currentPayload) {
    if (count($queue) > 0) {
        [$currentTrigger, $currentPayload] = array_shift($queue);
        $receiveSet = [];
        return 'SUCCESS';
    } elseif ($hasNullTransition || isset($receiveSet[spl_object_id((object)$currentTrigger)])) {
        $currentPayload = null;
        $currentTrigger = null;
        $receiveSet = [];
        return 'NULL';
    } else {
        $currentStatus = 'Blocked';
        return 'BLOCKED';
    }
};

$enqueue($event, 1);
try {
    $enqueue($event, 2);
} catch (Throwable $e) {
}
$dequeue(false);
$dequeue(true);
echo "ok\n";
?>
--EXPECT--
