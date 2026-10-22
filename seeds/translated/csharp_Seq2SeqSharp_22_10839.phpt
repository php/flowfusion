--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/22)
--FILE--
<?php
$rows = 3;
$cols = 5;
$weights = [
    [0.1, 0.4, 0.2, 0.05, 0.25],
    [0.3, 0.05, 0.15, 0.2, 0.3],
    [0.01, 0.6, 0.12, 0.12, 0.15],
];
$seqs = [
    [1, 3, 1, 4],
    [0, 2, 2, 4, 1],
    [3, 3, 1],
];
$topP = 0.95;
$repeatPenalty = 2.0;
$distancePenalty = 10.0;
$thresholdValue = 1.0 / ($cols * 100000.0);
$indices = array_fill(0, $rows, 0.0);
mt_srand((int)(microtime(true) * 1000000));

for ($i = 0; $i < $rows; $i++) {
    $seq = $seqs[$i];
    $tokenId2OffsetInSeq = [];
    $tokenId2Cnt = [];
    for ($j = 0, $n = count($seq); $j < $n; $j++) {
        $tok = $seq[$j];
        $tokenId2OffsetInSeq[$tok] = $j;
        $tokenId2Cnt[$tok] = ($tokenId2Cnt[$tok] ?? 0) + 1;
    }

    $weight2tokenId = [];
    $adjustedSum = 0.0;
    for ($j = 0; $j < $cols; $j++) {
        $weight = $weights[$i][$j];
        if ($weight < $thresholdValue || isset($weight2tokenId[(string)$weight])) continue;
        if (isset($tokenId2OffsetInSeq[$j])) {
            $offsetInSeq = $tokenId2OffsetInSeq[$j];
            $weight = ($weight * (1.0 - exp((($offsetInSeq + 1) - count($seq)) / $distancePenalty))) / pow($repeatPenalty, $tokenId2Cnt[$j]);
        }
        if ($weight < $thresholdValue || isset($weight2tokenId[(string)$weight])) continue;
        $adjustedSum += $weight;
        $weight2tokenId[(string)$weight] = $j;
    }

    $seed = (mt_rand() / mt_getrandmax()) * $topP * $adjustedSum;
    $acc = 0.0;
    krsort($weight2tokenId, SORT_NUMERIC);
    foreach ($weight2tokenId as $w => $tok) {
        $acc += (float)$w;
        if ($acc >= $seed) {
            $indices[$i] = (float)$tok;
            break;
        }
    }
}

echo implode("\n", array_map(fn($v) => (string)$v, $indices)) . "\n";
?>
--EXPECT--
