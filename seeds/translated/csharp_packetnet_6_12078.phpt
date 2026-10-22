--TEST--
Translated seed (inferredbugs-csharp: packetnet/6)
--FILE--
<?php
$bytes = chr(0x01) . chr(0x88) . chr(0x00) . chr(0x00) . chr(0x78) . chr(0xAC) . chr(0xC0) . chr(0x0A) . chr(0x5E) . chr(0x0E) . chr(0x00) . chr(0x22) . chr(0x3F) . chr(0xC1) . chr(0xF3) . chr(0x78) . chr(0x00) . chr(0x22) . chr(0x3F) . chr(0xC1) . chr(0xF3) . chr(0x78) . chr(0xB4) . chr(0x09) . chr(0x00) . chr(0x00) . chr(0xF2) . chr(0xB1) . chr(0xDB);
$u = unpack('vfc/vdur/C6da/C6sa/C6bss/vseq/vqos/Vfcs', $bytes);
$seq = $u['seq'];
$fcs = $u['fcs'];
$da = strtoupper(implode('', array_map(fn($x) => sprintf('%02X', $x), array_slice($u, 3, 6))));
$sa = strtoupper(implode('', array_map(fn($x) => sprintf('%02X', $x), array_slice($u, 9, 6))));
$bss = strtoupper(implode('', array_map(fn($x) => sprintf('%02X', $x), array_slice($u, 15, 6))));
$corrupt = unpack('C', "\x01");
echo $seq . "\n" . $fcs . "\n" . $da . "\n" . $sa . "\n" . $bss . "\n" . ($corrupt[1] ?? 0) . "\n";
?>
--EXPECT--
