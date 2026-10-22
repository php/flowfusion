--TEST--
Translated seed (inferredbugs-csharp: cscs/9)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$map = [];
for ($i = 0; $i < count($lines); $i++) {
    $parts = explode("\t", $lines[$i]);
    $k = $parts[0];
    $v = $i;
    $map[$k] = $v;
    if ($k !== "a") {
        $map["a"] = $v;
    }
}
$tokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}
$col = [];
for ($i = 0; $i < count($tokens); $i++) {
    $col[] = $tokens[$i][1];
}
$nested = ["x" => [["n" => "1"], ["n" => "2"]], "y" => [["n" => "3"]]];
$nested["x"][1]["n"] .= "4";
$nested["z"][0] = "new";
$copy = $nested;
$copy["x"][0]["n"] = "9";
$types = [gettype($nested), gettype($nested["x"]), gettype($nested["x"][1]["n"]), gettype($map["a"])];
$sizes = [count($nested), count($nested["x"]), strlen((string)$nested["x"][1]["n"]), strlen((string)$map["a"])];
$out = json_encode([$map, $tokens, $col, $copy, $types, $sizes], JSON_UNESCAPED_SLASHES);
echo $out, PHP_EOL;
?>
--EXPECT--
