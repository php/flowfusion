--TEST--
Translated seed (inferredbugs-csharp: cscs/21)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($allTokens); $i++) {
    $k = $allTokens[$i][0];
    $map[$k] = $i;
    $map[strtolower($k)] = $i;
}
$props = [];
$props["x"] = $map["a"];
$props["y"] = $map["b"];
$copy = $props;
$copy["x"]++;
$copy["y"] += 2;
$nested = [["v" => $copy["x"]], ["v" => $copy["y"]]];
$nested[0]["v"] += $nested[1]["v"];
$type = gettype($nested);
$size = count($nested) . ":" . strlen((string)$nested[0]["v"]);
echo $type . "\n";
echo $size . "\n";
echo $nested[0]["v"] . "\n";
echo $nested[1]["v"] . "\n";
?>
--EXPECT--
