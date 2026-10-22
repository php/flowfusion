--TEST--
Translated seed (inferredbugs-csharp: cscs/55)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "b\t5"];
$all = [];
foreach ($lines as $line) {
    $all[] = explode("\t", $line);
}
$counts = [];
$current = null;
$run = 0;
foreach ($all as $row) {
    $v = $row[0];
    if ($v !== $current) {
        $current = $v;
        $run = 0;
    }
    $counts[] = $run;
    $run++;
}
$map = [];
foreach ($all as $i => $row) {
    $map[$row[0]] = $i;
}
$copy = $all;
$copy[1][1] = (string)((int)$copy[1][1] + 10);
$nested = [];
$nested["x"]["y"] = $copy[1][1];
$props = array_keys($nested["x"]);
echo implode(",", array_map("strval", $counts)) . "\n";
echo $map["b"] . "\n";
echo $nested["x"]["y"] . "\n";
echo implode(",", $props) . "\n";
?>
--EXPECT--
