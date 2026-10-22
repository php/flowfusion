--TEST--
Translated seed (inferredbugs-csharp: packetnet/17)
--FILE--
<?php
$packet = pack('VvvVVV', 0x00000000, 0x0000, 0x0000, 2142, 50, 25);
$packet .= pack('Vvv', 0x1111, 0x2222, 0x0011) . "Hester the tester\0Test Group\0";
$packet .= pack('Vv', 0x3333, 0x0000);
$packet .= pack('Vv', 99, 4) . "\xAA\xBB\xCC\xDD";
$packet = substr($packet, 0, 4) . pack('V', strlen($packet)) . substr($packet, 8);

$len = strlen($packet);
$pos = 8;
$fields = [];
while ($pos + 4 <= $len) {
    $type = unpack('v', substr($packet, $pos, 2))[1];
    $flen = unpack('v', substr($packet, $pos + 2, 2))[1];
    $fields[] = [$type, substr($packet, $pos + 4, $flen)];
    $pos += 4 + $flen;
}

$repacked = substr($packet, 0, 8);
foreach ($fields as $f) {
    $repacked .= pack('vv', $f[0], strlen($f[1])) . $f[1];
}
$repacked = substr($repacked, 0, 4) . pack('V', strlen($repacked)) . substr($repacked, 8);

$len2 = strlen($repacked);
$pos = 8;
$seen99 = false;
while ($pos + 4 <= $len2) {
    $type = unpack('v', substr($repacked, $pos, 2))[1];
    $flen = unpack('v', substr($repacked, $pos + 2, 2))[1];
    if ($type === 99) {
        $seen99 = substr($repacked, $pos + 4, $flen) === "\xAA\xBB\xCC\xDD";
    }
    $pos += 4 + $flen;
}

if (!$seen99) {
    throw new Exception('roundtrip failed');
}
?>
--EXPECT--
