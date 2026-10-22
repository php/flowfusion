--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/13)
--FILE--
<?php
$batchSize = 3;
$seqLen = 4;
$beamSearchSize = 2;
$eos = 0;
$probs = [];
for ($b = 0; $b < $batchSize; $b++) {
    for ($t = 0; $t < $seqLen; $t++) {
        $row = [];
        for ($v = 0; $v < 5; $v++) $row[] = ($v + 1) / 15.0;
        $probs[] = $row;
    }
}
for ($step = 0; $step < $beamSearchSize; $step++) {
    $targetIdx = [];
    $gather = [];
    for ($i = 0; $i < count($probs); $i++) {
        $mx = 0;
        $mv = $probs[$i][0];
        for ($v = 1; $v < count($probs[$i]); $v++) {
            if ($probs[$i][$v] > $mv) {
                $mv = $probs[$i][$v];
                $mx = $v;
            }
        }
        $targetIdx[] = $mx;
        $gather[] = $mv;
    }
    for ($i = 0; $i < count($targetIdx); $i++) {
        $probs[$i][$targetIdx[$i]] = 0.0;
    }
    $batch2beam2seq = [];
    for ($b = 0; $b < $batchSize; $b++) $batch2beam2seq[$b] = [];
    for ($k = 0; $k < $beamSearchSize; $k++) {
        for ($b = 0; $b < $batchSize; $b++) {
            $batch2beam2seq[$b][] = [$targetIdx[$b * $seqLen + $seqLen - 1], -log(max($gather[$b * $seqLen + $seqLen - 1], 1e-12))];
        }
    }
    $allEnd = true;
    for ($b = 0; $b < $batchSize; $b++) {
        for ($k = 0; $k < $beamSearchSize; $k++) {
            if ($batch2beam2seq[$b][$k][0] !== $eos) $allEnd = false;
        }
    }
    if ($allEnd) break;
}
echo "ok\n";
?>
--EXPECT--
