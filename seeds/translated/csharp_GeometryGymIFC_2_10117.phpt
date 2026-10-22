--TEST--
Translated seed (inferredbugs-csharp: GeometryGymIFC/2)
--FILE--
<?php
class R{public array $a=[];function add($x){$this->a[]=$x;return $x;}function dup($x){return is_object($x)?clone $x:$x;}}
class P{public ?P $p=null;public function __construct(public string $n=""){}}
class Q extends P{public ?P $u=null;public function __construct(string $n="",?P $u=null){parent::__construct($n);$this->u=$u;}function __clone(){$this->u=$this->u?clone $this->u:null;}}
class S{public array $m=[];function __construct(public string $n=""){}function add($k,$v){$this->m[$k]=$v;}}
class T extends S{public function __construct(string $n="",public string $d="") {parent::__construct($n);}}
class U{public function __construct(public string $v=""){}}
class V{public array $items=[];function add($x){$this->items[]=$x;}}
class W{public ?P $pos=null;public array $x=[],$y=[],$z=[];function __clone(){$this->pos=$this->pos?clone $this->pos:null;}}
class X{public ?P $loc=null;function __construct(?P $l=null){$this->loc=$l;}function isOrigin($t){return $this->loc===null||$this->loc->n==="0,0";}}
class Y{public ?X $placement=null;public function __construct(?X $p=null){$this->placement=$p;}}
class Z{public array $pts=[];function __construct(array $p){$this->pts=$p;}}
class A1{public array $props=[];function add($p){$this->props[$p->n]=$p;}}
class B1 extends U{public ?A1 $set=null;function __construct(string $v="",?A1 $s=null){parent::__construct($v);$this->set=$s;}}
$p=new Q("p",new P("origin"));$q=clone $p;$r=new R();$r->add($q);$s=new S("layer");$s->add("a",new U("1"));$t=new T("layer2","desc");$v=new V();$v->add(new U("x"));$w=new W();$w->pos=new P("pt");$w2=clone $w;$x=new X(new P("0,0"));$y=new Y($x);$z=new Z([new P("1,2"),new P("3,4")]);$a=new A1();$b=new B1("b",$a);$a->add($b);$c=new Q("c");$c->u=null;$d=clone $c;echo "";
?>
--EXPECT--
