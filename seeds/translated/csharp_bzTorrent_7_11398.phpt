--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/7)
--FILE--
<?php
$h = str_repeat("\0", 20);
$p = str_repeat("\0", 20);
$b = chr(19) . "BitTorrent protocol" . str_repeat("\0", 8) . $h . $p;
$buf = $b . pack("N", 0) . pack("N", 5) . chr(4) . pack("N", 123456789);
$pos = 0;
$len = strlen($buf);
$handshake = false;
while ($pos < $len) {
    if (!$handshake) {
        if ($len - $pos < 68) break;
        $pstrlen = ord($buf[$pos]);
        if ($pstrlen !== 19) {
            if ($pstrlen === 0) break;
        }
        $reserved = substr($buf, $pos + 20, 8);
        $remoteHash = substr($buf, $pos + 28, 20);
        $remoteId = substr($buf, $pos + 48, 20);
        $pos += 68;
        $handshake = true;
        continue;
    }
    if ($len - $pos < 4) break;
    $cmdLen = unpack("N", substr($buf, $pos, 4))[1];
    $pos += 4;
    if ($cmdLen === 0) continue;
    if ($len - $pos < $cmdLen) break;
    $cmdId = ord($buf[$pos]);
    $pos += 1;
    if ($cmdId === 4 && $cmdLen >= 5) {
        $pieceIndex = unpack("N", substr($buf, $pos, 4))[1];
        $pos += 4;
    } else {
        $pos += $cmdLen - 1;
    }
}
echo "";
?>
--EXPECT--
