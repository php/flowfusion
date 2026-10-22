--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/8)
--FILE--
<?php
$deviceIds = [0];
$currentIdx = 0;
$round = function() use (&$deviceIds, &$currentIdx) {
    $item = $deviceIds[$currentIdx];
    $currentIdx = ($currentIdx + 1) % count($deviceIds);
    return $item;
};

$shared = false;
$srcEmbedding = $shared ? null : array_fill(0, 3, array_fill(0, 4, 0.0));
$tgtEmbedding = $shared ? null : array_fill(0, 5, array_fill(0, 6, 0.0));
$posEmbedding = (128 > 128 ? array_fill(0, 2, array_fill(0, 8, 0.0)) : null);

$srcSnts = [['a', 'b', 'c'], ['d', 'e']];
$tgtSnts = [['x', 'y', 'z'], ['u', 'v']];
$batchSize = count($srcSnts);

for ($i = 0; $i < $batchSize; $i++) {
    $round();
}

$pad = function(array &$sents, string $padToken = '<pad>') {
    $max = 0;
    foreach ($sents as $s) $max = max($max, count($s));
    $lengths = [];
    foreach ($sents as &$s) {
        $lengths[] = count($s);
        while (count($s) < $max) $s[] = $padToken;
    }
    return $lengths;
};

$srcLengths = $pad($srcSnts);
$tgtLengths = $pad($tgtSnts, '<eos>');

$srcSeqPaddedLen = count($srcSnts[0]);
$srcSelfMask = null;

$srcTokensList = [];
foreach ($srcSnts as $s) {
    $row = [];
    foreach ($s as $t) $row[] = ord($t[0]) % 10;
    $srcTokensList[] = $row;
}

$tgtTokensList = [];
foreach ($tgtSnts as $s) {
    $row = [];
    foreach ($s as $t) $row[] = ord($t[0]) % 10;
    $tgtTokensList[] = $row;
}

$encOutput = [];
foreach ($srcTokensList as $row) {
    foreach ($row as $v) $encOutput[] = $v;
}

$probs = [];
foreach ($tgtTokensList as $row) {
    $probs[] = array_fill(0, 10, 0.1);
}

$beam = [];
foreach ($tgtTokensList as $row) {
    $beam[] = [$row];
}

echo "";
?>
--EXPECT--
