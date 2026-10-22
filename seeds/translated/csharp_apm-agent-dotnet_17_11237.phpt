--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/17)
--FILE--
<?php
$start = microtime(true);
$tx = ['name' => 'ConvenientApiTest', 'type' => 'Test', 'tags' => []];
try {
    usleep(450000);
    $tx['tags']['foo'] = 'bar';
    if (random_int(1, 1) === 0) {
        throw new InvalidArgumentException('Foo');
    }
} catch (Throwable $e) {
    $err = ['type' => get_class($e), 'message' => $e->getMessage(), 'culprit' => 'A task was canceled'];
}
$tx['duration'] = (microtime(true) - $start) * 1000;
$payloads = [['transactions' => [$tx]]];
if (isset($err)) {
    $errors = [['errors' => [['exception' => $err]]]];
}
echo '';
?>
--EXPECT--
