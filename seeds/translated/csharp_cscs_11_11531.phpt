--TEST--
Translated seed (inferredbugs-csharp: cscs/11)
--FILE--
<?php
$lines = ["a\t1\tx", "a\t2\ty", "b\t3\tz", "b\t4\tw"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens = explode("\t", $lines[$i]);
    $hash = $tokens[0];
    $map[$hash] = $i;
    if ($hash !== "alias") {
        $map["alias"] = $i;
    }
}
$counter = [];
$currentValue = null;
$currentCount = 0;
foreach ($allTokens as $row) {
    if (count($row) < 2) break;
    $newValue = $row[1];
    if ($currentValue !== $newValue) {
        $currentValue = $newValue;
        $currentCount = 0;
    }
    $counter[] = $currentCount++;
}
$nested = ["root" => ["left" => [1, 2, 3], "right" => ["k" => "v"]]];
$nested["root"]["left"][5] = 99;
$nested["root"]["right"]["extra"] = "x";
$copy = $nested;
$copy["root"]["left"][0] = 42;
$contains = in_array(99, $nested["root"]["left"], true);
$type = gettype($nested["root"]["right"]);
$size = is_array($nested["root"]["left"]) ? count($nested["root"]["left"]) : strlen((string)$nested["root"]["left"]);
$sum = 0;
for ($i = 0; $i < 3; $i++) {
    if ($i === 1) continue;
    $sum += $i;
}
if ($contains && $type === "array" && $size >= 6 && $sum === 2 && $copy["root"]["left"][0] === 42) {
    echo "";
}
?>
--EXPECT--
