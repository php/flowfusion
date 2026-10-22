--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/8)
--FILE--
<?php
$files = [];
$bhdTimestamp = str_repeat("123456789", 1);
$bdtTimestamp = str_repeat("abcdefghi", 1);
$format = 0x74;

$bhd = "BHF3" . str_pad($bhdTimestamp, 8, "\0") . chr($format) . "\0\0\0" . pack("V", count($files)) . pack("V3", 0, 0, 0);
$bdt = "BDF3" . str_pad($bdtTimestamp, 8, "\0") . pack("V", 0);

for ($i = 0; $i < count($files); $i++) {
    $file = $files[$i];
    $bhd .= chr(0x40) . "\0\0\0" . pack("V", strlen($file[2])) . pack("V", strlen($bdt)) . pack("V", $i) . pack("V", strlen($bhd) + 4 + 16);
    if ($format === 0x54 || $format === 0x74) $bhd .= pack("V", strlen($file[2]));
    $bdt .= $file[2];
    $bdt .= str_repeat("\0", (16 - (strlen($bdt) & 15)) & 15);
}

for ($i = 0; $i < count($files); $i++) {
    $file = $files[$i];
    $bhd .= $file[1] . "\0";
}

if (substr($bhd, 0, 4) === "BHF3" && substr($bdt, 0, 4) === "BDF3") {
    $x = substr($bhd, 4, 8);
    $y = substr($bdt, 4, 8);
}

echo "";
?>
--EXPECT--
