--TEST--
Translated seed (inferredbugs-csharp: UniTask/1)
--FILE--
<?php
$cancelled = false;
$callbacks = [];
$register = function (&$callbacks, &$cancelled, $cb, $state = null) {
    if ($cancelled) {
        $cb($state);
        return null;
    }
    $callbacks[] = [$cb, $state];
    return count($callbacks) - 1;
};
$cancel = function (&$callbacks, &$cancelled) {
    if ($cancelled) return;
    $cancelled = true;
    foreach ($callbacks as [$cb, $state]) $cb($state);
    $callbacks = [];
};

$promise = ['done' => false];
$callback = function (&$promise) { $promise['done'] = true; };

$reg = $register($callbacks, $cancelled, $callback, $promise);
$cancel($callbacks, $cancelled);
if (!$promise['done']) { throw new Exception('callback not invoked'); }

$cancelled = true;
$promise2 = ['done' => false];
$reg2 = $register($callbacks, $cancelled, $callback, $promise2);
if (!$promise2['done']) { throw new Exception('late callback not invoked'); }

class D {
    public $disposed = false;
    function __destruct() { $this->disposed = true; }
}
$d = new D();
$cancelled = false;
$callbacks = [];
$register($callbacks, $cancelled, function ($obj) { unset($obj); }, $d);
$cancel($callbacks, $cancelled);
unset($d);
echo "OK\n";
?>
--EXPECT--
