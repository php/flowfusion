--TEST--
Translated seed (inferredbugs-csharp: mono.linq.expressions/1)
--FILE--
<?php
class Counter {
    public int $count = 0;
    public function hit(): void { $this->count++; }
}

$counter = new Counter();
$l = 10;
for ($i = 0; $i < $l; ++$i) {
    $counter->hit();
}
$counter = new Counter();
$l = 0;
for ($i = 0; $i < $l; ++$i) {
    $counter->hit();
}
$counter = new Counter();
$l = 100;
for ($i = 0; $i < $l; ++$i) {
    if ($i < 10) {
        $counter->hit();
    } else {
        break;
    }
}
$counter = new Counter();
$l = 10;
for ($i = 0; $i < $l; ++$i) {
    if (($i % 2) === 0) {
        $counter->hit();
    } else {
        continue;
    }
}
$counter->hit();
?>
--EXPECT--
