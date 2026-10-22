--TEST--
Translated seed (inferredbugs-csharp: neolua/6)
--FILE--
<?php
$a = [];
$a[0] = 1;
$a["0"] = 2;
$a[1] = 3;
$a["1"] = 4;
$b = $a;
$b[2] = 5;
$c = [];
$c["x"] = $a[0] + $a["0"] + $a[1] + $a["1"] + $b[2];
for ($i = 0; $i < 3; $i++) {
    $c[$i] = ($c["x"] ?? 0) + $i;
}
foreach ($c as $k => $v) {
    $d[$k] = $v;
}
echo count($d) . "\n";
?>
--EXPECT--
