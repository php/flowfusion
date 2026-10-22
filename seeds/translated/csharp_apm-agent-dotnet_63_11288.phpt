--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/63)
--FILE--
<?php
$transactions = [];
$spans = [];
$errors = [];

$transaction = [
    'name' => 'TestTransaction',
    'type' => 'UnitTest',
    'id' => bin2hex(random_bytes(8)),
    'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'],
    'context' => ['tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2']],
    'spans' => []
];

$span1 = [
    'name' => 'TestSpan1',
    'type' => 'TestSpan!',
    'id' => bin2hex(random_bytes(8)),
    'parentId' => $transaction['id'],
    'transactionId' => $transaction['id'],
    'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2'],
    'context' => ['tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']],
    'timestamp' => microtime(true)
];

usleep(5000);

$span2 = [
    'name' => 'TestSpan1',
    'type' => 'TestSpan!',
    'id' => bin2hex(random_bytes(8)),
    'parentId' => $span1['id'],
    'transactionId' => $transaction['id'],
    'tags' => [],
    'context' => ['tags' => []],
    'timestamp' => microtime(true)
];

usleep(5000);

$span2['duration'] = 5;
$span1['duration'] = 10;
$transaction['duration'] = 15;

$transaction['spans'][] = $span1;
$transaction['spans'][] = $span2;

$transactions[] = $transaction;
$spans[] = $span1;
$spans[] = $span2;

$orderedSpans = $spans;
usort($orderedSpans, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

$firstSpan = $orderedSpans[0];
$innerSpan = $orderedSpans[count($orderedSpans) - 1];

$transactions[] = [
    'name' => 'TestTransaction',
    'type' => 'UnitTest',
    'id' => bin2hex(random_bytes(8)),
    'result' => 'success'
];

$errors[] = ['message' => 'Foo!', 'culprit' => 'PublicAPI-CaptureException'];
$errors[] = ['message' => 'Foo!', 'culprit' => 'TestCulprit'];

echo '';
?>
--EXPECT--
