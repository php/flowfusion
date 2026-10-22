--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/5)
--FILE--
<?php
$h = str_repeat("\x11", 20);
$p = str_repeat("-PC0001-", 2) . "--";
$buf = chr(19) . "BitTorrent protocol" . str_repeat("\0", 8) . $h . $p;
$buf .= pack("N", 13) . chr(7) . pack("N", 1) . pack("N", 2) . "abcd";
$buf .= pack("N", 0);
$buf .= pack("N", 5) . chr(4) . pack("N", 1234);
$buf .= pack("N", 9) . chr(5) . "\x80";
$buf .= pack("N", 13) . chr(8) . pack("N", 1) . pack("N", 2) . pack("N", 3);

$pos = 0;
$handshake = false;
while ($pos + 4 <= strlen($buf)) {
    if (!$handshake) {
        if (strlen($buf) - $pos < 68) break;
        $len = ord($buf[$pos]);
        if ($len !== 19) {
            if ($len === 0) break;
        }
        $handshake = true;
        $pos += 68;
        continue;
    }
    $n = unpack("N", substr($buf, $pos, 4))[1];
    if ($pos + 4 + $n > strlen($buf)) break;
    $pos += 4;
    if ($n === 0) continue;
    $id = ord($buf[$pos]);
    $pos++;
    $payload = substr($buf, $pos, $n - 1);
    if ($id === 4 && strlen($payload) >= 4) {
        $piece = unpack("N", substr($payload, 0, 4))[1];
    } elseif ($id === 5) {
        $bits = [];
        for ($i = 0; $i < strlen($payload); $i++) {
            $b = ord($payload[$i]);
            for ($j = 0; $j < 8; $j++) $bits[] = (bool)(($b >> $j) & 1);
        }
    } elseif ($id === 6 || $id === 8) {
        if (strlen($payload) >= 12) {
            $a = unpack("N3", $payload);
        }
    } elseif ($id === 7 && strlen($payload) >= 8) {
        $a = unpack("N2", substr($payload, 0, 8));
        $pieceData = substr($payload, 8);
    }
    $pos += $n - 1;
}
echo "";
?>
--EXPECT--
