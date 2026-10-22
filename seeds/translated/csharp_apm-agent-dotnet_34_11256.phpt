--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/34)
--FILE--
<?php
$transactionName = 'TestTransaction';
$transactionType = 'UnitTest';
$spanName = 'TestSpan';
$exceptionMessage = 'Foo!';

$transactions = [];
$spans = [];
$errors = [];

$tx = [
    'name' => $transactionName,
    'type' => $transactionType,
    'result' => null,
    'tags' => [],
    'context' => ['tags' => []],
    'spans' => [],
    'started' => microtime(true),
];

$tx['tags']['fooTransaction1'] = 'barTransaction1';
$tx['tags']['fooTransaction2'] = 'barTransaction2';
$tx['context']['tags'] = $tx['tags'];

$span = [
    'name' => $spanName,
    'type' => 'external',
    'subtype' => null,
    'action' => null,
    'tags' => [],
    'context' => ['tags' => []],
    'started' => microtime(true),
];

$span['tags']['fooSpan1'] = 'barSpan1';
$span['tags']['fooSpan2'] = 'barSpan2';
$span['context']['tags'] = $span['tags'];

usleep(5000);

try {
    throw new InvalidArgumentException($exceptionMessage);
} catch (Throwable $e) {
    $errors[] = ['exception' => ['message' => $e->getMessage()], 'culprit' => 'PublicAPI-CaptureException'];
}

$span['duration'] = (microtime(true) - $span['started']) * 1000;
$tx['spans'][] = $span;
$tx['duration'] = (microtime(true) - $tx['started']) * 1000;
$transactions[] = $tx;
$spans = $tx['spans'];

if (!empty($transactions) && !empty($errors) && $transactions[0]['name'] === $transactionName && $transactions[0]['type'] === $transactionType && $errors[0]['exception']['message'] === $exceptionMessage && isset($transactions[0]['tags']['fooTransaction1']) && isset($spans[0]['tags']['fooSpan1'])) {
    echo '';
}
?>
--EXPECT--
