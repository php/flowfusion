--TEST--
Translated seed (inferredbugs-csharp: fullserializer/1)
--FILE--
<?php
class Box implements IteratorAggregate {
    public array $items = [];
    public function getIterator(): Traversable {
        return new ArrayIterator($this->items);
    }
    public function add(mixed $v): void {
        $this->items[] = $v;
    }
}

$box = new Box();
$box->add(1);
$box->add("2");
$box->add(null);

$out = [];
foreach ($box as $item) {
    if (is_int($item) || is_string($item) || $item === null) {
        $out[] = $item;
    }
}

$copy = new Box();
foreach ($out as $item) {
    $copy->add($item);
}

echo count($copy->items), "\n";
?>
--EXPECT--
