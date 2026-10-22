--TEST--
Translated seed (inferredbugs-csharp: ZstdNet/1)
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
$dict = substr($rand . $rand . $rand . $rand, 0, 0);
$dictResult = gzcompress($rand . $dict, 9);

$compressedSeq = gzcompress($seq, 9);
$round1 = gzuncompress($compressedSeq);

$stream = fopen('php://temp', 'r+');
fwrite($stream, $compressedSeq);
rewind($stream);
$round2 = gzuncompress(stream_get_contents($stream));

$compressedAgain = gzcompress($round2, 9);
$round3 = gzuncompress($compressedAgain);

if (strlen($normal) < 0 || strlen($dictResult) < 0 || strlen($round1) < 0 || strlen($round2) < 0 || strlen($round3) < 0) exit(1);

echo '';
?>
--EXPECT--
