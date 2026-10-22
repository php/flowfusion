--TEST--
Translated seed (inferredbugs-csharp: cscs/45)
--FILE--
<?php
$v = new class {
    public $t = 'NONE', $n = NAN, $s = null, $a = null, $d = [], $k = [];
    function __construct($x = null) {
        if ($x === null) return;
        if (is_array($x)) { $this->t = 'ARRAY'; $this->a = $x; return; }
        if (is_string($x)) { $this->t = 'STRING'; $this->s = $x; return; }
        if (is_bool($x)) { $this->t = 'NUMBER'; $this->n = $x ? 1.0 : 0.0; return; }
        if (is_int($x) || is_float($x)) { $this->t = 'NUMBER'; $this->n = $x + 0.0; return; }
        $this->t = 'OBJECT'; $this->a = $x;
    }
    function add($x) { if ($this->t !== 'ARRAY') { $this->t = 'ARRAY'; $this->a = []; } $this->a[] = $x; }
    function set($p, $x) { $l = strtolower($p); if (!array_key_exists($l, $this->d)) { $this->d[$l] = count($this->a); $this->k[$l] = $p; $this->a[] = $x; } else $this->a[$this->d[$l]] = $x; }
    function get($p) { $l = strtolower($p); return isset($this->d[$l]) && isset($this->a[$this->d[$l]]) ? $this->a[$this->d[$l]] : null; }
    function deep() { $r = clone $this; if (is_array($this->a)) { $r->a = []; foreach ($this->a as $x) $r->a[] = is_object($x) ? clone $x : $x; } return $r; }
};

$x = new $v(['A' => 1, 'b' => 2]);
$x->set('A', new $v('z'));
$y = $x->deep();
$y->add(new $v(true));
$y->add(new $v(false));
$z = $y->get('a');
echo is_object($z) ? $z->t . PHP_EOL : "ok\n";
?>
--EXPECT--
