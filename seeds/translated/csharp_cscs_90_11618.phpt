--TEST--
Translated seed (inferredbugs-csharp: cscs/90)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($lines); $i++) {
    $k = $allTokens[$i][0];
    $map[$k] = $i;
    if (!isset($map["alias"]) && strcasecmp("alias", $k) !== 0) {
        $map["alias"] = $i;
    }
}
$nested = [];
$nested["x"] = ["y" => 1];
$nested["x"]["z"] = 2;
$copy = $nested;
$copy["x"]["y"]++;
$size = count($allTokens) + count($nested["x"]);
echo $map["a"] . " " . $map["alias"] . " " . $copy["x"]["y"] . " " . $size . "\n";
?>
--EXPECT--
