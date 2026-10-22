--TEST--
Translated seed (inferredbugs-csharp: ZstdNet/2)
--FILE--
<?php
mt_srand(1234);

$small = 1024;
$large = 1024 * 1024;

$seq = '';
for ($i = 0; $i < $large; $i++) $seq .= chr($i & 255);

$rand = '';
for ($i = 0; $i < $small; $i++) $rand .= chr(mt_rand(0, 255));

$normal = gzcompress($rand, 9);
$dict = substr($rand . $rand . $rand . $rand, 0, 32768);
$withDict = gzdeflate($rand, 9);
$recompressed = gzcompress($rand . $dict, 9);

$compressedSeq = gzcompress($seq, 9);
$round1 = gzuncompress($compressedSeq);
$compressedAgain = gzcompress($round1, 9);
$round2 = gzuncompress($compressedAgain);

if (strlen($compressedSeq) >= strlen($seq)) exit(1);
if (strlen($recompressed) > strlen($normal) + strlen($dict)) exit(1);
if ($round1 !== $seq) exit(1);
if ($round2 !== $seq) exit(1);
if (strlen($normal) === 0 || strlen($withDict) === 0) exit(1);
?>
--EXPECT--
