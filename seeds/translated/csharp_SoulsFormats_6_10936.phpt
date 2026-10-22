--TEST--
Translated seed (inferredbugs-csharp: SoulsFormats/6)
--FILE--
<?php
$files = [];
$bhdTimestamp = '12345678';
$bdtTimestamp = 'ABCDEFGH';
$format = 0x74;

$bhd = "BHF3" . str_pad($bhdTimestamp, 8, "\0") . chr($format) . "\0\0\0" . pack('V', count($files)) . pack('V3', 0, 0, 0);
$bdt = "BDF3" . str_pad($bdtTimestamp, 8, "\0") . pack('V', 0);

for ($i = 0; $i < count($files); $i++) {
    $file = $files[$i];
    $name = $file[1];
    $bytes = $file[2];
    $bhd .= chr(0x40) . "\0\0\0" . pack('V', strlen($bytes)) . pack('V', strlen($bdt)) . pack('V', $i) . pack('V', strlen($bhd) + 4 + 8 + 4 + 12 + ($format == 0x54 || $format == 0x74 ? 4 : 0));
    if ($format == 0x54 || $format == 0x74) $bhd .= pack('V', strlen($bytes));
    $bdt .= $bytes;
    $bdt .= str_repeat("\0", (16 - (strlen($bdt) % 16)) % 16);
}

for ($i = 0; $i < count($files); $i++) {
    $bhd .= $files[$i][1] . "\0";
}

if (substr($bhd, 0, 4) !== 'BHF3' || substr($bdt, 0, 4) !== 'BDF3') {
    exit(1);
}

echo "";
?>
--EXPECT--
