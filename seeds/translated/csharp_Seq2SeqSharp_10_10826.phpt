--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/10)
--FILE--
<?php
$items = [10, 20, 30];
$i = 0;
$out = [];
for ($n = 0; $n < 8; $n++) {
    $out[] = $items[$i];
    $i = ($i + 1) % count($items);
}

$seqs = [
    [1, 2, 3, 4],
    [5, 6, 7, 8],
];
$seg = [];
foreach ($seqs as $s) {
    $segRow = [];
    $segIdx = 0;
    foreach ($s as $tok) {
        $segRow[] = $segIdx;
        if ($tok === 3 || $tok === 7) {
            $segIdx++;
        }
    }
    $seg[] = $segRow;
}

$pad = function(array $rows, int $padVal = 0): array {
    $max = 0;
    foreach ($rows as $r) {
        $max = max($max, count($r));
    }
    foreach ($rows as &$r) {
        while (count($r) < $max) {
            $r[] = $padVal;
        }
    }
    return $rows;
};

$src = $pad([[1, 2, 0], [3, 0]]);
$tgt = $pad([[9, 8, 7, 0], [6, 5, 0, 0]]);
$flat = [];
foreach ($src as $r) {
    foreach ($r as $v) {
        $flat[] = $v;
    }
}
foreach ($tgt as $r) {
    foreach ($r as $v) {
        $flat[] = $v;
    }
}

$map = ["a" => 1, "b" => 2, "c" => 3];
foreach ($map as $k => $v) {
    $x = $map[$k] ?? null;
    if ($x !== $v) {
        throw new Exception("unexpected");
    }
}

echo implode(",", $out), "\n";
echo json_encode($seg), "\n";
echo count($flat), "\n";
?>
--EXPECT--
