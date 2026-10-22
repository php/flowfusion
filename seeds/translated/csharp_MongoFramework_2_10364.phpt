--TEST--
Translated seed (inferredbugs-csharp: MongoFramework/2)
--FILE--
<?php
class E { public $x; function __construct($x){ $this->x = $x; } }
class T {
    public $m = [];
    function u($e, $s){ $this->m[spl_object_id($e)] = [$e, $s]; }
    function ur($es, $s){ foreach ($es as $e) $this->u($e, $s); }
    function g(){ return array_values($this->m); }
    function d(){ foreach ($this->m as &$v) if (is_object($v[0]) && property_exists($v[0], 'x')) $v[0]->x = $v[0]->x; }
}
class S {
    public $t, $v = true;
    function __construct(){ $this->t = new T; }
    function a($e){ if ($e === null) throw new InvalidArgumentException('entity'); $this->t->u($e, 1); }
    function ar($es){ if ($es === null) throw new InvalidArgumentException('entities'); $this->t->ur($es, 1); }
    function u($e){ if ($e === null) throw new InvalidArgumentException('entity'); $this->t->u($e, 2); }
    function ur($es){ if ($es === null) throw new InvalidArgumentException('entities'); $this->t->ur($es, 2); }
    function r($e){ if ($e === null) throw new InvalidArgumentException('entity'); $this->t->u($e, 3); }
    function rr($es){ if ($es === null) throw new InvalidArgumentException('entities'); $this->t->ur($es, 3); }
    function sc(){ $this->t->d(); if ($this->v) foreach ($this->t->g() as $v) if ($v[1] === 1 || $v[1] === 2) { $x = $v[0]->x; } }
}
$s = new S;
$o = new E(1);
$a = [$o, new E(2)];
$s->a($o);
$s->ar($a);
$s->u($o);
$s->ur($a);
$s->r($o);
$s->rr($a);
$s->sc();
echo "ok\n";
?>
--EXPECT--
