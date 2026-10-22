--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/50)
--FILE--
<?php
$transactions = [];
$spans = [];
$errors = [];

$transaction = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'tags' => [], 'spans' => [], 'result' => null, 'ended' => false];
$transactions[] = &$transaction;

usleep(5000);

$transaction['result'] = 'success';

$span = ['name' => 'TestSpan', 'type' => 'external', 'subtype' => null, 'action' => null, 'tags' => [], 'ended' => false];
$transaction['spans'][] = &$span;
$spans[] = &$span;

$transaction['tags']['fooTransaction1'] = 'barTransaction1';
$transaction['tags']['fooTransaction2'] = 'barTransaction2';
$span['tags']['fooSpan1'] = 'barSpan1';
$span['tags']['fooSpan2'] = 'barSpan2';

try {
    throw new InvalidArgumentException('Foo!');
} catch (Throwable $e) {
    $errors[] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException'];
}

$span['ended'] = true;
$transaction['ended'] = true;

$transaction2 = ['name' => 'TestTransaction', 'type' => 'Request', 'tags' => [], 'spans' => [], 'ended' => false];
$transactions[] = &$transaction2;
$span2 = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => [], 'ended' => true];
$transaction2['spans'][] = &$span2;
$spans[] = &$span2;
$transaction2['ended'] = true;

echo "";
?>
--EXPECT--
