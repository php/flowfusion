--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/16)
--FILE--
<?php
$batch = [[1, 2, 3], [4, 5], [6]];
$beamSize = 3;
$maxSteps = 4;
$eos = 0;
$beams = [];
foreach ($batch as $seq) {
    $beams[] = [[$seq, 0.0, false]];
}
for ($step = 0; $step < $maxSteps; $step++) {
    $next = [];
    $allEnd = true;
    foreach ($beams as $b) {
        $cand = [];
        foreach ($b as [$seq, $score, $ended]) {
            if ($ended) {
                $cand[] = [$seq, $score, true];
                continue;
            }
            $allEnd = false;
            $last = end($seq);
            for ($k = 0; $k < $beamSize; $k++) {
                $tok = ($last + $step + $k) % 7;
                if ($step === $maxSteps - 1) $tok = $eos;
                $nseq = $seq;
                $nseq[] = $tok;
                $cand[] = [$nseq, $score + log(($tok + 1) / 8), $tok === $eos];
            }
        }
        usort($cand, fn($a, $b) => $b[1] <=> $a[1]);
        $next[] = array_slice($cand, 0, $beamSize);
    }
    $beams = $next;
    if ($allEnd) break;
}
foreach ($beams as $b) {
    foreach ($b as [$seq, $score, $ended]) {
        if (!$ended) $seq[] = $eos;
        while (count($seq) > 1 && $seq[count($seq) - 1] === $seq[count($seq) - 2]) array_pop($seq);
    }
}
echo "ok\n";
?>
--EXPECT--
