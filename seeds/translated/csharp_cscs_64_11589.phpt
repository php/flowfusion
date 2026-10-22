--TEST--
Translated seed (inferredbugs-csharp: cscs/64)
--FILE--
<?php
$lines = ["a,b,c", "d,e", "f"];
$allTokens = [];
for ($fromLine = 0; $fromLine < count($lines); $fromLine++) {
    $line = $lines[$fromLine];
    $allTokens[] = explode("\t", $line);
}
$map = [];
for ($i = 0; $i < count($allTokens); $i++) {
    $current = $allTokens[$i];
    if (!isset($current[1])) {
        continue;
    }
    $key = $current[1];
    if (!isset($map[$key])) {
        $map[$key] = [];
    }
    $map[$key][] = $i;
}
$nested = ["x" => [["y" => 1], ["y" => 2]]];
$nested["x"][1]["y"] += 1;
$copy = $nested;
$copy["x"][0]["y"] = 99;
echo count($allTokens), "\n";
echo count($map), "\n";
echo $nested["x"][1]["y"], "\n";
echo $copy["x"][0]["y"], "\n";
?>
--EXPECT--
