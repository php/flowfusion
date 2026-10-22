--TEST--
Translated seed (inferredbugs-csharp: cscs/70)
--FILE--
<?php
$e = new class {
    public $t = [];
    function __construct() {
        $this->t = [1, 2, 3];
    }
    function add($x, $i = -1) {
        if ($i < 0) $this->t[] = $x; else array_splice($this->t, $i, 0, [$x]);
        return $this->t;
    }
    function remove($x) {
        $k = array_search($x, $this->t, true);
        if ($k !== false) unset($this->t[$k]);
        $this->t = array_values($this->t);
        return $k !== false;
    }
    function contains($x) {
        return in_array($x, $this->t, true);
    }
    function deepCopy() {
        return unserialize(serialize($this));
    }
};

$a = [];
$a["x"]["y"] = 1;
$a["x"]["z"] = &$a["x"]["y"];
$a["x"]["y"] += 1;
$b = $a;
$b["x"]["y"]++;
$c = $e->deepCopy();
$c->add(4);
$c->remove(2);

$s = "9223372036854775807";
$n = (int)$s;
$n += 1;
$m = (float)"1.0e309";
$r = ((string)$n) . ":" . ((string)$m);

$u = [];
$u[] = $a;
$u[] = $b;
$u[] = $c;
$u[] = $e->t;
$u[] = $r;

echo count($u), "\n";
?>
--EXPECT--
