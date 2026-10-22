--TEST--
Translated seed (inferredbugs-csharp: cscs/20)
--FILE--
<?php
$lines = ["a,b,c", "d,e", "f"];
$allTokens = [];
for ($counter = 0; $counter < count($lines); $counter++) {
    $allTokens[] = explode(",", $lines[$counter]);
}

$map = [];
foreach ($allTokens as $i => $tokens) {
    $k = $tokens[0];
    $map[$k] = $i;
    if ($k !== "" && strcasecmp($k, "x") !== 0) {
        $map["x"] = $i;
    }
}

$values = [1, 2, 3];
$values[] = 4;
array_splice($values, 1, 1);
$values[2] += 1;

$nested = [["p" => 1], ["p" => 2]];
$nested[1]["q"] = $values[2];

$type = gettype($nested);
$size = is_array($nested) ? count($nested) : strlen((string)$nested);

echo $type, "\n", $size, "\n", $map["a"], "\n", $nested[1]["q"], "\n";
?>
--EXPECT--
