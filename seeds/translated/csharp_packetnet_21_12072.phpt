--TEST--
Translated seed (inferredbugs-csharp: packetnet/21)
--FILE--
<?php
$fields = [
    ['type' => 1, 'data' => pack('v', 2142) . pack('c', 50) . pack('c', 25)],
    ['type' => 2, 'data' => pack('V', 0x1111) . "Hester the tester\0" . pack('V', 0x2222) . "Test Group\0"],
    ['type' => 3, 'data' => pack('V', 0x3333)],
];
$hdr = pack('v', 0) . pack('v', 0);
$body = '';
foreach ($fields as $f) {
    $d = $f['data'];
    $body .= pack('v', $f['type']) . pack('v', 4 + strlen($d)) . $d;
}
$unknown = pack('v', 99) . pack('v', 8) . "\xAA\xBB\xCC\xDD";
$packet = $hdr . $body . $unknown . "PAYLOAD";
$len = strlen($packet);
$ver = unpack('v', substr($packet, 0, 2))[1];
$off = 4;
$types = [];
while ($off + 4 <= $len) {
    $t = unpack('v', substr($packet, $off, 2))[1];
    $l = unpack('v', substr($packet, $off + 2, 2))[1];
    if ($l < 4 || $off + $l > $len) break;
    $types[$t] = substr($packet, $off + 4, $l - 4);
    $off += $l;
}
$unknownBytes = $types[99] ?? '';
$repacked = pack('v', $ver) . pack('v', 0);
foreach ($types as $t => $d) {
    $repacked .= pack('v', $t) . pack('v', 4 + strlen($d)) . $d;
}
$repacked .= "PAYLOAD";
$len2 = strlen($repacked);
$ver2 = unpack('v', substr($repacked, 0, 2))[1];
$off = 4;
$seen1 = $seen2 = $seen99 = false;
while ($off + 4 <= $len2) {
    $t = unpack('v', substr($repacked, $off, 2))[1];
    $l = unpack('v', substr($repacked, $off + 2, 2))[1];
    if ($l < 4 || $off + $l > $len2) break;
    $seen1 |= $t === 1;
    $seen2 |= $t === 2;
    $seen99 |= $t === 99;
    $off += $l;
}
if ($ver !== 0 || $ver2 !== 0 || !$seen1 || !$seen2 || !$seen99 || $unknownBytes !== "\xAA\xBB\xCC\xDD") {
    exit(1);
}
$aligned = pack('v', 0) . pack('v', 0) . pack('v', 1) . pack('v', 12) . pack('v', 2142) . pack('c', 50) . pack('c', 25) . pack('v', 2) . pack('v', 50) . pack('V', 0x1111) . "Hester the tester\0" . pack('V', 0x2222) . "Test Group\0" . pack('v', 3) . pack('v', 8) . pack('V', 0x3333);
if (strlen($aligned) !== 92) exit(1);
echo "";
?>
--EXPECT--
