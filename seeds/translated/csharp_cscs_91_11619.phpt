--TEST--
Translated seed (inferredbugs-csharp: cscs/91)
--FILE--
<?php
class V {
    public $t, $v, $a = [], $p = [];
    function __construct($x = null) {
        if (is_array($x)) { $this->t = 'a'; $this->a = $x; }
        elseif (is_object($x)) { $this->t = 'o'; $this->v = $x; }
        elseif (is_bool($x)) { $this->t = 'b'; $this->v = $x; }
        elseif (is_int($x) || is_float($x)) { $this->t = 'n'; $this->v = $x; }
        elseif ($x === null) { $this->t = 'e'; }
        else { $this->t = 's'; $this->v = (string)$x; }
    }
    function s() { return $this->t === 's' ? $this->v : ($this->t === 'n' ? (string)$this->v : ($this->t === 'b' ? ($this->v ? 'true' : 'false') : ($this->t === 'a' ? json_encode($this->a) : ''))); }
    function i() { return (int)$this->s(); }
    function add($x) { $this->a[] = $x instanceof V ? $x : new V($x); $this->t = 'a'; return $this; }
    function set($k, $x) { $this->p[$k] = $x instanceof V ? $x : new V($x); return $this; }
    function get($k) { return $this->p[$k] ?? null; }
    function clonev() { return unserialize(serialize($this)); }
}

$a = new V([new V([new V(1), new V(2)]), new V([new V(3), new V(4)])]);
$b = $a->clonev();
$b->a[0]->a[1] = new V(99);
$c = new V([]);
$c->add(new V("x"))->add(new V("y"));
$c->set("k", new V("v"));
$d = $c->clonev();
$e = new V(["n" => new V(1)]);
$e->set("n", new V($e->get("n")->i() + 1));
$f = new V((string)($b->a[0]->a[1]->i() + $e->get("n")->i()));
echo $f->s();
?>
--EXPECT--
