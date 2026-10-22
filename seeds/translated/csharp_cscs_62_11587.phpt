--TEST--
Translated seed (inferredbugs-csharp: cscs/62)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$map = [];
for ($i = 0; $i < count($lines); $i++) {
    $parts = explode("\t", $lines[$i]);
    $k = $parts[0];
    if (!isset($map[$k])) $map[$k] = [];
    $map[$k][] = $i;
    if ($k !== "x") $map["x"][] = $i;
}
$tokens = [];
for ($i = 0; $i < count($lines); $i++) $tokens[] = explode("\t", $lines[$i]);
$col = [];
for ($i = 0; $i < count($tokens); $i++) $col[] = $tokens[$i][1];
$nested = [];
$nested["p"]["q"] = 7;
$nested["p"]["r"] = 8;
$copy = $nested;
$copy["p"]["q"]++;
$copy["p"]["r"] += 2;
$values = [0, 1, 2];
array_splice($values, 1, 1);
$values[] = 9;
$assoc = ["k" => 1, "m" => 2];
unset($assoc["k"]);
$assoc["z"] = 3;
$sum = 0;
foreach ($map as $v) $sum += count($v);
foreach ($col as $v) $sum += (int)$v;
foreach ($copy["p"] as $v) $sum += $v;
echo $sum, "\n";
?>
--EXPECT--
