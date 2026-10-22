--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/54)
--FILE--
<?php
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'spans' => []];
usleep(5000);
$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']];
$span['duration'] = 5;
$tx['spans'][] = $span;
$tx['duration'] = 5;
$error = ['message' => 'Foo!', 'culprit' => 'PublicAPI-CaptureException'];
$payload = ['transactions' => [$tx], 'spans' => $tx['spans'], 'errors' => [$error], 'currentTransaction' => null];
echo '';
?>
--EXPECT--
