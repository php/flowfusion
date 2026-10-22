--TEST--
Translated seed (inferredbugs-csharp: cscs/53)
--FILE--
<?php
class V {
    public $t, $v, $a = [], $p = [];
    function __construct($x = null) {
        if (is_array($x)) { $this->t = 'a'; $this->a = $x; }
        elseif (is_bool($x)) { $this->t = 'b'; $this->v = $x; }
        elseif (is_int($x) || is_float($x)) { $this->t = 'n'; $this->v = $x; }
        elseif (is_string($x)) { $this->t = 's'; $this->v = $x; }
        else { $this->t = 'n'; $this->v = 0; }
    }
    function c() {
        $r = new V();
        $r->t = $this->t; $r->v = $this->v; $r->a = $this->a; $r->p = $this->p;
        return $r;
    }
}

function &getv(&$x, $k) {
    if (!array_key_exists($k, $x)) $x[$k] = new V();
    return $x[$k];
}

$root = new V([]);
$root->a['x'] = new V([]);
$root->a['x']->a['y'] = new V(1);
$root->a['x']->a['z'] = new V("1");

$k = "y";
$root->a['x']->a[$k]->v += 1;
$root->a['x']->a['z']->v .= $root->a['x']->a['y']->v;

$arr = new V([new V([new V(1), new V(2)]), new V([new V(3), new V(4)])]);
$tmp = $arr->a[1]->a[0]->c();
$tmp->v += 10;
$arr->a[1]->a[0] = $tmp;

$map = [];
getv($map, "a")->v = 1;
getv($map, "b")->v = 2;
getv($map, "a")->v += getv($map, "b")->v;

$s = "12";
$n = (int)$s;
$f = (float)($s . ".5");
$b = filter_var("true", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

$mix = new V(["0" => new V("zero"), 0 => new V("numzero")]);
$mix->a["0"]->v = $mix->a[0]->v . ":" . $n . ":" . $f . ":" . ($b ? "1" : "0");

echo "ok\n";
?>
--EXPECT--
