--TEST--
Translated seed (inferredbugs-csharp: cscs/68)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "b\t5"];
$tokens = [];
foreach ($lines as $line) {
    $tokens[] = explode("\t", $line);
}
$counts = [];
$prev = null;
$run = 0;
foreach ($tokens as $row) {
    $v = $row[0];
    if ($v !== $prev) {
        $prev = $v;
        $run = 0;
    }
    $counts[] = $run;
    $run++;
}
$map = [];
foreach ($tokens as $i => $row) {
    $map[$row[0]] = $counts[$i];
    if ($row[0] !== $row[1]) {
        $map[$row[1]] = $counts[$i];
    }
}
$arr = [];
$arr["x"] = ["y" => 1];
$arr["x"]["z"] = 2;
$arr["x"]["y"] += 3;
$arr["x"]["z"] .= "4";
$copy = unserialize(serialize($arr));
$s = "42";
$n = (int)$s;
$f = (float)"3.14";
$b = filter_var("true", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
$k = array_keys($map);
sort($k);
echo json_encode([$tokens, $counts, $map, $copy, $n, $f, $b, $k], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
