--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/12)
--FILE--
<?php
$src = [[1, 2, 3], [4, 5]];
$tgt = [[6, 7, 8], [9, 10]];
$eos = 0;

foreach ($src as &$s) {
    $s[] = $eos;
}
unset($s);

$batchSize = count($src);
$seqLen = count($src[0]);
$idxs = [];
$segIdxs = [];

for ($i = 0; $i < $batchSize; $i++) {
    $seg = 0;
    for ($j = 0; $j < $seqLen; $j++) {
        $idxs[] = $src[$i][$j];
        $segIdxs[] = $seg;
        if ($src[$i][$j] === 2) {
            $seg++;
        }
    }
}

$emb = array_fill(0, 16, array_fill(0, 4, 0.1));
$segEmb = array_fill(0, 2, array_fill(0, 4, 0.2));
$out = [];

for ($n = 0; $n < count($idxs); $n++) {
    $row = $idxs[$n] % 16;
    $seg = $segIdxs[$n] % 2;
    $out[] = $emb[$row][0] + $segEmb[$seg][0];
}

$map = ["a" => 1, "b" => 2, "c" => 3];
$keys = array_keys($map);
$vals = array_values($map);
$flip = array_flip($map);
$probe = $flip[2] ?? null;

$beam = [[["x", "y", "z"], ["x", "y", "0"]], [["x", "y", "1"], ["x", "y", "2"]]];
$top = [];
for ($k = 0; $k < count($beam[0]); $k++) {
    $top[] = $beam[0][$k];
}

echo count($out) . "\n";
echo ($probe === "b" ? "ok" : "bad") . "\n";
echo count($top) . "\n";
?>
--EXPECT--
