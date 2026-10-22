--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/19)
--FILE--
<?php
$src = [[1, 2, 3], [4, 5, 6]];
$tgt = [[0], [0]];
$batchSize = count($src);
$srcSeqPaddedLen = count($src[0]);
$clsIdxs = array_fill(0, $batchSize, 0);

for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < count($src[$i]); $j++) {
        if ($src[$i][$j] === 2) {
            $clsIdxs[$i] = $i * $srcSeqPaddedLen + $j;
            break;
        }
    }
}

$encOutput = [];
for ($i = 0; $i < $batchSize * $srcSeqPaddedLen; $i++) {
    $encOutput[] = $i;
}

$clsWeightTensor = [];
for ($i = 0; $i < $batchSize; $i++) {
    $clsWeightTensor[] = $encOutput[$clsIdxs[$i]];
}

$ffLayer = [];
for ($i = 0; $i < $batchSize; $i++) {
    $ffLayer[] = [$clsWeightTensor[$i], $clsWeightTensor[$i] + 1, $clsWeightTensor[$i] + 2];
}

$probs = [];
for ($i = 0; $i < $batchSize; $i++) {
    $row = $ffLayer[$i];
    $max = max($row);
    $sum = 0.0;
    foreach ($row as $v) {
        $sum += exp($v - $max);
    }
    $soft = [];
    foreach ($row as $v) {
        $soft[] = exp($v - $max) / $sum;
    }
    $probs[] = $soft;
}

$out = [];
for ($i = 0; $i < $batchSize; $i++) {
    $best = 0;
    for ($j = 1; $j < count($probs[$i]); $j++) {
        if ($probs[$i][$j] > $probs[$i][$best]) {
            $best = $j;
        }
    }
    $out[] = [(string)$best];
}

echo json_encode($out), PHP_EOL;
--EXPECT--
