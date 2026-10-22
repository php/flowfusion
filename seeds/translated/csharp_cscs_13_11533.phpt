--TEST--
Translated seed (inferredbugs-csharp: cscs/13)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "c\t5"];
$all = [];
for ($i = 0; $i < count($lines); $i++) {
    $all[] = explode("\t", $lines[$i]);
}
$counts = [];
$current = null;
$cnt = 0;
for ($i = 0; $i < count($all); $i++) {
    $v = $all[$i][0];
    if ($v !== $current) {
        $current = $v;
        $cnt = 0;
    }
    $counts[] = $cnt++;
}
$map = [];
for ($i = 0; $i < count($all); $i++) {
    $k = $all[$i][0];
    $map[$k] = $counts[$i];
}
$nested = ["x" => [10, 20, 30], "y" => ["p" => 1, "q" => 2]];
$nested["x"][1] += 5;
$nested["y"]["q"] = (string)$nested["y"]["q"] . "7";
$copy = $nested;
$copy["x"][2]--;
$type1 = gettype($nested["x"]);
$type2 = gettype($nested["y"]["q"]);
$size1 = count($nested["x"]);
$size2 = strlen((string)$nested["y"]["q"]);
$parsed = (int)"42";
$bool = filter_var("true", FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
echo $map["a"] . $map["b"] . $map["c"] . $type1 . $type2 . $size1 . $size2 . $parsed . $bool . $copy["x"][2];
?>
--EXPECT--
