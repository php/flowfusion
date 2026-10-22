--TEST--
Translated seed (inferredbugs-csharp: uhttpsharp/2)
--FILE--
<?php
$h = fopen('php://temp', 'r+');
fwrite($h, "SESSID=abc; foo=bar");
rewind($h);
$s = stream_get_contents($h);

parse_str(str_replace('; ', '&', $s), $cookies);
if (!isset($cookies['SESSID'])) {
    $cookies['SESSID'] = (string)mt_rand();
}

$store = [];
$sessionId = $cookies['SESSID'];
if (!array_key_exists($sessionId, $store)) {
    $store[$sessionId] = ['lastAccessTime' => microtime(true), 'value' => 'session'];
}

$session = &$store[$sessionId];
$session['lastAccessTime'] = microtime(true);

$query = 'MyProperty=123&Model[MyProperty]=456&Model[Model][MyProperty]=789';
parse_str($query, $model);
$myModel = [
    'MyProperty' => isset($model['MyProperty']) ? (int)$model['MyProperty'] : 0,
    'Model' => isset($model['Model']) && is_array($model['Model']) ? $model['Model'] : null,
];

$router = [
    '' => 'index',
    'about' => 'about',
    'strings' => 'strings',
];
$path = '';
$handler = $router[$path] ?? null;

$super = new class {
    public function __get($name) {
        if ($name === 'Child') {
            return new self();
        }
        return null;
    }

    public function __call($name, $args) {
        if ($name === 'GetChild') {
            return new self();
        }
        return null;
    }
};

$child = $super->Child;
$grandchild = $super->GetChild(0);

echo "OK\n";
?>
--EXPECT--
