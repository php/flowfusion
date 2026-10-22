--TEST--
Translated seed (inferredbugs-csharp: packetnet/22)
--FILE--
<?php
$h = pack('VvvV', 0, 0, 0, 0);
$f1 = pack('vv', 2, 20) . pack('V', 0) . pack('v', 2437) . pack('v', 0x00A0) . pack('c', -84) . pack('c', -100) . pack('v', 0) . pack('v', 0);
$f2 = pack('vv', 3, 48) . pack('V', 0x1111) . "Hester the tester\0" . pack('V', 0x2222) . "Test Group\0";
$f3 = pack('vvV', 4, 4, 0x3333);
$payload = "\x00\x00\x00\x00";
$packet = $h . $f1 . $f2 . $f3 . $payload;
$len = strlen($packet);
$packet = substr_replace($packet, pack('V', $len), 4, 4);
$u = pack('vv', 99, 8) . "\xAA\xBB\xCC\xDD";
$packet .= $u;
$len = strlen($packet);
$packet = substr_replace($packet, pack('V', $len), 4, 4);
$packet = substr_replace($packet, pack('V', 0), 0, 4);
$packet = substr_replace($packet, pack('V', 0), 8, 4);
$packet;
?>
--EXPECT--
