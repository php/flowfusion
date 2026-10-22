--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/38)
--FILE--
<?php
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'spans' => []];
usleep(5000);
$tx['duration'] = 5;
$tx['id'] = bin2hex(random_bytes(8));

$span = ['name' => 'TestSpan', 'type' => 'external', 'subtype' => 'mssql', 'action' => 'query', 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']];
usleep(5000);
$span['duration'] = 5;
$span['id'] = bin2hex(random_bytes(8));
$tx['spans'][] = $span;

$payloads = [['service' => ['name' => 'service'], 'transactions' => [$tx]]];
$errors = [['errors' => [['exception' => ['message' => 'Foo!'], 'culprit' => 'PublicAPI-CaptureException']]]];

$tx2 = ['name' => 'TestTransaction', 'type' => 'Request'];
$span2 = ['name' => 'TestSpan', 'type' => 'external'];

echo "";
?>
--EXPECT--
