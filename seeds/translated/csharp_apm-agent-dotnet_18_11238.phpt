--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/18)
--FILE--
<?php
$start = microtime(true);
$payloads = [];
$errors = [];
$name = 'ConvenientApiTest';
$type = 'Test';
$msg = 'Foo';
$delay = 450000;

$capture = function ($cb) use (&$payloads, &$errors, $name, $type, $start) {
    $tx = ['Name' => $name, 'Type' => $type, 'Tags' => [], 'Context' => ['Tags' => []], 'Duration' => 0];
    try {
        $res = $cb($tx);
        $tx['Duration'] = (microtime(true) - $start) * 1000;
        $tx['Tags'] = $tx['Context']['Tags'];
        $payloads[] = ['Transactions' => [$tx]];
        return $res;
    } catch (Throwable $e) {
        $tx['Duration'] = (microtime(true) - $start) * 1000;
        $tx['Tags'] = $tx['Context']['Tags'];
        $payloads[] = ['Transactions' => [$tx]];
        $errors[] = ['Errors' => [['Exception' => ['Type' => get_class($e), 'Message' => $e->getMessage()], 'Culprit' => 'A task was canceled']]];
        throw $e;
    }
};

$capture(function ($t) use ($delay) { usleep($delay); });
try { $capture(function ($t) use ($delay, $msg) { usleep($delay); throw new InvalidArgumentException($msg); }); } catch (Throwable $e) {}
$capture(function ($t) use ($delay) { usleep($delay); $t['Context']['Tags']['foo'] = 'bar'; });
try { $capture(function ($t) use ($delay, $msg) { usleep($delay); $t['Context']['Tags']['foo'] = 'bar'; throw new InvalidArgumentException($msg); }); } catch (Throwable $e) {}
try { $capture(function ($t) use ($delay) { usleep($delay); return 42; }); } catch (Throwable $e) {}
try { $capture(function ($t) use ($delay) { usleep($delay); $t['Context']['Tags']['foo'] = 'bar'; return 42; }); } catch (Throwable $e) {}
try { $capture(function ($t) use ($delay, $msg) { usleep($delay); $t['Context']['Tags']['foo'] = 'bar'; throw new InvalidArgumentException($msg); return 42; }); } catch (Throwable $e) {}
try { $capture(function ($t) use ($delay, $msg) { usleep($delay); throw new InvalidArgumentException($msg); return 42; }); } catch (Throwable $e) {}

echo '';
?>
--EXPECT--
