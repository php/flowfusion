--TEST--
Translated seed (inferredbugs-csharp: cscs/22)
--FILE--
<?php
$lines = ["a\tb\tc", "a\td\te", "x\ty\tz", "x\tq\tw"];
$tokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($tokens); $i++) {
    $k = $tokens[$i][0];
    $map[$k][] = $i;
    if ($k !== "alias") {
        $map["alias"][] = $i;
    }
}
$all = [];
$prev = null;
$count = 0;
for ($i = 0; $i < count($tokens); $i++) {
    $v = $tokens[$i][0];
    if ($v !== $prev) {
        $prev = $v;
        $count = 0;
    }
    $all[] = $count++;
}
$x = ["p" => ["q" => 1], "r" => 2];
$y = $x;
$y["p"]["q"]++;
$z = $y["p"]["q"] . ":" . count($map["alias"]) . ":" . implode(",", $all);
echo $z;
?>
--EXPECT--
