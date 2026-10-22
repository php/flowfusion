--TEST--
Translated seed (inferredbugs-csharp: LiteDB/5)
--FILE--
<?php
$left = "1";
$right = 2;
echo $left . $right . PHP_EOL;
$arr = [1, 2, 3];
echo ($arr[-1] ?? null) . PHP_EOL;
$doc = ["a" => ["b" => 1], "x" => null];
echo ($doc["a"]["b"] ?? null) . PHP_EOL;
echo (in_array(2, [1, "2", 3], true) ? "1" : "0") . PHP_EOL;
$sum = 0;
foreach ([1, 2, 3, 4] as $v) {
    if ($v > 2) $sum += $v;
}
echo $sum . PHP_EOL;
?>
--EXPECT--
