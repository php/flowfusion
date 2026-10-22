--TEST--
Translated seed (inferredbugs-csharp: cscs/84)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "b\t5"];
$all = [];
for ($i = 0; $i < count($lines); $i++) {
    $all[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($all); $i++) {
    $k = $all[$i][0];
    if (!isset($map[$k])) $map[$k] = [];
    $map[$k][] = $i;
}
$tokens = [];
for ($i = 0; $i < count($all); $i++) {
    $tokens[] = $all[$i][1];
}
$copy = $tokens;
$copy[] = "6";
$copy[1] = "20";
$nested = [];
$nested["x"] = ["y" => ["z" => 7]];
$nested["x"]["y"]["z"]++;
$s = "9";
$n = (int)$s;
$f = (float)$s;
$b = filter_var("true", FILTER_VALIDATE_BOOLEAN);
$joined = implode(",", $tokens);
echo $map["a"][0] . $map["b"][2] . $copy[3] . $nested["x"]["y"]["z"] . $n . $f . ($b ? 1 : 0) . $joined . PHP_EOL;
?>
--EXPECT--
