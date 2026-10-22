--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/37)
--FILE--
<?php
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'spans' => []];
usleep(5000);
$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']];
usleep(5000);
$span['duration'] = 5;
$tx['spans'][] = $span;
$tx['duration'] = 5;
$err = ['message' => 'Foo!', 'culprit' => 'PublicAPI-CaptureException'];
$tx2 = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'error' => $err];
$tx3 = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'current' => null];
$ctx = ['name' => 'TestTransaction', 'type' => 'request'];
$sub = $ctx;
usleep(50000);
$check = [$ctx, $sub];
echo '';
?>
--EXPECT--
