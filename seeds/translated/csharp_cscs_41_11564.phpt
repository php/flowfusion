--TEST--
Translated seed (inferredbugs-csharp: cscs/41)
--FILE--
<?php
$xs = [];
for ($i = 0; $i < 3; $i++) {
    $xs[] = ["k" => $i, "v" => str_repeat((string)$i, $i + 1)];
}
$map = [];
foreach ($xs as $x) {
    $map[$x["k"]] = $x["v"];
}
$copy = $map;
$copy[1] .= "x";
$copy[2] = $copy[2] ?? "";
$copy[2] = substr($copy[2], 0, 1) . substr($copy[2], 1);
$nested = ["a" => ["b" => [1, 2, 3]]];
$nested["a"]["b"][1]++;
$nested["a"]["b"][] = 4;
$nested["a"]["c"] = $copy;
$sum = 0;
foreach ($nested["a"]["b"] as $n) {
    $sum += $n;
}
if (isset($nested["a"]["c"][1])) {
    $sum += strlen($nested["a"]["c"][1]);
}
echo $sum, "\n";
?>
--EXPECT--
