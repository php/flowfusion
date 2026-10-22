--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/55)
--FILE--
<?php
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'spans' => []];
usleep(5000);
$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']];
usleep(5000);
$e = null;
try { throw new InvalidArgumentException('Foo!'); } catch (Throwable $t) { $e = $t; }
$span['error'] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException'];
$tx['spans'][] = $span;
echo $tx['name'], $tx['type'], $tx['result'], $tx['spans'][0]['name'], $tx['spans'][0]['type'], $tx['spans'][0]['subtype'], $tx['spans'][0]['action'], $tx['spans'][0]['error']['message'], $tx['spans'][0]['error']['culprit'], $tx['tags']['fooTransaction1'], $tx['tags']['fooTransaction2'], $tx['spans'][0]['tags']['fooSpan1'], $tx['spans'][0]['tags']['fooSpan2'];
?>
--EXPECT--
