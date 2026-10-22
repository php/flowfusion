--TEST--
Translated seed (inferredbugs-csharp: cscs/26)
--FILE--
<?php
class V {
    public $v;
    public $a = [];
    public $p = [];
    function __construct($v = null) { $this->v = $v; }
    function add($x) {
        if (is_array($this->v)) $this->v[] = $x->v;
        elseif (is_string($this->v) && is_string($x->v)) $this->v .= $x->v;
        elseif (is_numeric($this->v) && is_numeric($x->v)) $this->v += $x->v;
        else $this->v = $x->v;
    }
    function deep() { return new V(is_array($this->v) ? $this->v : $this->v); }
}

class C {
    public static $classes = [];
    public $props = [];
    public $methods = [];
    function __construct($n, $bases = []) {
        self::$classes[$n] = $this;
        foreach ($bases as $b) {
            if (!isset(self::$classes[$b])) continue;
            $bc = self::$classes[$b];
            $this->props += $bc->props;
            $this->methods += $bc->methods;
        }
    }
}

class I {
    public $c;
    public $props = [];
    function __construct($cn) {
        $this->c = C::$classes[$cn];
        foreach ($this->c->props as $k => $v) $this->props[$k] = $v;
    }
}

$c1 = new C("Base");
$c1->props["x"] = new V(1);
$c1->methods["m"] = function($self, $a) { return new V($self->props["x"]->v + $a->v); };
$c2 = new C("Child", ["Base"]);
$o = new I("Child");
$o->props["x"]->v = 2;
$r = $c2->methods["m"]($o, new V(3));
$a = new V([new V("a"), new V("b")]);
$a->add(new V("c"));
$s = new V("1");
$s->add(new V("2"));
$t = new V(10);
$t->add(new V(5));
$x = $a->deep();
$y = $s->deep();
$z = $t->deep();
echo "";
?>
--EXPECT--
