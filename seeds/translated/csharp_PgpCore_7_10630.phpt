--TEST--
Translated seed (inferredbugs-csharp: PgpCore/7)
--FILE--
<?php
$input = "abc";
$parts = str_split($input, 1);
$parts[] = "";
$parts[] = "x";
$out = "";
foreach ($parts as $p) {
    $out .= strtoupper($p);
}
$map = [];
foreach (str_split($out) as $i => $ch) {
    $map[$i] = ord($ch) & 255;
}
$sum = 0;
foreach ($map as $v) {
    $sum += $v;
}
echo $sum, "\n";
?>
--EXPECT--
