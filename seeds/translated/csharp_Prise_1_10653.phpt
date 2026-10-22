--TEST--
Translated seed (inferredbugs-csharp: Prise/1)
--FILE--
<?php
class PriseProxyException extends Exception {}

class RemoteObject {
    public function foo(int $x) { return $x + 1; }
    public function fooString(string $x) { return $x . "1"; }
    public function bar($x) { return $x; }
    public function baz(callable $cb) { return $cb(7); }
    public function qux() { return; }
}

$remote = new RemoteObject();
$methods = ['foo', 'fooString', 'bar', 'baz', 'qux'];

foreach ($methods as $name) {
    $rm = new ReflectionMethod($remote, $name);
    $args = [];
    foreach ($rm->getParameters() as $p) {
        $t = $p->hasType() ? (string)$p->getType() : '';
        if ($t === 'int') $args[] = 41;
        elseif ($t === 'string') $args[] = '4';
        elseif ($t === 'callable') $args[] = function ($v) { return $v + 1; };
        else $args[] = '7';
    }
    $result = $rm->invokeArgs($remote, $args);
    if ($name === 'foo' && $result !== 42) throw new PriseProxyException();
    if ($name === 'fooString' && $result !== '41') throw new PriseProxyException();
    if ($name === 'bar' && $result !== '7') throw new PriseProxyException();
    if ($name === 'baz' && $result !== 8) throw new PriseProxyException();
    if ($name === 'qux' && $result !== null) throw new PriseProxyException();
}

$overloaded = [];
foreach ((new ReflectionClass($remote))->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
    if ($m->getName() === 'foo') $overloaded[] = $m;
}
if (count($overloaded) !== 1) {
    $chosen = null;
    foreach ($overloaded as $m) {
        $params = $m->getParameters();
        if (count($params) === 1 && (string)$params[0]->getType() === 'int') $chosen = $m;
    }
    if ($chosen === null) throw new PriseProxyException();
}

echo "";
?>
--EXPECT--
