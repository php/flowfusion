--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/24)
--FILE--
<?php
mt_srand((int)(microtime(true) * 1000));

function randf() { return mt_rand() / mt_getrandmax(); }

$batchSize = 5;
$vocabSize = 20;

$a = [];
$b = [];
$idx = [];
for ($i = 0; $i < $batchSize; $i++) {
    $idx[$i] = mt_rand(0, $vocabSize - 1);
    for ($j = 0; $j < $vocabSize; $j++) {
        $a[$i][$j] = randf();
        $b[$i][$j] = randf();
    }
}

$sum = [];
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $sum[$i][$j] = $a[$i][$j] + $b[$i][$j];
        $negAdd[$i][$j] = (-$a[$i][$j]) + 100.0;
        $sub[$i][$j] = 100.0 - $b[$i][$j];
    }
}

for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $e = exp($negAdd[$i][$j]);
        $s1[$i][$j] = $e;
        $e2 = exp($sub[$i][$j]);
        $s2[$i][$j] = $e2;
        $z1[$i] = ($z1[$i] ?? 0.0) + $e;
        $z2[$i] = ($z2[$i] ?? 0.0) + $e2;
    }
}

for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $p1[$i][$j] = $s1[$i][$j] / $z1[$i];
        $p2[$i][$j] = $s2[$i][$j] / $z2[$i];
    }
}

$gA = $gB = array_fill(0, $batchSize, array_fill(0, $vocabSize, 0.0));
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $grad = $p1[$i][$j] + $p2[$i][$j];
        $gA[$i][$j] += -$grad;
        $gB[$i][$j] += -$grad;
        if ($idx[$i] == $j) {
            $gA[$i][$j] += 1.0;
            $gB[$i][$j] += 1.0;
        }
    }
}

$sum1 = $sum2 = $atomic = 0.0;
for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $sum1 += $a[$i][$j];
        $sum2 += $sum[$i][$j];
        $atomic += $a[$i][$j] + $b[$i][$j];
    }
}

for ($i = 0; $i < $batchSize; $i++) {
    for ($j = 0; $j < $vocabSize; $j++) {
        $sumB = 0.0;
        for ($k = 0; $k < $vocabSize; $k++) $sumB += $b[$i][$k];
        $sumRow[$i] = $sumB;
    }
}

echo "";
?>
--EXPECT--
