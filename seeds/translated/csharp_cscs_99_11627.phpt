--TEST--
Translated seed (inferredbugs-csharp: cscs/99)
--FILE--
<?php
error_reporting(E_ALL);

$a = ["x" => 1, "y" => 2];
$b = &$a;
$b["z"] = 3;
$c = $a;
$c["x"] += 10;
unset($b["y"]);

$u = null;
$nan = acos(2);
$inf = 1 / 0.0;

$vals = [$u, $nan, $inf, "123", "12.5", "abc", 0, 1, true, false];
$out = [];
foreach ($vals as $v) {
    $out[] = [
        is_null($v),
        is_nan($v),
        is_infinite($v),
        is_numeric($v),
        (string)$v,
        gettype($v),
    ];
}

$s = "0";
$i = 0;
$j = "1";
$k = 1;
$cmp = [
    $s == $i,
    $s === $i,
    $j == $k,
    $j === $k,
    "abc" == 0,
    "abc" === 0,
];

$arr = [[1, 2], [3, 4]];
$arr[1][0] = $arr[0][1];
$arr[] = &$arr[0];
$arr[2][0] = 99;

$map = [];
$map["01"] = "a";
$map[1] = "b";
$map["1"] = "c";

$mix = ["n" => "5", "f" => "5.5", "b" => "false"];
$mix["n"] += 2;
$mix["f"] *= 2;
$mix["b"] = (bool)$mix["b"];

foreach ([$a, $c, $arr, $map, $mix, $out, $cmp] as $x) {
    json_encode($x);
}

echo "ok\n";
?>
--EXPECT--
