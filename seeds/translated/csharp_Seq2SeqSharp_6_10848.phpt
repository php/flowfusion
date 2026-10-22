--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/6)
--FILE--
<?php
$deviceIds = [0, 1, 2];
$i = 0;
$next = fn() => $deviceIds[($GLOBALS['i']++) % count($deviceIds)];

$row = 5;
$column = 8;
$pos = array_fill(0, $row * $column, 0.0);
$numTimescales = $column / 2.0;
$logTimescaleIncrement = log(10000.0) / ($numTimescales - 1.0);

for ($p = 0; $p < $row; $p++) {
    for ($j = 0; $j < $numTimescales; $j++) {
        $v = $p * exp($j * -$logTimescaleIncrement);
        $pos[$p * $column + $j] = sin($v);
        $pos[$p * $column + (int)$numTimescales + $j] = cos($v);
    }
}

$sentences = [
    ["a", "b", "</s>", "x", "y"],
    ["c", "</s>", "</s>", "z"],
    ["d", "e", "f"]
];

foreach ($sentences as &$s) {
    for ($k = 0; $k < count($s); $k++) {
        if ($s[$k] === "</s>") {
            $s = array_slice($s, 0, $k + 1);
            break;
        }
    }
}
unset($s);

$beam = [[0, 1, 2], [0, 3], [0, 4, 5, 2]];
$top = [];
foreach ($beam as $b) {
    $top[] = [$b, count($b)];
}
usort($top, fn($a, $b) => $a[1] <=> $b[1]);

$ids = [];
for ($t = 0; $t < 6; $t++) {
    $ids[] = $next();
}

echo count($pos) . "\n";
echo count($sentences) . "\n";
echo count($top) . "\n";
echo implode(",", $ids) . "\n";
?>
--EXPECT--
