--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/18)
--FILE--
<?php
$mode = $argv[1] ?? 'continuous';
$training = ($argv[2] ?? '0') === '1';
$cache = [];
$src1 = [['a', 'b'], ['c']];
$src2 = [['x'], ['y', 'z']];
$key1 = implode("\t", array_map(fn($s) => implode(' ', $s), $src1));
$key2 = implode("\t", array_map(fn($s) => implode(' ', $s), $src2));
$enc1 = $cache[$key1] ?? null;
$enc2 = $cache[$key2] ?? null;
if ($enc1 === null) {
    $enc1 = [1.0, 2.0, 3.0];
    $cache[$key1] = $enc1;
}
if ($enc2 === null) {
    $enc2 = [4.0, 5.0, 6.0];
    $cache[$key2] = $enc2;
}
if ($mode === 'continuous') {
    $dot = 0.0;
    $n1 = 0.0;
    $n2 = 0.0;
    for ($i = 0; $i < 3; $i++) {
        $dot += $enc1[$i] * $enc2[$i];
        $n1 += $enc1[$i] * $enc1[$i];
        $n2 += $enc2[$i] * $enc2[$i];
    }
    $score = $dot / sqrt($n1 * $n2);
    if ($training) {
        $gold = 0.5;
        $score -= $gold;
        $cost = abs($score);
    }
} else {
    $logits = [];
    for ($k = 0; $k < 2; $k++) {
        $logits[$k] = [];
        for ($j = 0; $j < 4; $j++) {
            $logits[$k][$j] = ($enc1[$k] ?? 0) * ($enc2[$k] ?? 0) + $j;
        }
    }
    $probs = [];
    for ($k = 0; $k < 2; $k++) {
        $m = max($logits[$k]);
        $sum = 0.0;
        foreach ($logits[$k] as $v) $sum += exp($v - $m);
        foreach ($logits[$k] as $j => $v) $probs[$k][$j] = exp($v - $m) / $sum;
    }
    if ($training) {
        $targets = [1, 2];
        $cost = 0.0;
        foreach ($targets as $k => $ix) {
            $cost += -log($probs[$k][$ix]);
            $probs[$k][$ix] -= 1.0;
        }
    } else {
        $out = [];
        foreach ($probs as $row) {
            $out[] = [array_search(max($row), $row, true)];
        }
    }
}
echo "ok\n";
--EXPECT--
