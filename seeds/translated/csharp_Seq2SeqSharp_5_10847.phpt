--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/5)
--FILE--
<?php
$maxSntSize = 128;
$embeddingDim = 6;
$row = $maxSntSize + 2;
$posWeights = [];
for ($p = 0; $p < $row; $p++) {
    for ($i = 0; $i < $embeddingDim; $i += 2) {
        $angle = $p / pow(10000, 2 * (int)($i / 2) / $embeddingDim);
        $posWeights[$p][$i] = sin($angle);
        $posWeights[$p][$i + 1] = cos($angle);
    }
}
$src = [["a", "b", "c"], ["d", "e", "f"]];
$seqLen = count($src[0]);
$batchSize = count($src);
$input = [];
for ($j = 0; $j < $batchSize; $j++) {
    for ($i = 0; $i < $seqLen; $i++) {
        $input[] = [$j, $i, $posWeights[$i]];
    }
}
for ($k = 0; $k < $batchSize; $k++) {
    for ($j = 0; $j < $seqLen; $j++) {
        $x = $input[$k * $seqLen + $j][2];
        $y = [];
        for ($d = 0; $d < $embeddingDim; $d++) {
            $y[$d] = $x[$d] + $posWeights[$j][$d];
        }
        $input[$k * $seqLen + $j][2] = $y;
    }
}
echo "";
?>
--EXPECT--
