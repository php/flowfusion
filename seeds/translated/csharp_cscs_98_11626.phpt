--TEST--
Translated seed (inferredbugs-csharp: cscs/98)
--FILE--
<?php
$e = INF;
$a = [0, 1, 2];
$b = ["x" => 1, "y" => ["z" => 2]];
$c = [];
$c["k"] = null;
$c["k"] = $c["k"] ?? 0;
$c["k"] += 1;
$d = json_decode('{"n":1,"s":"1","i":[1,2,3],"o":{"p":4}}', true);
$e1 = is_nan(NAN);
$e2 = is_finite($e);
$e3 = is_infinite($e);
$e4 = gettype("1");
$e5 = gettype(1);
$e6 = gettype($d["o"]["p"]);
$f = [];
$f[] = $a[1];
$f[] = $b["y"]["z"];
$f[] = $c["k"];
$f[] = $d["i"][2];
$f[] = $e1 ? 1 : 0;
$f[] = $e2 ? 1 : 0;
$f[] = $e3 ? 1 : 0;
$f[] = $e4;
$f[] = $e5;
$f[] = $e6;
$g = [];
foreach ($f as $k => $v) {
    $g[$k] = $v;
}
$h = array_map(function ($x) {
    return is_int($x) ? $x + 1 : $x;
}, $g);
$i = implode(",", array_map(function ($x) {
    return (string)$x;
}, $h));
$j = explode(",", $i);
$k = array_reduce($j, function ($carry, $item) {
    return $carry . $item;
}, "");
echo $k;
?>
--EXPECT--
