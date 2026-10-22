--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/2)
--FILE--
<?php
$deviceIds = [0, 1, 2];
$i = 0;
$n = count($deviceIds);
$next = function () use (&$i, $deviceIds, $n) {
    $v = $deviceIds[$i];
    $i = ($i + 1) % $n;
    return $v;
};

$rows = 7;
$cols = 6;
$pos = array_fill(0, $rows, array_fill(0, $cols, 0.0));
for ($p = 0; $p < $rows; $p++) {
    for ($j = 0; $j < $cols; $j += 2) {
        $angle = $p / pow(10000, 2 * (($j / 2)) / $cols);
        $pos[$p][$j] = sin($angle);
        $pos[$p][$j + 1] = cos($angle);
    }
}

$sentences = [
    ['a', 'b', '</s>', 'x', 'y'],
    ['c', '</s>', '</s>', 'z'],
    ['d', 'e', 'f']
];

foreach ($sentences as &$s) {
    for ($k = 0; $k < count($s); $k++) {
        if ($s[$k] === '</s>') {
            $s = array_slice($s, 0, $k + 1);
            break;
        }
    }
}
unset($s);

$beam = [
    ['ids' => [0], 'score' => 0.0],
];
for ($step = 0; $step < 5; $step++) {
    $new = [];
    foreach ($beam as $b) {
        $last = $b['ids'][count($b['ids']) - 1];
        if ($last === 1) {
            $new[] = $b;
            continue;
        }
        $dev = $next();
        $p = [0.6, 0.3, 0.1];
        for ($j = 0; $j < 3; $j++) {
            $nb = $b;
            $nb['ids'][] = $j;
            $nb['score'] += -log($p[$j]) + ($dev * 0.0);
            $new[] = $nb;
        }
    }
    usort($new, fn($a, $b) => $a['score'] <=> $b['score']);
    $beam = array_slice($new, 0, 2);
}

echo count($pos) . "\n";
echo count($sentences) . "\n";
echo count($beam) . "\n";
?>
--EXPECT--
