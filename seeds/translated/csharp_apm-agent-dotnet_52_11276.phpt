--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/52)
--FILE--
<?php
$state = ['tx' => null, 'spans' => [], 'payloads' => [], 'errors' => []];

$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => null, 'tags' => [], 'span' => null, 'start' => microtime(true)];
$state['tx'] = &$tx;

usleep(5000);
$tx['result'] = 'success';

$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => [], 'start' => microtime(true)];
$tx['span'] = &$span;
$span['tags']['fooSpan1'] = 'barSpan1';
$span['tags']['fooSpan2'] = 'barSpan2';

try { throw new InvalidArgumentException('Foo!'); } catch (Throwable $e) { $state['errors'][] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException']; }
usleep(5000);

$span['duration'] = (int) ((microtime(true) - $span['start']) * 1000);
$tx['duration'] = (int) ((microtime(true) - $tx['start']) * 1000);

$state['spans'][] = $span;
$state['payloads'][] = ['service' => ['name' => 'svc'], 'transactions' => [$tx], 'spans' => [$span]];

$tx2 = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'], 'start' => microtime(true)];
$span2 = ['name' => 'TestSpan', 'type' => 'external', 'tags' => [], 'start' => microtime(true)];
usleep(5000);
try { throw new RuntimeException('Foo!'); } catch (Throwable $e) { $state['errors'][] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException']; }
$span2['duration'] = (int) ((microtime(true) - $span2['start']) * 1000);
$tx2['duration'] = (int) ((microtime(true) - $tx2['start']) * 1000);
$state['payloads'][] = ['service' => ['name' => 'svc'], 'transactions' => [$tx2], 'spans' => [$span2]];

if (!empty($state['payloads']) && !empty($state['errors'])) {
    echo '';
}
?>
--EXPECT--
