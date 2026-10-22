--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/27)
--FILE--
<?php
mt_srand((int)(microtime(true) * 1000));

$batchSize = 5;
$vocabSize = 20;

$a = [];
$b = [];
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $a[$i][$j] = mt_rand() / mt_getrandmax();
        $b[$i][$j] = mt_rand() / mt_getrandmax();
    }
}

$sumA = 0.0;
$sumB = 0.0;
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $sumA += $a[$i][$j];
        $sumB += $b[$i][$j];
    }
}

for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $a[$i][$j] += $b[$i][$j];
    }
}

$sum2 = 0.0;
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $sum2 += $a[$i][$j];
    }
}

$neg = [];
$sub = [];
$soft1 = [];
$soft2 = [];
$labels = [];
for ($i = 0; $i < $batchSize; $i++) {
    $labels[$i] = mt_rand(0, $vocabSize - 1);
    $row1 = [];
    $row2 = [];
    $m1 = -INF;
    $m2 = -INF;
    for ($j = 0; $j < $vocabSize; $j++) {
        $row1[$j] = 100.0 - $a[$i][$j];
        $row2[$j] = 100.0 - $b[$i][$j];
        if ($row1[$j] > $m1) $m1 = $row1[$j];
        if ($row2[$j] > $m2) $m2 = $row2[$j];
    }
    $s1 = 0.0;
    $s2 = 0.0;
    for ($j = 0; $j < $vocabSize; $j++) {
        $row1[$j] = exp($row1[$j] - $m1);
        $row2[$j] = exp($row2[$j] - $m2);
        $s1 += $row1[$j];
        $s2 += $row2[$j];
    }
    for ($j = 0; $j < $vocabSize; $j++) {
        $soft1[$i][$j] = $row1[$j] / $s1;
        $soft2[$i][$j] = $row2[$j] / $s2;
    }
}

$gradA = [];
$gradB = [];
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $gradA[$i][$j] = $soft1[$i][$j] - ($labels[$i] === $j ? 1.0 : 0.0);
        $gradB[$i][$j] = $soft2[$i][$j] - ($labels[$i] === $j ? 1.0 : 0.0);
    }
}

$atomic = [];
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $atomic[$i][$j] = $a[$i][$j] + $b[$i][$j];
    }
}

echo "";
?>
--EXPECT--
