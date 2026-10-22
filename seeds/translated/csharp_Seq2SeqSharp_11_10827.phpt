--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/11)
--FILE--
<?php
$src = [
    ['[CLS]', 'a', '[SEP]', 'b'],
    ['[CLS]', 'x', 'y', '[SEP]'],
    ['[CLS]', '[SEP]', 'z', 'w'],
];

$batch = count($src);
$len = count($src[0]);
$ids = [];
$seg = [];

for ($i = 0; $i < $batch; $i++) {
    $s = 0;
    for ($j = 0; $j < $len; $j++) {
        $ids[] = $src[$i][$j];
        $seg[] = $s;
        if ($src[$i][$j] === '[SEP]') $s++;
    }
}

$emb = [];
$segEmb = [];
for ($i = 0; $i < 8; $i++) $emb[$i] = [$i, $i + 0.5];
for ($i = 0; $i < 2; $i++) $segEmb[$i] = [$i * 10, $i * 10 + 1];

$out = [];
for ($i = 0; $i < count($ids); $i++) {
    $e = $emb[$i % 8];
    $s = $segEmb[$seg[$i] % 2];
    $out[] = [$e[0] + $s[0], $e[1] + $s[1]];
}

$cls = [];
for ($i = 0; $i < $batch; $i++) {
    for ($j = 0; $j < $len; $j++) {
        if ($src[$i][$j] === '[CLS]') {
            $cls[] = $out[$i * $len + $j];
            break;
        }
    }
}

$logits = [];
foreach ($cls as $v) $logits[] = $v[0] + $v[1];

$mx = max($logits);
$sum = 0.0;
$probs = [];
foreach ($logits as $x) {
    $p = exp($x - $mx);
    $probs[] = $p;
    $sum += $p;
}
foreach ($probs as &$p) $p /= $sum;

$pred = [];
foreach ($probs as $p) $pred[] = $p > 0.5 ? 'POS' : 'NEG';

echo implode(',', $pred), PHP_EOL;
?>
--EXPECT--
