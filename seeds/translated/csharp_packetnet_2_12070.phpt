--TEST--
Translated seed (inferredbugs-csharp: packetnet/2)
--FILE--
<?php
$fc = 0;
$fc |= 0;
$fc |= 2 << 2;
$fc |= 1 << 8;
$fc |= 1 << 9;
$fc |= 1 << 10;
$fc |= 0x1234 << 16;

$bytes = pack('V', $fc) . pack('v', 0x1234) . hex2bin('111111111111222222222222333333333333') . pack('v', (0x89 << 4) | 0x1) . pack('V', 0x01020304) . "\x01\x02\x03\x04\x05";

$u = unpack('Vfc/vdur/a6da/a6sa6bss/vseq/Vfcs', $bytes);
$fc2 = $u['fc'];

$protocolVersion = $fc2 & 0x3;
$type = ($fc2 >> 2) & 0x3;
$toDS = (bool)(($fc2 >> 8) & 1);
$fromDS = (bool)(($fc2 >> 9) & 1);
$moreFragments = (bool)(($fc2 >> 10) & 1);
$retry = (bool)(($fc2 >> 11) & 1);
$powerManagement = (bool)(($fc2 >> 12) & 1);
$moreData = (bool)(($fc2 >> 13) & 1);
$wep = (bool)(($fc2 >> 14) & 1);
$order = (bool)(($fc2 >> 15) & 1);
$duration = $u['dur'];
$dest = strtoupper(bin2hex($u['da']));
$src = strtoupper(bin2hex($u['sa']));
$bss = strtoupper(bin2hex($u['bss']));
$frag = $u['seq'] & 0xF;
$seq = $u['seq'] >> 4;
$fcs = $u['fcs'];
$frameSize = strlen($bytes) - 4;

$fc3 = 0;
$fc3 |= 2 << 2;
$fc3 |= 1 << 9;
$fc3 |= 1 << 10;
$bytes2 = pack('V', $fc3) . pack('v', 0x1234) . hex2bin('111111111111222222222222333333333333') . pack('v', (0x89 << 4) | 0x1) . pack('V', 0x01020304) . "\x01\x02\x03\x04\x05";
$u2 = unpack('Vfc/vdur/a6da/a6sa6bss/vseq/Vfcs', $bytes2);

$protocolVersion2 = $u2['fc'] & 0x3;
$type2 = ($u2['fc'] >> 2) & 0x3;
$toDS2 = (bool)(($u2['fc'] >> 8) & 1);
$fromDS2 = (bool)(($u2['fc'] >> 9) & 1);
$moreFragments2 = (bool)(($u2['fc'] >> 10) & 1);
$frag2 = $u2['seq'] & 0xF;
$seq2 = $u2['seq'] >> 4;
$dest2 = strtoupper(bin2hex($u2['da']));
$src2 = strtoupper(bin2hex($u2['sa']));
$bss2 = strtoupper(bin2hex($u2['bss']));
$fcs2 = $u2['fcs'];
?>
--EXPECT--
