--TEST--
Translated seed (inferredbugs-csharp: cscs/108)
--FILE--
<?php
$nan = NAN;
$inf = INF;
$ninf = -INF;
$v = ["a" => null, "b" => $nan, "c" => $inf, "d" => $ninf, "e" => ["x" => 1, "y" => 2]];
$u = $v["z"] ?? null;
$r = is_nan($v["b"]);
$f = is_finite($v["c"]);
$s = gettype($v["a"]);
$t = gettype($v["b"]);
$x = json_encode($v);
$y = json_decode($x, true);
$a = [];
$a[] = 1;
$a[3] = 4;
$a["k"] = 5;
foreach ($a as $k => $val) {
    if ($k === 3) {
        continue;
    }
    if ($k === "k") {
        break;
    }
}
try {
    if ($r && !$f && $s === "NULL" && $t === "double") {
        throw new Exception("ok");
    }
} catch (Exception $e) {
}
echo "";
?>
--EXPECT--
