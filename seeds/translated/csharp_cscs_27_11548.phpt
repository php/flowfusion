--TEST--
Translated seed (inferredbugs-csharp: cscs/27)
--FILE--
<?php
class V {
    public $t;
    public $v;
    public $a = [];
    public $p = [];
    function __construct($v = null) {
        if (is_array($v)) { $this->t = 'a'; $this->a = $v; }
        elseif (is_object($v)) { $this->t = 'o'; $this->v = $v; }
        elseif (is_bool($v)) { $this->t = 'b'; $this->v = $v; }
        elseif (is_int($v) || is_float($v)) { $this->t = 'n'; $this->v = $v; }
        elseif ($v === null) { $this->t = 'u'; }
        else { $this->t = 's'; $this->v = (string)$v; }
    }
    function c() { return unserialize(serialize($this)); }
}
class C {
    public $x = 1;
    public $y = [1, 2];
}
$o = new C();
$a = new V(['k' => new V(1), 'n' => new V([new V(2), new V(3)])]);
$b = $a->c();
$b->a['k']->v = 7;
$b->a['n']->a[1]->v = 9;
$s = "123";
$i = (int)$s;
$f = (float)"3.14";
$z = json_decode('{"a":[1,2,{"b":true}],"c":{"d":"e"}}', true);
$z['a'][2]['b'] = !$z['a'][2]['b'];
$z['c']['d'] .= $i . $f;
$r = [];
for ($j = 0; $j < 3; $j++) {
    $r[] = $j;
    if ($j === 1) continue;
    if ($j === 2) break;
}
try {
    if ($o->x === 1) throw new Exception("x");
} catch (Exception $e) {
    $o->x = strlen($e->getMessage());
}
echo $b->a['k']->v . $b->a['n']->a[1]->v . $o->x . count($r) . $z['c']['d'];
?>
--EXPECT--
