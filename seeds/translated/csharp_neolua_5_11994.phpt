--TEST--
Translated seed (inferredbugs-csharp: neolua/5)
--FILE--
<?php
$g = [];
$a = 0;
$b = 1;
for ($i = 0; $i < 1000; $i++) {
    $g[$i] = [$a, $b, $a + $b];
    $a = $b;
    $b = $g[$i][2] & 2147483647;
}
$s = "";
foreach ($g as $v) {
    $s .= $v[0] . ":" . $v[1] . ":" . $v[2] . ";";
}
echo substr($s, 0, 1);
?>
--EXPECT--
