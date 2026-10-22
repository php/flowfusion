--TEST--
Translated seed (inferredbugs-csharp: NLua/4)
--FILE--
<?php
class Box {
    public $v;
    function __construct($v) { $this->v = $v; }
}

$map = [];
$back = [];
$next = 0;

$a = new Box(1);
$b = new Box(1);
$c = $a;

$back[spl_object_id($a)] = $next;
$map[$next] = $a;
$next++;

$back[spl_object_id($b)] = $next;
$map[$next] = $b;
$next++;

if (spl_object_id($a) === spl_object_id($c) && $back[spl_object_id($c)] !== $back[spl_object_id($a)]) {
    $map[$back[spl_object_id($c)]] = $c;
}

$k = spl_object_id($a);
unset($map[$back[$k]]);
unset($back[$k]);

$map[$next] = $a;
$back[spl_object_id($a)] = $next;
$next++;

$arr = [];
$arr["base"] = $a;
$arr["copy"] = $b;
$arr["same"] = $c;
$arr["num"] = 1 << 30;
$arr["num"] += 1 << 30;
$arr["strnum"] = "42";
$arr["mix"] = $arr["strnum"] + 1;
$arr["flag"] = true;
$arr["float"] = (float)$arr["num"] / 3.0;

foreach ($arr as $x) {
    if (is_object($x)) {
        $id = spl_object_id($x);
        $map[$back[$id]] = $x;
    }
}

echo count($map), "\n";
echo $arr["num"], "\n";
echo $arr["mix"], "\n";
echo $arr["float"], "\n";
?>
--EXPECT--
