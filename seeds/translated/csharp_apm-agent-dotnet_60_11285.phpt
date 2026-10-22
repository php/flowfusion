--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/60)
--FILE--
<?php
$payload = ['transactions' => [], 'spans' => [], 'errors' => []];

$transaction = [
    'id' => bin2hex(random_bytes(8)),
    'name' => 'TestTransaction',
    'type' => 'UnitTest',
    'result' => 'success',
    'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'],
    'context' => ['tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2']],
    'spans' => []
];

$span1 = [
    'id' => bin2hex(random_bytes(8)),
    'name' => 'TestSpan1',
    'type' => 'TestSpan!',
    'parent_id' => $transaction['id'],
    'transaction_id' => $transaction['id'],
    'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2'],
    'context' => ['tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']],
    'timestamp' => microtime(true)
];

usleep(5000);

$span2 = [
    'id' => bin2hex(random_bytes(8)),
    'name' => 'TestSpan1',
    'type' => 'TestSpan!',
    'parent_id' => $span1['id'],
    'transaction_id' => $transaction['id'],
    'tags' => [],
    'context' => ['tags' => []],
    'timestamp' => microtime(true)
];

$payload['spans'][] = $span1;
$payload['spans'][] = $span2;
$payload['transactions'][] = $transaction;

$orderedSpans = $payload['spans'];
usort($orderedSpans, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

$firstSpan = $orderedSpans[0];
$innerSpan = $orderedSpans[count($orderedSpans) - 1];

$firstSpan['parent_id'];
$innerSpan['parent_id'];
$firstSpan['transaction_id'];
$innerSpan['transaction_id'];
?>
--EXPECT--
