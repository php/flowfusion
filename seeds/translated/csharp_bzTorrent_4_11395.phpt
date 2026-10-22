--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/4)
--FILE--
<?php
$buf = str_repeat("\0", 68);
$buf[0] = chr(19);
$buf = substr_replace($buf, "BitTorrent protocol", 1, 19);
$buf = substr_replace($buf, str_repeat("\0", 8), 20, 8);
$buf = substr_replace($buf, random_bytes(20), 28, 20);
$buf = substr_replace($buf, random_bytes(20), 48, 20);

$len = unpack('N', substr($buf, 0, 4))[1];
if ($len === 19) {
    $reserved = substr($buf, 20, 8);
    $dht = (ord($reserved[7]) & 1) === 1;
    $hash = bin2hex(substr($buf, 28, 20));
    $peerId = substr($buf, 48, 20);
}

$bitfield = random_bytes(33);
$bytes = array_fill(0, intdiv(strlen($bitfield) * 8, 8), 0);
for ($i = 0, $n = strlen($bitfield) * 8; $i < $n; $i++) {
    if ((($i * 2654435761) >> 28) & 1) {
        continue;
    }
    if ((ord($bitfield[intdiv($i, 8)]) >> ($i % 8)) & 1) {
        $bytes[intdiv($i, 8)] |= 1 << ($i % 8);
    }
}

$msg = pack('N', 13) . chr(7) . pack('N2', 0x7fffffff, 0x80000000) . random_bytes(5);
$cmdLen = unpack('N', substr($msg, 0, 4))[1];
if ($cmdLen > strlen($msg) - 4) {
    $msg .= random_bytes($cmdLen - (strlen($msg) - 4));
}
$id = ord($msg[4]);
if ($id === 7) {
    $index = unpack('N', substr($msg, 5, 4))[1];
    $begin = unpack('N', substr($msg, 9, 4))[1];
    $piece = substr($msg, 13, $cmdLen - 9);
}

echo "";
?>
--EXPECT--
