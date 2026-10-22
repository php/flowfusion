--TEST--
Translated seed (inferredbugs-csharp: neolua/2)
--FILE--
<?php
$vals = ["0", "00", "08", "0x10", "1e3", " 42 ", "-0", "9223372036854775807", "9223372036854775808"];
foreach ($vals as $s) {
    $a = intval($s);
    $b = is_numeric($s) ? $s + 0 : null;
    $c = filter_var($s, FILTER_VALIDATE_INT);
    $d = filter_var($s, FILTER_VALIDATE_FLOAT);
    $e = (int)trim($s);
    $f = (float)$s;
}
$x = [];
$x["a"] = 1;
$y =& $x["a"];
$y = 2;
$z = &$x["a"];
$z++;
$u = ["k" => 1];
$v = $u;
$v["k"] = 3;
$w = &$u["k"];
$w = 4;
for ($i = 0; $i < 3; $i++) {
    $n = str_repeat("9", $i + 1);
    $m = $n + 0;
}
echo "ok\n";
?>
--EXPECT--
