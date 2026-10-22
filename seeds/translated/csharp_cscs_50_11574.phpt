--TEST--
Translated seed (inferredbugs-csharp: cscs/50)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "b\t5"];
$allTokens = [];
foreach ($lines as $line) {
    $allTokens[] = explode("\t", $line);
}
$counts = [];
$current = null;
$count = 0;
foreach ($allTokens as $row) {
    $v = $row[0];
    if ($v !== $current) {
        $current = $v;
        $count = 0;
    }
    $counts[] = $count;
    $count++;
}
$map = [];
foreach ($allTokens as $i => $row) {
    $map[$row[0]] = $counts[$i];
}
$copy = $map;
$copy["b"] = $copy["b"] + 1;
$nested = ["x" => [["y" => 1], ["y" => 2]]];
$nested["x"][1]["y"] += 3;
$props = array_keys($nested);
echo implode(",", $counts), "\n";
echo $map["a"], " ", $map["b"], "\n";
echo $copy["b"], "\n";
echo $nested["x"][1]["y"], "\n";
echo implode(",", $props), "\n";
?>
--EXPECT--
