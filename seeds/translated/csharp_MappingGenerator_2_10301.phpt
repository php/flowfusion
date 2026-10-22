--TEST--
Translated seed (inferredbugs-csharp: MappingGenerator/2)
--FILE--
<?php
class Wrapper {
    public $value;
    function __construct($v) { $this->value = $v; }
}

class Node {
    public $name;
    public $child;
    public $items;
    public $total;
    function __construct($name = null) { $this->name = $name; }
    function getTotal() { return $this->total; }
}

class TargetNode {
    public $name;
    public $child;
    public $items;
    public $total;
    function __construct($src = null) {
        if ($src !== null) {
            $this->name = $src->name;
            $this->child = $src->child;
            $this->items = $src->items;
            $this->total = $src->total;
        }
    }
}

$source = new Node("root");
$source->child = new Node("leaf");
$source->child->name = "kid";
$source->items = [new Node("a"), new Node("b")];
$source->total = (new Wrapper(7))->value;

$target = new TargetNode($source);
$target->child = new TargetNode($source->child);
$target->items = array_map(fn($x) => new TargetNode($x), $source->items);
$target->total = $source->getTotal();

$readonly = new ArrayObject($target->items, ArrayObject::ARRAY_AS_PROPS);
$copy = $readonly->getArrayCopy();
$mapped = array_map(fn($x) => $x->name, $copy);
echo implode(",", $mapped), "\n";
echo $target->child->name, "\n";
echo $target->total, "\n";
?>
--EXPECT--
