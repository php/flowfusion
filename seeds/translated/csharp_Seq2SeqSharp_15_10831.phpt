--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/15)
--FILE--
<?php
$src = [["<cls>", "a", "b"], ["x", "<cls>", "y", "z"]];
$tgt = [["<bos>", "u", "<eos>"], ["<bos>", "v", "<eos>"]];
$cls = [["cat"], ["dog"]];

$pad = function(array &$sents, string $padToken) {
    $max = 0;
    foreach ($sents as $s) $max = max($max, count($s));
    $lens = [];
    foreach ($sents as &$s) {
        $lens[] = count($s);
        while (count($s) < $max) $s[] = $padToken;
    }
    return $lens;
};

$srcLens = $pad($src, "<pad>");
$tgtLens = $pad($tgt, "<eos>");

$batch = count($src);
$srcSeqLen = count($src[0]);
$tgtSeqLen = count($tgt[0]);

$clsIdxs = array_fill(0, $batch, 0);
for ($i = 0; $i < $batch; $i++) {
    for ($j = 0; $j < $srcSeqLen; $j++) {
        if ($src[$i][$j] === "<cls>") {
            $clsIdxs[$i] = $i * $srcSeqLen + $j;
            break;
        }
    }
}

$encOutput = [];
for ($i = 0; $i < $batch * $srcSeqLen; $i++) $encOutput[$i] = $i + 1;

$clsLogits = [];
for ($i = 0; $i < $batch; $i++) {
    $x = $encOutput[$clsIdxs[$i]];
    $clsLogits[$i] = [$x % 3 + 1, ($x + 1) % 3 + 1];
}

$clsProbs = [];
for ($i = 0; $i < $batch; $i++) {
    $sum = array_sum($clsLogits[$i]);
    $clsProbs[$i] = [$clsLogits[$i][0] / $sum, $clsLogits[$i][1] / $sum];
}

$predCls = [];
for ($i = 0; $i < $batch; $i++) $predCls[] = $clsProbs[$i][0] >= $clsProbs[$i][1] ? "cat" : "dog";

$beam = [[
    ["<bos>", "u", "<eos>"],
    ["<bos>", "v", "<eos>"]
]];

for ($step = 0; $step < 3; $step++) {
    $next = [];
    foreach ($beam as $b) {
        $cand = [];
        foreach ($b as $seq) {
            $last = end($seq);
            if ($last === "<eos>") {
                $cand[] = $seq;
                continue;
            }
            $cand[] = array_merge($seq, [$step % 2 === 0 ? "u" : "v"]);
            $cand[] = array_merge($seq, ["<eos>"]);
        }
        $next[] = $cand[0];
    }
    $beam = $next;
}

echo implode(" ", $predCls) . PHP_EOL;
foreach ($beam as $seq) echo implode(" ", $seq) . PHP_EOL;
?>
--EXPECT--
