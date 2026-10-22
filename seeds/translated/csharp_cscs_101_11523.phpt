--TEST--
Translated seed (inferredbugs-csharp: cscs/101)
--FILE--
<?php
$nan = NAN;
$inf = INF;
$ninf = -INF;

$v = ["a" => 1, "b" => $nan, "c" => $inf, "d" => $ninf, "e" => "123", "f" => "12.5", "g" => "0", "h" => "", "i" => null];
$a = [$v, ["x" => [1, 2, 3]], ["x" => [4, 5]]];

for ($i = 0; $i < 3; $i++) {
    $a[1]["x"][] = $i;
    array_splice($a[1]["x"], 1, 0, [$i + 10]);
    unset($a[2]["x"][0]);
    $a[2]["x"] = array_values($a[2]["x"]);
}

$types = [];
foreach ([$nan, $inf, $ninf, 1, 1.5, "1", "foo", null, true, false, [], $a] as $x) {
    $types[] = gettype($x);
    $types[] = is_nan(is_float($x) ? $x : NAN) ? "nan" : "ok";
    $types[] = is_finite(is_numeric($x) ? $x + 0 : INF) ? "finite" : "inf";
}

$s = "1,2,3;4,5,6\n7,8,9";
$lines = explode("\n", $s);
$all = [];
foreach ($lines as $line) {
    $all[] = explode(",", $line);
}
$col = [];
for ($i = 0; $i < count($all); $i++) {
    $col[] = $all[$i][1];
}

$x = 0;
$y = $x++;
$z = ++$x;
$x += 2;
$x -= 1;
$x *= 3;
$x /= 2;
$x %= 5;
$x |= 1;
$x &= 7;
$x ^= 2;

$map = [];
$map["k"] = ["p" => 1];
$map["k"]["q"] = 2;
$map["k"]["q"] += 3;
$map["k"]["r"] = $map["k"]["q"] . "";
$map["k"]["s"] = (int)$map["k"]["r"];

$copy = $map;
$deep = unserialize(serialize($map));
$copy["k"]["q"] = 99;

$fn = function ($a = 1, $b = 2, $c = 3) {
    return [$a, $b, $c];
};
$r1 = $fn(10);
$r2 = $fn(b: 20, a: 30);
$r3 = $fn(c: 40, a: 50, b: 60);

$u = null;
$exists = isset($u);
$undef = $u ?? "fallback";

$props = array_keys($map["k"]);
$keys = array_keys($a[1]["x"]);

echo "";
?>
--EXPECT--
