--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/8)
--FILE--
<?php
$h = str_repeat("A", 20);
$p = str_repeat("B", 20);
$h2 = pack("C", 19) . "BitTorrent protocol" . str_repeat("\0", 8) . $h . $p;
$bitfield = pack("N", 2) . chr(5) . chr(0x80);
$have = pack("N", 5) . chr(4) . pack("N", 123456789);
$request = pack("N", 13) . chr(6) . pack("N3", 7, 8, 9);
$piece = pack("N", 17) . chr(7) . pack("N2", 1, 2) . "abcdefgh";
$keepalive = pack("N", 0);
$buf = $h2 . $bitfield . $have . $request . $piece . $keepalive;

$pos = 0;
$handshake = false;
$peerBitField = null;

while ($pos < strlen($buf)) {
    if (!$handshake) {
        if (strlen($buf) - $pos < 68) break;
        $pstrlen = ord($buf[$pos]);
        if ($pstrlen !== 19) break;
        $remoteHash = substr($buf, $pos + 28, 20);
        $remoteId = substr($buf, $pos + 48, 20);
        $handshake = true;
        $pos += 68;
        continue;
    }

    if (strlen($buf) - $pos < 4) break;
    $len = unpack("N", substr($buf, $pos, 4))[1];
    $pos += 4;
    if ($len === 0) continue;
    if (strlen($buf) - $pos < $len) break;
    $id = ord($buf[$pos]);
    $payload = substr($buf, $pos + 1, $len - 1);
    $pos += $len;

    if ($id === 5) {
        $peerBitField = [];
        for ($i = 0; $i < strlen($payload); $i++) {
            $b = ord($payload[$i]);
            for ($j = 0; $j < 8; $j++) $peerBitField[$i * 8 + $j] = (bool)(($b >> $j) & 1);
        }
    } elseif ($id === 4) {
        $pieceIndex = unpack("N", $payload)[1];
    } elseif ($id === 6 || $id === 8) {
        $vals = unpack("Nindex/Nbegin/Nlength", $payload);
    } elseif ($id === 7) {
        $vals = unpack("Nindex/Nbegin", substr($payload, 0, 8));
        $data = substr($payload, 8);
    }
}
?>
--EXPECT--
