--TEST--
Translated seed (inferredbugs-csharp: NLua/3)
--FILE--
<?php
class Box {
    public $v;
    function __construct($v) { $this->v = $v; }
}

$map = [];
$back = [];
$next = 0;

$a = new Box(1);
$b = new Box(1);
$c = $a;

$map[$next] = $a;
$back[spl_object_id($a)] = $next;
$next++;

$map[$next] = $b;
$back[spl_object_id($b)] = $next;
$next++;

if (isset($back[spl_object_id($c)])) {
    $id = $back[spl_object_id($c)];
    if (isset($map[$id])) {
        unset($map[$id]);
        unset($back[spl_object_id($c)]);
    }
}

if (!isset($back[spl_object_id($a)])) {
    $map[$next] = $a;
    $back[spl_object_id($a)] = $next;
    $next++;
}

$values = [];
$values[] = $map[$back[spl_object_id($a)]]->v;
$values[] = $map[$back[spl_object_id($b)]]->v;

$enum = PHP_INT_MAX;
$enum2 = (int)(string)$enum;

if ($enum2 !== $enum) {
    $enum2 = $enum;
}

echo count($map) . ":" . $values[0] . ":" . $values[1] . ":" . $enum2 . "\n";
?>
--EXPECT--
