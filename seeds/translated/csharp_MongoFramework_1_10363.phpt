--TEST--
Translated seed (inferredbugs-csharp: MongoFramework/1)
--FILE--
<?php
class E { public $x; function __construct($x){ $this->x = $x; } }
class T {
    public $m = [];
    function u($e, $s){ $this->m[spl_object_id($e)] = [$e, $s]; }
    function ur($es, $s){ foreach ($es as $e) $this->u($e, $s); }
    function g(){ return $this->m; }
}
class W { public $c = 0; function w($t){ foreach ($t->g() as [$e, $s]) if ($s === 1 || $s === 2) if ($e->x === null) throw new Exception(); $this->c++; } }
class R { function q(){ return new ArrayIterator([]); } }
class D {
    public $t, $w, $r, $v = true;
    function __construct($r = null, $w = null){ $this->r = $r; $this->w = $w; $this->t = new T(); }
    function a($e){ if ($e === null) throw new InvalidArgumentException(); $this->t->u($e, 1); }
    function ar($es){ if ($es === null) throw new InvalidArgumentException(); $this->t->ur($es, 1); }
    function u($e){ if ($e === null) throw new InvalidArgumentException(); $this->t->u($e, 2); }
    function ur($es){ if ($es === null) throw new InvalidArgumentException(); $this->t->ur($es, 2); }
    function rm($e){ if ($e === null) throw new InvalidArgumentException(); $this->t->u($e, 3); }
    function rr($es){ if ($es === null) throw new InvalidArgumentException(); $this->t->ur($es, 3); }
    function s(){ if ($this->w === null) throw new RuntimeException(); foreach ($this->t->g() as [$e, $st]) if ($this->v && ($st === 1 || $st === 2) && $e->x === null) throw new RuntimeException(); $this->w->w($this->t); }
}
$d = new D(new R(), new W());
$a = new E(null);
$b = new E(1);
$d->a($a);
$d->u($b);
$d->rm($a);
$d->ar([$a, $b]);
$d->rr([$b]);
$d->s();
echo "ok\n";
--EXPECT--
