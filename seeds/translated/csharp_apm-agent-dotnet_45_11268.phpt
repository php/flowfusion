--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/45)
--FILE--
<?php
declare(strict_types=1);

$payloads = [];
$errors = [];
$tx = null;
$start = microtime(true);

$capture = function (string $name, string $type, callable $cb) use (&$payloads, &$errors, &$tx, $start) {
    $tx = ['name' => $name, 'type' => $type, 'tags' => [], 'context' => ['tags' => []]];
    try {
        $res = $cb($tx);
        $tx['duration'] = microtime(true) - $start;
        $payloads[] = ['transactions' => [$tx]];
        return $res;
    } catch (Throwable $e) {
        $tx['duration'] = microtime(true) - $start;
        $payloads[] = ['transactions' => [$tx]];
        $errors[] = ['errors' => [['exception' => ['type' => get_class($e), 'message' => $e->getMessage()], 'culprit' => $e instanceof ErrorException ? 'A task was canceled' : '']]];
        throw $e;
    }
};

try {
    $capture('ConvenientApiTest', 'Test', function (&$t) {
        usleep(1000);
    });

    try {
        $capture('ConvenientApiTest', 'Test', function (&$t) {
            usleep(1000);
            throw new InvalidArgumentException('Foo');
        });
    } catch (Throwable $e) {
    }

    $res = $capture('ConvenientApiTest', 'Test', function (&$t) {
        usleep(1000);
        return 42;
    });

    $capture('ConvenientApiTest', 'Test', function (&$t) {
        usleep(1000);
        $t['tags']['foo'] = 'bar';
        $t['context']['tags']['foo'] = 'bar';
    });

    $capture('ConvenientApiTest', 'Test', function (&$t) {
        usleep(1000);
        $t['context']['request'] = ['method' => 'GET', 'url' => ['protocol' => 'HTTP', 'full' => 'https://elastic.co', 'raw' => 'https://elastic.co', 'hostName' => 'elastic'], 'httpVersion' => '2.0', 'socket' => ['encrypted' => true, 'remoteAddress' => '127.0.0.1'], 'body' => '123'];
        $t['context']['response'] = ['finished' => true, 'statusCode' => 200];
    });
} catch (Throwable $e) {
}
echo '';
?>
--EXPECT--
