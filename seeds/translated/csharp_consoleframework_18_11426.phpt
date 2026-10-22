--TEST--
Translated seed (inferredbugs-csharp: consoleframework/18)
--FILE--
<?php
class A { public $v; }
class B { public $a; public $items; public $map; public function __construct() { $this->items = []; $this->map = []; } }

$xml = '<B xmlns:x="urn:x" x:Id="root"><a>{root}</a><items><A x:Id="later"><v>7</v></A></items><map><A x:Key="k"><v>9</v></A></map></B>';

$doc = new SimpleXMLElement($xml);
$ids = [];
$fixups = [];

$root = new B();
$stack = [[$doc, $root, null]];

while ($stack) {
    [$node, $obj, $prop] = array_pop($stack);
    foreach ($node->attributes() as $k => $v) {
        $n = $k;
        if ($n === 'Id' && $v !== '') $ids[(string)$v] = $obj;
        if ($n === 'Key') $obj->_key = (string)$v;
    }
    foreach ($node->children() as $child) {
        $name = $child->getName();
        if ($name === 'v') {
            $obj->v = (int)(string)$child;
        } elseif ($name === 'a') {
            $txt = trim((string)$child);
            if ($txt !== '' && $txt[0] === '{') {
                $ref = substr($txt, 1, -1);
                if (isset($ids[$ref])) $obj->a = $ids[$ref];
                else $fixups[] = [&$obj, 'a', $ref];
            }
        } elseif ($name === 'items') {
            foreach ($child->children() as $gc) {
                $a = new A();
                $stack[] = [$gc, $a, 'items'];
                $obj->items[] = $a;
            }
        } elseif ($name === 'map') {
            foreach ($child->children() as $gc) {
                $a = new A();
                $k = (string)$gc['x:Key'];
                $stack[] = [$gc, $a, 'map'];
                $obj->map[$k] = $a;
            }
        }
    }
}

foreach ($fixups as [$o, $p, $id]) if (isset($ids[$id])) $o->$p = $ids[$id];
echo $root->items[0]->v + $root->map['k']->v + ($root->a === $root ? 1 : 0);
?>
--EXPECT--
