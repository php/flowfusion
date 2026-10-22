--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/17)
--FILE--
<?php
class T {
    public array $s;
    public array $w;
    public array $g;
    public string $n;
    function __construct(array $s, string $n = '') { $this->s = $s; $this->n = $n; $c = 1; foreach ($s as $x) $c *= $x; $this->w = array_fill(0, $c, 0.0); $this->g = array_fill(0, $c, 0.0); }
}
class G {
    public array $b = [];
    function add(T $t) { $this->b[] = $t; }
    function backward() { for ($i = count($this->b) - 1; $i >= 0; $i--) { ($this->b[$i])(); } $this->b = []; }
}
$g = new G();
$a = new T([2, 3], 'a'); $a->w = [1, 2, 3, 4, 5, 6];
$b = new T([3, 2], 'b'); $b->w = [7, 8, 9, 10, 11, 12];
$c = new T([2, 2], 'c'); $c->w = [1, 0, 0, 1];
$r = new T([2, 2], 'r');
for ($i = 0; $i < 2; $i++) for ($j = 0; $j < 2; $j++) for ($k = 0; $k < 3; $k++) $r->w[$i * 2 + $j] += $a->w[$i * 3 + $k] * $b->w[$k * 2 + $j];
$s = new T([2, 2], 's');
for ($i = 0; $i < 4; $i++) $s->w[$i] = $r->w[$i] + $c->w[$i];
$u = new T([2, 2], 'u');
for ($i = 0; $i < 4; $i++) $u->w[$i] = 1.0 / sqrt($s->w[$i] + 1e-9);
$v = new T([2, 2], 'v');
for ($i = 0; $i < 4; $i++) $v->w[$i] = 1.0 / (1.0 + exp(-$u->w[$i]));
$idx = new T([2, 1], 'idx');
for ($i = 0; $i < 2; $i++) {
    $sum = 0.0; $seed = mt_rand() / mt_getrandmax();
    for ($j = 0; $j < 2; $j++) { $sum += $v->w[$i * 2 + $j]; if ($seed <= $sum) { $idx->w[$i] = $j; break; } }
}
$cat = new T([4, 2], 'cat');
for ($i = 0; $i < 2; $i++) for ($j = 0; $j < 2; $j++) { $cat->w[$i * 2 + $j] = $a->w[$i * 3 + $j]; $cat->w[($i + 2) * 2 + $j] = $b->w[$j * 2 + $i]; }
$view = new T([2, 2], 'view');
$view->w = $cat->w;
$mask = new T([2, 3, 3], 'mask');
for ($bix = 0; $bix < 2; $bix++) for ($i = 0; $i < 3; $i++) for ($j = 0; $j < 3; $j++) $mask->w[$bix * 9 + $i * 3 + $j] = $j <= $i ? 0.0 : -99999999.0;
$g->add(function() use ($g, $r, $s, $u, $v, $idx, $cat, $view, $mask) { $r->g = $s->g = $u->g = $v->g = $idx->g = $cat->g = $view->g = $mask->g = []; });
$g->backward();
echo "ok\n";
?>
--EXPECT--
