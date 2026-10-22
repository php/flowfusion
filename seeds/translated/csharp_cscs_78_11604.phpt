--TEST--
Translated seed (inferredbugs-csharp: cscs/78)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$map = [];
foreach ($lines as $i => $line) {
    [$k] = explode("\t", $line, 2);
    $map[$k][] = $i;
}
$nested = [];
foreach ($map as $k => $idxs) {
    $nested[$k] = ["first" => $idxs[0], "count" => count($idxs)];
}
$copy = $nested;
$copy["a"]["count"] += 1;
$copy["b"]["first"] = 99;
$tokens = array_map(fn($s) => explode("\t", $s), $lines);
$cols = array_column($tokens, 1);
$exists = array_key_exists("c", $map) && in_array("4", $cols, true);
$val = $nested["a"]["count"] . ":" . $copy["a"]["count"] . ":" . ($exists ? "1" : "0");
echo $val, PHP_EOL;
?>
--EXPECT--
