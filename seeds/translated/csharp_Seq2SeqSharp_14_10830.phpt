--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/14)
--FILE--
<?php
$src = [["<CLS>", "a", "b"], ["x", "<CLS>", "y", "z"]];
$batchSize = count($src);
$maxLen = 0;
foreach ($src as $s) $maxLen = max($maxLen, count($s));
$clsIdxs = array_fill(0, $batchSize, 0);
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < count($src[$i]); $j++) {
        if ($src[$i][$j] === "<CLS>") {
            $clsIdxs[$i] = $i * $maxLen + $j;
            break;
        }
    }
}
$encOutput = [];
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $maxLen; $j++) {
        $encOutput[] = ($j < count($src[$i]) ? strlen($src[$i][$j]) : 0) + $i;
    }
}
$clsWeights = [];
for ($i = 0; $i < $batchSize; $i++) {
    $clsWeights[] = $encOutput[$clsIdxs[$i]];
}
$probs = [];
foreach ($clsWeights as $w) {
    $row = [0.1, 0.2, 0.3, 0.4];
    $sum = array_sum($row);
    foreach ($row as &$v) $v /= $sum;
    unset($v);
    $probs[] = $row;
}
$targets = [3, 1];
for ($k = 0; $k < $batchSize; $k++) {
    $score = $probs[$k][$targets[$k]];
    $cost = -log($score);
    $probs[$k][$targets[$k]] = $score - 1.0;
}
$beam = [[["<BOS>", 1, 2], 0.0], [["<BOS>", 1, 3], 0.0]];
for ($step = 0; $step < 3; $step++) {
    $next = [];
    foreach ($beam as $b) {
        $seq = $b[0];
        $last = end($seq);
        $cand = [$last + 1, $last + 2];
        foreach ($cand as $c) {
            $n = $seq;
            $n[] = $c;
            $next[] = [$n, $b[1] + log($c + 1)];
        }
    }
    usort($next, fn($a, $b) => $b[1] <=> $a[1]);
    $beam = array_slice($next, 0, 2);
    $done = true;
    foreach ($beam as $b) if (end($b[0]) !== 4) $done = false;
    if ($done) break;
}
echo "";
?>
--EXPECT--
