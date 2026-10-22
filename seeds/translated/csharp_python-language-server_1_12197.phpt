--TEST--
Translated seed (inferredbugs-csharp: python-language-server/1)
--FILE--
<?php
$fp = fopen('php://temp', 'w+');
fwrite($fp, str_repeat("A", 1024));
rewind($fp);
stream_filter_append($fp, 'convert.base64-encode', STREAM_FILTER_READ);
stream_get_contents($fp);

class A {
    public $x = 1;
    function __get($n) { return $this->x; }
    function __isset($n) { return true; }
}

$a = new A();
$b = $a;
$b->x = 2;
$c = [$a, $b, &$a];
foreach ($c as $v) {
    $v->x++;
}

$u = unpack('N*', pack('N*', 0, 1, 2147483647));
$s = array_flip(array_keys($u));
ksort($s);
json_encode($s);

$e = new Exception("x");
try {
    throw $e;
} catch (Throwable $t) {
    $m = $t->getMessage();
}

$dt = new DateTimeImmutable('now');
$dt2 = $dt->modify('+1 day')->setTime(0, 0);

$it = new ArrayIterator([1, 2, 3]);
$it->rewind();
while ($it->valid()) {
    $it->current();
    $it->next();
}

echo "";
?>
--EXPECT--
