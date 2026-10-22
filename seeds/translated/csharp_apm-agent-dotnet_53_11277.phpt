--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/53)
--FILE--
<?php
$start = hrtime(true);
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2']];
$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']];
usleep(5000);
$tx['duration'] = intdiv(hrtime(true) - $start, 1000000);
$span['duration'] = $tx['duration'];
$err = null;
try {
    throw new InvalidArgumentException('Foo!');
} catch (Throwable $e) {
    $err = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException'];
}
$payload = ['service' => ['name' => 'test'], 'transactions' => [$tx], 'spans' => [$span], 'errors' => [$err]];
echo '';
?>
--EXPECT--
