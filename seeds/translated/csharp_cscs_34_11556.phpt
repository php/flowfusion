--TEST--
Translated seed (inferredbugs-csharp: cscs/34)
--FILE--
<?php
declare(strict_types=1);

$classes = [];

class C {
    public string $name;
    public array $props = [];
    public array $ctors = [];
    public array $methods = [];
    function __construct(string $n = '') { $this->name = $n; }
}

class I {
    public C $c;
    public array $props = [];
    function __construct(string $cn, array $args = []) {
        global $classes;
        $this->c = $classes[$cn];
        $this->props = $this->c->props;
        if (isset($this->c->ctors[count($args)])) {
            ($this->c->ctors[count($args)])($this, $args);
        }
    }
    function set(string $k, $v): void { $this->props[$k] = $v; }
    function get(string $k, array $args = null) {
        if (array_key_exists($k, $this->props)) return $this->props[$k];
        if (!isset($this->c->methods[$k]) || $args === null) return null;
        foreach ($this->c->props as $v) $args[] = $v;
        return ($this->c->methods[$k])($this, $args);
    }
}

$base = new C('Base');
$base->props = ['x' => 1, 'y' => ['a' => 2]];
$base->ctors[1] = function(I $o, array $args): void { $o->set('x', $args[0]); };
$base->methods['sum'] = function(I $o, array $args) { return $args[0] + $args[1] + $o->props['x']; };
$classes['Base'] = $base;

$child = new C('Child');
$child->props = $base->props;
$child->ctors = $base->ctors;
$child->methods = $base->methods;
$classes['Child'] = $child;

$o = new I('Child', [7]);
$o->set('z', ['k' => ['v' => 3]]);
$a = $o->get('sum', [4, 5]);
$b = $o->get('x');
$c = $o->get('z')['k']['v'];

$x = ['p' => [1, 2, 3]];
$x['p'][1] += 10;
$x['q'] = ['r' => 4];
unset($x['p'][0]);
$y = array_values($x['p']);
$y[] = $a + $b + $c;

$s = "12";
$i = (int)$s;
$d = (float)"3.5";
$bool = (bool)"0";
$mix = [$i, $d, $bool ? 1 : 0, count($y)];

echo json_encode([$a, $b, $c, $x, $y, $mix], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
