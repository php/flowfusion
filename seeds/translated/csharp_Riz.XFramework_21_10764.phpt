--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/21)
--FILE--
<?php
class A { public int $id = 1; public string $name = 'x'; public ?B $b = null; }
class B { public int $bid = 2; public ?C $c = null; }
class C { public int $cid = 3; }

$a = new A();
$a->b = new B();
$a->b->c = new C();

$seen = [];
$pick = [];
$nav = [];

$it = new RecursiveIteratorIterator(new RecursiveArrayIterator([
    'root' => [
        'id' => $a->id,
        'name' => $a->name,
        'b' => [
            'bid' => $a->b->bid,
            'c' => ['cid' => $a->b->c->cid],
        ],
    ],
]), RecursiveIteratorIterator::SELF_FIRST);

foreach ($it as $k => $v) {
    if (is_string($k)) $seen[] = $k;
    if (is_array($v)) continue;
    $pick[] = $k . '=' . (string)$v;
    $nav[] = implode('.', $seen);
    array_pop($seen);
}

echo implode(',', $pick), PHP_EOL;
echo implode('|', $nav), PHP_EOL;
?>
--EXPECT--
