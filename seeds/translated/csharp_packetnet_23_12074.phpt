--TEST--
Translated seed (inferredbugs-csharp: packetnet/23)
--FILE--
<?php
$fields = [];
$common = pack('vVv', 2142, 0x00000000, 0x0000) . pack('c2', 50, 25) . str_repeat("\0", 16);
$fields[] = pack('vv', 2, 32) . $common;
$proc = pack('V', 0x1111) . "Hester the tester\0" . pack('V', 0x2222) . "Test Group\0";
$fields[] = pack('vv', 4, 50) . $proc;
$fields[] = pack('vvV', 5, 8, 0x3333);
$payload = implode('', $fields);
$hdr = pack('v', 0) . pack('v', 0) . pack('V', 4 + strlen($payload));
$packet = $hdr . $payload;
$unknown = pack('vv', 99, 8) . "\xAA\xBB\xCC\xDD";
$packet .= $unknown;
$len = strlen($packet);
$ver = unpack('v', substr($packet, 0, 2))[1];
$flags = unpack('v', substr($packet, 2, 2))[1];
$tot = unpack('V', substr($packet, 4, 4))[1];
$off = 8;
$types = [];
while ($off + 4 <= $len) {
    $t = unpack('v', substr($packet, $off, 2))[1];
    $l = unpack('v', substr($packet, $off + 2, 2))[1];
    $types[] = $t;
    $off += $l;
}
$hasCommon = in_array(2, $types, true);
$hasMacPhy = in_array(3, $types, true);
$hasUnknown = in_array(99, $types, true);
$packet2 = substr($packet, 0, 8);
$off = 8;
while ($off + 4 <= $len) {
    $t = unpack('v', substr($packet, $off, 2))[1];
    $l = unpack('v', substr($packet, $off + 2, 2))[1];
    if ($t !== 3) $packet2 .= substr($packet, $off, $l);
    $off += $l;
}
$len2 = strlen($packet2);
$off = 8;
$types2 = [];
while ($off + 4 <= $len2) {
    $t = unpack('v', substr($packet2, $off, 2))[1];
    $l = unpack('v', substr($packet2, $off + 2, 2))[1];
    $types2[] = $t;
    $off += $l;
}
echo "";
?>
--EXPECT--
