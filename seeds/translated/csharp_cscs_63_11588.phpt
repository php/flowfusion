--TEST--
Translated seed (inferredbugs-csharp: cscs/63)
--FILE--
<?php
$lines = ["a\t1", "a\t2", "b\t3", "b\t4", "b\t5"];
$tokens = [];
foreach ($lines as $line) {
    $tokens[] = explode("\t", $line);
}
$counts = [];
$prev = null;
$run = 0;
foreach ($tokens as $row) {
    $v = $row[0];
    if ($v !== $prev) {
        $prev = $v;
        $run = 0;
    }
    $counts[] = $run;
    $run++;
}
$map = [];
foreach ($tokens as $i => $row) {
    $map[$row[0]][] = $i;
}
$nested = [];
foreach ($map as $k => $idxs) {
    $nested[$k] = ["idxs" => $idxs, "count" => count($idxs)];
}
$copy = unserialize(serialize($nested));
$copy["a"]["idxs"][0] = 99;
echo json_encode([$tokens, $counts, $nested, $copy], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
