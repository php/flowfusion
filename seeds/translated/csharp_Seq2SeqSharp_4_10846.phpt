--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/4)
--FILE--
<?php
$deviceIds = [0, 1, 2];
$idx = 0;
$n = count($deviceIds);
$round = function() use (&$deviceIds, &$idx, $n) {
    $v = $deviceIds[$idx];
    $idx = ($idx + 1) % $n;
    return $v;
};

$maxSrc = 128;
$maxTgt = 128;
$hiddenDim = 16;
$embeddingDim = 8;
$encoderType = 'Transformer';
$decoderType = 'Transformer';

$contextDim = ($encoderType === 'BiLSTM') ? $hiddenDim * 2 : $hiddenDim;
$dev1 = $round();
$dev2 = $round();
$dev3 = $round();
$dev4 = $round();

if ($encoderType === 'Transformer' || $decoderType === 'Transformer') {
    $rows = max($maxSrc, $maxTgt) + 2;
    $cols = $contextDim;
    $pos = array_fill(0, $rows, array_fill(0, $cols, 0.0));
    $numTimescales = $cols / 2.0;
    $logTimescaleIncrement = log(10000.0) / ($numTimescales - 1.0);
    for ($p = 0; $p < $rows; $p++) {
        for ($i = 0; $i < $numTimescales; $i++) {
            $v = $p * exp($i * -$logTimescaleIncrement);
            $pos[$p][$i] = sin($v);
            $pos[$p][(int)$numTimescales + $i] = cos($v);
        }
    }
}

$sentences = [
    ['a', 'b', '</s>', 'x', 'y'],
    ['c', '</s>', 'z'],
    ['d', 'e', 'f']
];

foreach ($sentences as &$s) {
    for ($i = 0; $i < count($s); $i++) {
        if ($s[$i] === '</s>') {
            $s = array_slice($s, 0, $i + 1);
            break;
        }
    }
}
unset($s);

$beam = [
    ['ids' => [0], 'score' => 0.0],
];
for ($step = 0; $step < 4; $step++) {
    $next = [];
    foreach ($beam as $b) {
        $last = $b['ids'][count($b['ids']) - 1];
        if ($last === 1) {
            $next[] = $b;
            continue;
        }
        for ($k = 0; $k < 2; $k++) {
            $nb = $b;
            $nb['ids'][] = ($last + $k + $step) % 5;
            $nb['score'] += -log(0.5);
            $next[] = $nb;
        }
    }
    usort($next, fn($a, $b) => $a['score'] <=> $b['score']);
    $beam = array_slice($next, 0, 2);
}

echo "";
?>
--EXPECT--
