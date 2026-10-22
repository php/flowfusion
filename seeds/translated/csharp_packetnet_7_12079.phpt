--TEST--
Translated seed (inferredbugs-csharp: packetnet/7)
--FILE--
<?php
$bytes = pack('C*',
    0x48, 0x01, 0x3A, 0x01, 0x11, 0x11, 0x11, 0x11, 0x11, 0x11,
    0x22, 0x22, 0x22, 0x22, 0x22, 0x22, 0x33, 0x33, 0x33, 0x33,
    0x33, 0x33, 0x89, 0x00, 0x01, 0x02, 0x03, 0x04, 0x05
);
$u = unpack('vfc/vdur/C6dst/C6src/C6bss/vseq/Vfcs/C*payload', $bytes);
$fc = $u['fc'];
$dur = $u['dur'];
$seq = $u['seq'];
$dst = strtoupper(implode('', array_map(fn($b) => sprintf('%02X', $b), array_slice($u, 3, 6))));
$src = strtoupper(implode('', array_map(fn($b) => sprintf('%02X', $b), array_slice($u, 9, 6))));
$bss = strtoupper(implode('', array_map(fn($b) => sprintf('%02X', $b), array_slice($u, 15, 6))));
$payload = array_slice($u, 25);
$repacked = pack('v', $fc) . pack('v', $dur) . pack('C6', ...array_slice($u, 3, 6)) . pack('C6', ...array_slice($u, 9, 6)) . pack('C6', ...array_slice($u, 15, 6)) . pack('v', $seq) . pack('C*', ...$payload);
$corrupt = substr("\x01", 0, 1);
unpack('C', $corrupt);
echo $dst, $src, $bss, strlen($bytes), strlen($repacked), "\n";
?>
--EXPECT--
