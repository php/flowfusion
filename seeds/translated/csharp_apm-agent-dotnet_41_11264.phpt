--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/41)
--FILE--
<?php
$start = hrtime(true);
$tx = ['name' => 'TestTransaction', 'type' => 'UnitTest', 'result' => 'success', 'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2']];
$span = ['name' => 'TestSpan', 'type' => 'db', 'subtype' => 'mssql', 'action' => 'query', 'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']];
usleep(5000);
$tx['duration_ms'] = intdiv(hrtime(true) - $start, 1000000);
$span['duration_ms'] = $tx['duration_ms'];
$errors = [];
try {
    throw new InvalidArgumentException('Foo!');
} catch (Throwable $e) {
    $errors[] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException'];
}
$payloads = [['service' => ['name' => 'service'], 'transactions' => [$tx], 'spans' => [$span]]];
echo json_encode(['payloads' => $payloads, 'errors' => $errors], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
