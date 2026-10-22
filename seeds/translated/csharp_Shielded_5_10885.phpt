--TEST--
Translated seed (inferredbugs-csharp: Shielded/5)
--FILE--
<?php
class SimpleEntity {
    public int $Id = 0;
    public function Commute(callable $a): void { $a(); }
}

class SmallEntity {
    public int $Id = 0;
    public int $Value = 0;
}

class TreeItem {
    public string $Id;
    public function __construct() { $this->Id = bin2hex(random_bytes(16)); }
}

class Dummy { public int $Value = 0; }

$taskCount = 1000;
$randomizr = random_int(0, PHP_INT_MAX);
$x = array_fill(0, 100, 0);
for ($i = 0; $i < $taskCount; $i++) {
    $rnd = random_int(0, 99);
    $v = $x[$rnd];
    $x[$rnd] = $v + 1;
}
$sum = array_sum($x);

$shx = [];
for ($i = 0; $i < 100; $i++) {
    $e = new SmallEntity();
    $e->Id = $i;
    $e->Value = 0;
    $shx[$i] = $e;
}
for ($i = 0; $i < $taskCount; $i++) {
    $rnd = random_int(0, 99);
    $v = $shx[$rnd]->Value;
    $shx[$rnd]->Value = $v + 1;
}
$sum2 = 0;
foreach ($shx as $e) $sum2 += $e->Value;

$tree = [];
for ($i = 0; $i < 1000; $i++) {
    $item = new TreeItem();
    $tree[$item->Id] = $item;
}
ksort($tree);
foreach ($tree as $k => $v) { $x = $v->Id; }

$a = new SimpleEntity();
$a->Commute(function () use ($a) { $a->Id++; });
$a->Commute(function () use ($a) { $a->Id++; });

echo $sum . "\n";
echo $sum2 . "\n";
echo count($tree) . "\n";
echo $a->Id . "\n";
?>
--EXPECT--
