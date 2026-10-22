--TEST--
Translated seed (inferredbugs-csharp: cscs/23)
--FILE--
<?php
class V {
    public $t, $v, $a = [], $p = [];
    function __construct($v = null) {
        if (is_array($v)) { $this->t = 'a'; $this->a = $v; }
        elseif (is_bool($v)) { $this->t = 'b'; $this->v = $v; }
        elseif (is_int($v) || is_float($v)) { $this->t = 'n'; $this->v = $v; }
        elseif ($v === null) { $this->t = 'e'; }
        else { $this->t = 's'; $this->v = (string)$v; }
    }
    function s() { return $this->t === 'a' ? json_encode($this->a) : (string)($this->v ?? ''); }
    function add($x) { $this->a[] = $x instanceof self ? $x : new self($x); $this->t = 'a'; return $this; }
    function setp($k, $x) { $this->p[$k] = $x instanceof self ? $x : new self($x); return $this; }
    function getp($k) { return $this->p[$k] ?? null; }
    function clonev() { return unserialize(serialize($this)); }
}

$a = new V([new V("x"), new V("y")]);
$a->setp("k", new V("v"));
$b = $a->clonev();
$b->a[1] = new V("z");
$c = new V();
$c->add(new V(1))->add(new V(2));
$d = $c->clonev();
$d->a[0] = new V(9);
echo $a->s(), "\n";
echo $b->s(), "\n";
echo $a->getp("k")->s(), "\n";
echo $c->s(), "\n";
echo $d->s(), "\n";
?>
--EXPECT--
