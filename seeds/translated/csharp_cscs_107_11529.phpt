--TEST--
Translated seed (inferredbugs-csharp: cscs/107)
--FILE--
<?php
declare(strict_types=1);

$nan = NAN;
$inf = INF;
$ninf = -INF;

$values = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN, 1.0, 1.5, $nan, $inf, $ninf, "0", "1", "-1", "1e309", "foo", "", null, true, false, [], [1, 2], ["a" => 1], " 42 ", "042", "0x10"];

foreach ($values as $v) {
    is_nan(is_float($v) ? $v : (float)$v);
    is_finite(is_float($v) ? $v : (float)$v);
    gettype($v);
    settype($v, 'string');
    settype($v, 'array');
    settype($v, 'object');
}

$a = ["x" => 1, "y" => [2, 3], "z" => ["k" => "v"]];
$b = $a;
$b["y"][1] = 99;
$b["z"]["k"] = "w";
$c = $a;
$c[] = 4;
unset($c["x"]);

$e = enum_exists('UnitEnum') ? UnitEnum::cases() : [];
$map = [];
foreach ($e as $case) {
    $map[$case->name] = $case->name;
}

$nested = ["a" => ["b" => ["c" => 1]]];
$ref = &$nested["a"]["b"];
$ref["d"] = 2;

$s = "abc";
for ($i = 0; $i < 3; $i++) {
    $s .= $i;
    $s = substr($s, 0, strlen($s));
}

$x = 0;
while ($x < 5) {
    if ($x === 2) {
        $x++;
        continue;
    }
    if ($x === 4) {
        break;
    }
    $x++;
}

try {
    throw new ArgumentException("value");
} catch (Throwable $t) {
}

echo "";
?>
--EXPECT--
