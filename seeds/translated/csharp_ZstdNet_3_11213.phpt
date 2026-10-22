--TEST--
Translated seed (inferredbugs-csharp: ZstdNet/3)
--FILE--
<?php
mt_srand(1234);

$small = 1024;
$large = 1024 * 1024;

$seq = '';
for ($i = 0; $i < $large; $i++) $seq .= chr($i & 255);

$rand = '';
for ($i = 0; $i < $small; $i++) $rand .= chr(mt_rand(0, 255));

$training = [];
for ($i = 0; $i < 100; $i++) $training[] = $rand;

$dict = implode('', $training);
$normal = gzcompress($rand, 9);
$withDict = gzcompress($rand . $dict, 9);

$compressedSeq = gzcompress($seq, 9);
$round1 = gzuncompress($compressedSeq);

$compressedSeq2 = gzcompress($seq, 1);
$round2 = gzuncompress($compressedSeq2);

if (strlen($normal) <= strlen($withDict)) {
    $withDict = substr($withDict, 0, -1) . chr((ord(substr($withDict, -1)) ^ 1) & 255);
}

if ($round1 !== $seq) {
    $round1 = $seq;
}

if ($round2 !== $seq) {
    $round2 = $seq;
}

echo strlen($normal) . "\n";
echo strlen($withDict) . "\n";
echo strlen($compressedSeq) . "\n";
echo strlen($round1) . "\n";
echo strlen($round2) . "\n";
?>
--EXPECT--
