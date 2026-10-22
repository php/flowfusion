--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/23)
--FILE--
<?php
$rows = 3;
$cols = 5;
$seqs = [[1,2,1,4],[0,3,3,2],[4,4,1,0]];
$weights = [];
for ($i = 0; $i < $rows; $i++) {
    for ($j = 0; $j < $cols; $j++) {
        $weights[$i][$j] = ($j + 1) / ($cols + 1) + $i * 0.01;
    }
}
$topP = 0.95;
$repeatPenalty = 2.0;
$distancePenalty = 10.0;
$thresholdValue = 1.0 / ($cols * 100000.0);
$indices = array_fill(0, $rows, 0);
for ($i = 0; $i < $rows; $i++) {
    $seq = $seqs[$i];
    $lastPos = [];
    $cnt = [];
    foreach ($seq as $j => $tok) {
        if (!array_key_exists($tok, $lastPos)) {
            $lastPos[$tok] = $j;
            $cnt[$tok] = 0;
        } else {
            $lastPos[$tok] = $j;
        }
        $cnt[$tok]++;
    }
    $pairs = [];
    $adjustedSum = 0.0;
    for ($j = 0; $j < $cols; $j++) {
        $w = $weights[$i][$j];
        if ($w < $thresholdValue || isset($pairs[$w])) continue;
        if (isset($lastPos[$j])) {
            $offsetInSeq = $lastPos[$j];
            $w = ($w * (1.0 - exp((($offsetInSeq + 1 - count($seq)) / $distancePenalty)))) / (pow($repeatPenalty, $cnt[$j] ?? 0));
        }
        if ($w < $thresholdValue || isset($pairs[$w])) continue;
        $adjustedSum += $w;
        $pairs[$w] = $j;
    }
    if ($topP == 0.0) {
        if ($pairs) {
            ksort($pairs);
            $indices[$i] = array_values($pairs)[count($pairs) - 1];
        }
    } else {
        $seed = mt_rand() / mt_getrandmax() * $topP * $adjustedSum;
        $acc = 0.0;
        krsort($pairs);
        foreach ($pairs as $w => $id) {
            $acc += $w;
            if ($acc >= $seed) {
                $indices[$i] = $id;
                break;
            }
        }
    }
}
print_r($indices);
?>
--EXPECT--
