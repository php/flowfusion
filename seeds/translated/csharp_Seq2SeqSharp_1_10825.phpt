--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/1)
--FILE--
<?php
$ra = [0, 1, 2];
$idx = 0;
for ($k = 0; $k < 10; $k++) {
    $item = $ra[$idx];
    $idx = ($idx + 1) % count($ra);
}

$eos = "<eos>";
$sents = [
    ["a", "b", $eos, "x", $eos],
    ["c", $eos, "d"],
    ["e", "f"]
];

foreach ($sents as &$snt) {
    for ($i = 0; $i < count($snt); $i++) {
        if ($snt[$i] === $eos) {
            $snt = array_slice($snt, 0, $i + 1);
            break;
        }
    }
}
unset($snt);

$batch = [["s1", "s2"], ["t1", "t2"]];
$seqLen = count($batch[0]);
$out = [];
for ($j = 0; $j < count($batch); $j++) {
    for ($i = 0; $i < $seqLen; $i++) {
        $out[] = $batch[$j][$i];
    }
}

$pos = [];
$row = 6;
$col = 8;
for ($p = 0; $p < $row; $p++) {
    for ($i = 0; $i < $col; $i += 2) {
        $angle = $p / pow(10000, 2 * (($i / 2) / $col));
        $pos[$p][$i] = sin($angle);
        $pos[$p][$i + 1] = cos($angle);
    }
}

$tokens = ["<s>", "hello", $eos];
$generated = [];
$end = [];
for ($step = 1; $step < 5; $step++) {
    $next = $tokens[$step % count($tokens)];
    $generated[] = $next;
    if ($next === $eos) {
        $end[] = true;
        break;
    }
}

echo "";
?>
--EXPECT--
