--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/36)
--FILE--
<?php
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'id' => bin2hex(random_bytes(8)), 'start' => microtime(true), 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'spans' => []];
usleep(5000);
$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'start' => microtime(true), 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2'], 'error' => null];
try { throw new InvalidArgumentException('Foo!'); } catch (Throwable $e) { $span['error'] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException']; }
$span['duration'] = (microtime(true) - $span['start']) * 1000;
$tx['spans'][] = $span;
$tx['duration'] = (microtime(true) - $tx['start']) * 1000;
$errors = [$span['error']];
$payload = ['transactions' => [$tx], 'errors' => $errors, 'currentTransaction' => null];
$asyncTx = ['name' => 'TestTransaction', 'type' => 'request'];
$before = $payload['currentTransaction'];
$payload['currentTransaction'] = $asyncTx;
$subtaskBefore = $payload['currentTransaction'];
usleep(50000);
$subtaskAfter = $payload['currentTransaction'];
$after = $payload['currentTransaction'];
echo '';
?>
--EXPECT--
