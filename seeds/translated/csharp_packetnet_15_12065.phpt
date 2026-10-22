--TEST--
Translated seed (inferredbugs-csharp: packetnet/15)
--FILE--
<?php
$fields = [
    ['type' => 1, 'data' => pack('vCCv', 2437, 2, 0xA0, 0) . pack('c', -84) . pack('c', -100)],
    ['type' => 2, 'data' => pack('v', 0x1111) . "Hester the tester\0" . pack('v', 0x2222) . "Test Group\0"],
    ['type' => 3, 'data' => pack('v', 0x3333)],
];
$packet = pack('CCv', 0, 0, 0);
foreach ($fields as $f) {
    $packet .= pack('vv', $f['type'], 4 + strlen($f['data'])) . $f['data'];
}
$packet .= pack('vv', 99, 8) . "\xAA\xBB\xCC\xDD";
$len = strlen($packet);
$pos = 4;
$seen = [];
while ($pos + 4 <= $len) {
    $t = unpack('v', substr($packet, $pos, 2))[1];
    $l = unpack('v', substr($packet, $pos + 2, 2))[1];
    $seen[$t] = substr($packet, $pos + 4, $l - 4);
    $pos += $l;
}
$re = pack('CCv', 0, 0, 0);
foreach ([1, 2, 3, 99] as $t) {
    $d = $seen[$t];
    $re .= pack('vv', $t, 4 + strlen($d)) . $d;
}
if ($re !== $packet) {
    exit(1);
}
?>
--EXPECT--
