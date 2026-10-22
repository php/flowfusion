--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/9)
--FILE--
<?php
$files = [];
$bhdTimestamp = "12345678";
$bdtTimestamp = "ABCDEFGH";
$format = 0x74;
$names = ["a", "b\0c", str_repeat("x", 8)];
$datas = [random_bytes(3), random_bytes(16), random_bytes(1)];

$bhd = "BHF3" . str_pad($bhdTimestamp, 8, "\0") . chr($format) . "\0\0\0";
$bhd .= pack("V", count($files = $names)) . pack("V3", 0, 0, 0);

$bdt = "BDF3" . str_pad($bdtTimestamp, 8, "\0") . pack("V", 0);
$offsets = [];
$header = "";
foreach ($names as $i => $name) {
    $offsets[$i] = strlen($bhd) + strlen($header) + 20 + (($format == 0x54 || $format == 0x74) ? 4 : 0);
    $header .= chr(0x40) . "\0\0\0" . pack("V", strlen($datas[$i])) . pack("V", strlen($bdt)) . pack("V", $i) . pack("V", 0);
    if ($format == 0x54 || $format == 0x74) $header .= pack("V", strlen($datas[$i]));
    $bdt .= $datas[$i];
    $bdt .= str_repeat("\0", (16 - (strlen($bdt) & 15)) & 15);
}
$bhd .= $header;
$pos = strlen($bhd);
foreach ($names as $i => $name) {
    $bhd = substr_replace($bhd, pack("V", $pos), strpos($bhd, pack("V", 0), 20 + $i * (24 + (($format == 0x54 || $format == 0x74) ? 4 : 0))) , 4);
    $bhd .= $name . "\0";
    $pos = strlen($bhd);
}
echo strlen($bhd) . "\n";
echo strlen($bdt) . "\n";
?>
--EXPECT--
