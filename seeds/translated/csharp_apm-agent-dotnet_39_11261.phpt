--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/39)
--FILE--
<?php
$ctx = [];
$start = microtime(true);
$ctx['tx'] = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'spans' => [], 'errors' => []];
usleep(5000);
$ctx['tx']['spans'][] = ['name' => 'TestSpan', 'type' => 'external', 'subtype' => null, 'action' => null, 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2'], 'errors' => [], 'started' => microtime(true)];
try { throw new InvalidArgumentException('Foo!'); } catch (Throwable $e) { $ctx['tx']['spans'][0]['errors'][] = ['message' => $e->getMessage()]; $ctx['tx']['errors'][] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException']; }
$ctx['tx']['spans'][0]['duration'] = (microtime(true) - $ctx['tx']['spans'][0]['started']) * 1000;
$ctx['tx']['duration'] = (microtime(true) - $start) * 1000;
$payloads = [$ctx];
echo '';
?>
--EXPECT--
