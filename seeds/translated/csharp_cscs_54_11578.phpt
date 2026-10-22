--TEST--
Translated seed (inferredbugs-csharp: cscs/54)
--FILE--
<?php
$v = [1, 2, 3];
foreach ($v as &$x) {}
unset($x);
foreach ($v as $k => $x) {
    $v[$k] = $x + 1;
}
$a = [];
$a["01"] = 1;
$a[1] = 2;
$b = ["x" => ["y" => [10, 20, 30]]];
$idx = "x";
$idx2 = "y";
$z = $b[$idx][$idx2][1];
$s = "foo";
$s .= $z;
$c = [];
$c[] = $s;
$c[] = (int)$a["01"];
$c[] = (int)$a[1];
$d = ["0" => "zero", 0 => "numzero"];
$e = array_key_exists("0", $d) ? $d["0"] : "";
$f = array_key_exists(0, $d) ? $d[0] : "";
$g = [];
for ($i = 0; $i < 3; $i++) {
    $g[$i] = $i;
}
unset($g[1]);
$g[] = 99;
$h = ["a" => 1, "b" => 2];
foreach ($h as $key => $val) {
    $h[$key] = $val * 2;
}
$i = $h["a"] + $h["b"] + count($g) + count($c) + strlen($e . $f);
echo $i, "\n";
?>
--EXPECT--
