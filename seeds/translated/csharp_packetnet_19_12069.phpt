--TEST--
Translated seed (inferredbugs-csharp: packetnet/19)
--FILE--
<?php
$fields = [];
$fields[] = pack('VvvV', 2142, 50, 25, 0);
$u = "Hester the tester";
$g = "Test Group";
$fields[] = pack('Vvv', 0x1111, 0x2222, strlen($u)) . $u . pack('v', strlen($g)) . $g;
$fields[] = pack('V', 0x3333);
$packet = pack('V', 0) . implode('', $fields);
$packet .= pack('V', 99) . pack('V', 4) . "\xAA\xBB\xCC\xDD";
$len = strlen($packet);
$packet = substr($packet, 0, $len);
$ver = unpack('V', substr($packet, 0, 4))[1];
$off = 4;
$items = [];
while ($off < strlen($packet)) {
    $t = unpack('V', substr($packet, $off, 4))[1];
    $l = unpack('V', substr($packet, $off + 4, 4))[1];
    $d = substr($packet, $off + 8, $l);
    $items[] = [$t, $d];
    $off += 8 + $l;
}
$hasCommon = $hasMac = $hasUnknown = false;
foreach ($items as [$t, $d]) {
    if ($t === 0) $hasCommon = true;
    if ($t === 1) $hasMac = true;
    if ($t === 99 && $d === "\xAA\xBB\xCC\xDD") $hasUnknown = true;
}
$packet2 = pack('V', 0);
$packet2 .= pack('VvV', 0, 2142, 50) . pack('v', 25);
$packet2 .= pack('Vv', 1, strlen($u)) . $u . pack('v', strlen($g)) . $g;
$packet2 .= pack('V', 2) . pack('V', 0x3333);
$packet2 .= pack('V', 99) . pack('V', 4) . "\xAA\xBB\xCC\xDD";
$packet2 = substr($packet2, 0, strlen($packet2));
$packet3 = pack('V', 0);
$packet4 = $packet2;
$packet5 = $packet2;
$packet6 = $packet2;
$packet7 = $packet2;
$packet8 = $packet2;
$packet9 = $packet2;
$packet10 = $packet2;
echo "";
?>
--EXPECT--
