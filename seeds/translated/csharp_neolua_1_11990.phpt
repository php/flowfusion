--TEST--
Translated seed (inferredbugs-csharp: neolua/1)
--FILE--
<?php
$a = [];
$a["x"] = 1;
$a[1] = 2;
$a["1"] = 3;
$b = $a;
$b["x"]++;
$b[2] = $b["x"] + ($b[1] ?? 0);
$c = [];
for ($i = 0; $i < 3; $i++) {
    $c[] = $b[$i] ?? null;
}
$d = ["k" => &$c[0]];
$d["k"] = 42;
$e = array_merge($a, $b, $c, $d);
echo count($e), "\n";
?>
--EXPECT--
