--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/20)
--FILE--
<?php
$src = [["a", "b", "CLS", "c"], ["x", "CLS", "y", "z"], ["CLS", "m", "n", "o"]];
$tgt = [["label1"], ["label2"], ["label3"]];
$batchSize = count($src);
$srcSeqPaddedLen = count($src[0]);
$clsIdxs = array_fill(0, $batchSize, 0.0);
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < count($src[$i]); $j++) {
        if ($src[$i][$j] === "CLS") {
            $clsIdxs[$i] = $i * $srcSeqPaddedLen + $j;
            break;
        }
    }
}
$encOutput = [];
for ($i = 0; $i < $batchSize * $srcSeqPaddedLen; $i++) {
    $encOutput[$i] = $i + 0.5;
}
$clsWeights = [];
for ($i = 0; $i < $batchSize; $i++) {
    $clsWeights[$i] = $encOutput[(int)$clsIdxs[$i]];
}
$logits = [];
for ($i = 0; $i < $batchSize; $i++) {
    $logits[$i] = [$clsWeights[$i] - 1.0, $clsWeights[$i], $clsWeights[$i] + 1.0];
}
$probs = [];
for ($i = 0; $i < $batchSize; $i++) {
    $max = max($logits[$i]);
    $sum = 0.0;
    for ($k = 0; $k < count($logits[$i]); $k++) {
        $probs[$i][$k] = exp($logits[$i][$k] - $max);
        $sum += $probs[$i][$k];
    }
    for ($k = 0; $k < count($probs[$i]); $k++) {
        $probs[$i][$k] /= $sum;
    }
}
$labels = [0, 1, 2];
$cost = 0.0;
for ($i = 0; $i < $batchSize; $i++) {
    $p = $probs[$i][$labels[$i]];
    $cost += -log($p);
    $probs[$i][$labels[$i]] = $p - 1.0;
}
$pred = [];
for ($i = 0; $i < $batchSize; $i++) {
    $mx = 0;
    for ($k = 1; $k < count($probs[$i]); $k++) {
        if ($probs[$i][$k] > $probs[$i][$mx]) $mx = $k;
    }
    $pred[] = $mx;
}
echo implode(",", $pred), "\n";
echo number_format($cost / $batchSize, 6, ".", ""), "\n";
?>
--EXPECT--
