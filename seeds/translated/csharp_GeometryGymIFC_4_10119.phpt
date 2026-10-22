--TEST--
Translated seed (inferredbugs-csharp: GeometryGymIFC/4)
--FILE--
<?php
declare(strict_types=1);

class Db { public int $release=2; public int $modelView=0; public array $f=[]; function dup($x){ return is_object($x) ? clone $x : $x; } }
class P { public ?P $pos=null; public function __construct(public Db $db, ?P $p=null){ if($p&&$p->pos!==null) $this->pos=$db->dup($p->pos); } }
class E { public function __construct(public Db $db){} }
class R { public array $objs=[]; }
class Person { public string $id='$', $fam='$', $giv='$'; public array $mid=[], $pre=[], $suf=[]; public array $refs=[]; public function __construct(public Db $db){} public function name(): string { return $this->giv!=='' ? $this->giv.($this->fam!=='' ? " ".$this->fam : '') : ($this->fam!=='' ? $this->fam : $this->id); } }
class Prop { public string $name='Unknown', $desc='$'; public array $psets=[]; public function __construct(public Db $db, string $n=''){ if($n!=='') $this->name=$n; } }
class Psv extends Prop { public $val=null; public function __construct(Db $db, string $n='', $v=null){ parent::__construct($db,$n); $this->val=$v; } }
class Pset { public array $props=[]; public function add(Prop $p){ $this->props[$p->name]=$p; $p->psets[spl_object_id($this)]=$this; } }
class Prod { public ?object $placement=null, $rep=null; public function __construct(public Db $db){} }

$db=new Db();
$a=new P($db); $b=new P($db,$a);
$p=new Person($db); $p->id='ID'; $p->fam='Doe'; $p->giv='Jane';
$q=new Prop($db,'X'); $s=new Pset(); $s->add($q);
$v=new Psv($db,'X',123); $s->add($v);
$prod=new Prod($db); $prod->placement=$b; $prod->rep=(object)['x'=>1];
echo $p->name()."\n";
echo count($s->props)."\n";
echo ($prod->placement!==null && $prod->rep!==null ? "ok" : "bad")."\n";
?>
--EXPECT--
