--TEST--
Translated seed (inferredbugs-csharp: cscs/69)
--FILE--
<?php
$lines = ["a,b,c", "d,e,f", "g,h,i"];
$tokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode(",", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($tokens); $i++) {
    $key = $tokens[$i][0];
    $map[$key] = $i;
    if ($key !== "x") {
        $map["x"] = $i;
    }
}
$nested = ["outer" => ["inner" => [1, 2, 3]]];
$nested["outer"]["inner"][1] += 1;
$nested["outer"]["inner"][] = $map["a"] ?? 0;
$copy = unserialize(serialize($nested));
$copy["outer"]["inner"][0] = (int)$copy["outer"]["inner"][0] + 1;
$size = count($copy["outer"]["inner"]);
$type = gettype($size);
echo $tokens[1][2] . "\n";
echo $map["d"] . "\n";
echo $copy["outer"]["inner"][1] . "\n";
echo $size . "\n";
echo $type . "\n";
?>
--EXPECT--
