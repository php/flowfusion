--TEST--
Translated seed (inferredbugs-csharp: tau/2)
--FILE--
<?php
$parent = new class {
    public array $children = [];
    function remove($child) {
        foreach ($this->children as $i => $c) {
            if ($c === $child) {
                array_splice($this->children, $i, 1);
                return;
            }
        }
    }
    function add($child) {
        $this->children[] = $child;
    }
};

$hitObjectContainer = new stdClass();
$hitObjectContainer->parent = $parent;
$parent->children[] = $hitObjectContainer;

$mode = 'fadeOut';
$coverage = 0.4;

$parent->remove($hitObjectContainer);
$parent->add((object)[
    'child' => $hitObjectContainer,
    'mode' => $mode,
    'coverage' => $coverage,
]);

echo "ok\n";
--EXPECT--
