--TEST--
Translated seed (inferredbugs-csharp: cscs/10)
--FILE--
<?php
$lines = ["1\t2\t3", "4\t5", "6\t7\t8\t9"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$col = [];
for ($i = 0; $i < count($allTokens); $i++) {
    if (!array_key_exists(2, $allTokens[$i])) {
        continue;
    }
    $col[] = $allTokens[$i][2];
}
$map = [];
$current = null;
$currentCount = 0;
for ($i = 0; $i < count($allTokens); $i++) {
    $v = $allTokens[$i][1] ?? "";
    if ($v !== $current) {
        $current = $v;
        $currentCount = 0;
    }
    $map[] = $currentCount++;
}
$nested = ["a" => ["b" => [1, 2, 3]]];
$nested["a"]["b"][5] = 99;
$nested["x"] = [];
$nested["x"]["y"] = "z";
$copy = unserialize(serialize($nested));
$size = is_array($copy["a"]["b"]) ? count($copy["a"]["b"]) : strlen((string)$copy["a"]["b"]);
$type = gettype($copy["x"]);
$contains = in_array(99, $copy["a"]["b"], true);
$sum = 0;
for ($i = 0; $i < 3; $i++) {
    if ($i === 1) continue;
    $sum += $i;
}
if ($contains && $size >= 6 && $type === "array" && $sum === 2 && count($col) === 2 && $map[0] === 0) {
    echo "";
}
?>
--EXPECT--
