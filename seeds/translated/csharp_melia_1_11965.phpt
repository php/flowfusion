--TEST--
Translated seed (inferredbugs-csharp: melia/1)
--FILE--
<?php
declare(strict_types=1);

class P {
    public array $v = [];
    function g(string $k): float { return (float)($this->v[$k] ?? 0); }
    function s(string $k, $x): void { $this->v[$k] = $x; }
    function m(string $k, $d) { $this->v[$k] = ($this->v[$k] ?? 0) + $d; return $this->v[$k]; }
    function a(string $k, $x): void { $this->v[$k] = ($this->v[$k] ?? 0) + $x; }
}

class J { public int $l = 1; public int $t = 0; }
class Js {
    public J $j;
    function __construct() { $this->j = new J(); }
    function g($id): J { return $this->j; }
    function c(): int { return $this->j->l; }
    function m($id, int $a): void { $this->j->t += $a; }
}

class C {
    public P $p;
    public Js $js;
    public int $e = 0;
    public int $m = 10;
    public int $h = 0;
    function __construct() { $this->p = new P(); $this->js = new Js(); }
    function l(int $a = 1): void { $this->p->m('Lv', $a); $this->m += $a; }
    function g(int $e, int $ce): void {
        $this->e += $e;
        while ($this->e >= $this->m) { $this->e -= $this->m; $this->l(); }
        $cl = $this->js->c();
        $this->js->g(0)->t += $ce;
        if ($this->js->c() > $cl) $this->js->m(0, $this->js->c() - $cl);
    }
    function hp(int $a): void { $this->h = $this->p->m('HP', $a); }
}

$c = new C();
$c->p->s('Lv', 1);
$c->p->s('HP', 100);
$c->p->s('MHP', 100);
$c->g(35, 7);
$c->hp(-13);
$c->hp(5);
echo "";
?>
--EXPECT--
