--TEST--
Translated seed (inferredbugs-csharp: cscs/85)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "c\t3"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($lines); $i++) {
    $parts = explode("\t", $lines[$i]);
    $map[$parts[0]] = $i;
}
$nested = [];
$nested["x"] = [];
$nested["x"]["y"] = 1;
$nested["x"]["y"] += 2;
$nested["x"]["z"] = ["k" => "v"];
$copy = $nested;
$copy["x"]["y"]++;
$copy["x"]["z"]["k"] .= "!";
$singletonKey = json_encode(["a" => 1, "b" => [2, 3]]);
static $singletons = [];
if (!array_key_exists($singletonKey, $singletons)) {
    $singletons[$singletonKey] = ["value" => $nested, "tokens" => $allTokens, "map" => $map];
}
$result = $singletons[$singletonKey];
echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
--EXPECT--
