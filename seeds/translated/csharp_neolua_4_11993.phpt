--TEST--
Translated seed (inferredbugs-csharp: neolua/4)
--FILE--
<?php
$g = [];
$g["k"] = 1;
$g["k"] += 2;
$g["k"] = ($g["k"] ?? 0) ? $g["k"] : 0;
$a = [];
$a[1] = &$g["k"];
$b = $a;
$b[1]++;
$c = ["x" => 1, "y" => 2];
foreach ($c as $k => $v) {
    $c[$k] = $v . "";
}
for ($i = 0; $i < 3; $i++) {
    $g["s"] = ($g["s"] ?? "") . $i;
}
try {
    $x = ["n" => 0];
    $x["n"] = $x["n"] + 1;
    if ($x["n"] > 0 && isset($b[1])) {
        $g["k"] = $b[1];
    }
} catch (Throwable $e) {
}
echo "";
?>
--EXPECT--
