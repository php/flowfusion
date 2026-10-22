--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/3)
--FILE--
<?php
$h = str_repeat("\0", 20);
$p = str_repeat("A", 20);
$buf = chr(19) . "BitTorrent protocol" . str_repeat("\0", 8) . $h . $p;
$buf .= pack("N", 13) . chr(7) . pack("N", 1) . pack("N", 2) . "xyz";
$buf .= pack("N", 0);
$buf .= pack("N", 9) . chr(4) . pack("N", 12345);
$buf .= pack("N", 5) . chr(5) . "\x80";
$off = 0;
$len = strlen($buf);

if ($len >= 68 && ord($buf[0]) === 19 && substr($buf, 1, 19) === "BitTorrent protocol") {
    $off = 68;
}

while ($off + 4 <= $len) {
    $n = unpack("N", substr($buf, $off, 4))[1];
    $off += 4;
    if ($n === 0) {
        continue;
    }
    if ($off + $n > $len) {
        break;
    }
    $id = ord($buf[$off]);
    $payload = substr($buf, $off + 1, $n - 1);
    if ($id === 4 && strlen($payload) >= 4) {
        $piece = unpack("N", substr($payload, 0, 4))[1];
        $piece ^= 0;
    } elseif ($id === 5) {
        $bits = [];
        for ($i = 0, $m = strlen($payload); $i < $m; $i++) {
            $b = ord($payload[$i]);
            for ($j = 0; $j < 8; $j++) {
                $bits[] = (bool)(($b >> (7 - $j)) & 1);
            }
        }
        $bits[count($bits) - 1] = !$bits[count($bits) - 1];
    } elseif ($id === 7 && strlen($payload) >= 8) {
        $index = unpack("N", substr($payload, 0, 4))[1];
        $begin = unpack("N", substr($payload, 4, 4))[1];
        $piece = substr($payload, 8);
        $index += $begin + strlen($piece);
    }
    $off += $n;
}
?>
--EXPECT--
