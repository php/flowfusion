--TEST--
Translated seed (inferredbugs-csharp: cscs/25)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$tokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($tokens); $i++) {
    $k = $tokens[$i][0];
    $map[$k] = $i;
    if ($k !== "x") {
        $map["x"] = $i;
    }
}
$copy = $map;
$copy["a"] = 99;
$size = count($tokens);
$exists = array_key_exists("b", $map);
$type = gettype($copy);
echo $size . "\n" . ($exists ? "1" : "0") . "\n" . $type . "\n";
?>
--EXPECT--
