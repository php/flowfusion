--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/96)
--FILE--
<?php
$state = ["enabled" => true, "recording" => true, "currentSpan" => null, "currentTransaction" => null];

function startTransaction($name, $type, $state) {
    return (object)["name" => $name, "type" => $type, "ended" => false, "errors" => []];
}

function captureTransaction($name, $type, $func, $state) {
    $tx = startTransaction($name, $type, $state);
    try {
        return $func($tx);
    } catch (Throwable $e) {
        $tx->errors[] = $e->getMessage();
    } finally {
        $tx->ended = true;
    }
}

function registerContinuation($promise, $tx) {
    $promise->then(
        function ($value) use ($tx) {
            $tx->ended = true;
            return $value;
        },
        function ($e) use ($tx) {
            $tx->errors[] = is_object($e) ? $e->getMessage() : (string)$e;
            $tx->ended = true;
        }
    );
}

class Promise {
    private $value;
    private $error;
    public function __construct($value = null, $error = null) { $this->value = $value; $this->error = $error; }
    public function then($onFulfilled, $onRejected = null) {
        if ($this->error !== null) {
            return $onRejected ? $onRejected($this->error) : null;
        }
        return $onFulfilled($this->value);
    }
}

captureTransaction("t1", "test", function ($tx) {
    $a = [1, 2, 3];
    $b =& $a;
    $b[1] = 99;
    $p = new Promise(null, new Exception("faulted"));
    registerContinuation($p, $tx);
    return $a[1];
}, $state);

captureTransaction("t2", "test", function () {
    $p = new Promise("ok");
    registerContinuation($p, (object)["ended" => false, "errors" => []]);
    return "done";
}, $state);
?>
--EXPECT--
