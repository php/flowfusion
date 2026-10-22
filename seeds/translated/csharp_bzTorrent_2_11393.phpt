--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/2)
--FILE--
<?php
$h = str_repeat("\x00", 20);
$p = str_repeat("\x01", 20);
$hdr = "BitTorrent protocol";
$handshake = chr(19) . $hdr . str_repeat("\x00", 8) . $h . $p;

$bitfield = 0;
for ($i = 0; $i < 40; $i++) {
    $bitfield = ($bitfield << 1) | ($i & 1);
}
$bitfield = substr(pack('N', $bitfield), -5);

$piece = pack('N', 7) . chr(7) . pack('N', 1) . pack('N', 2) . "abc";
$keepalive = pack('N', 0);
$msg = $handshake . $keepalive . pack('N', 6) . chr(5) . $bitfield . $piece;

$buf = '';
$pos = 0;
while ($pos < strlen($msg)) {
    $n = ($pos % 3) + 1;
    $buf .= substr($msg, $pos, $n);
    $pos += $n;
    if (strlen($buf) >= 68) {
        $len = ord($buf[0]);
        if ($len === 19 && strlen($buf) >= 68) {
            $buf = substr($buf, 68);
        }
    }
    while (strlen($buf) >= 4) {
        $len = unpack('N', substr($buf, 0, 4))[1];
        if (strlen($buf) < 4 + $len) break;
        $payload = substr($buf, 4, $len);
        $buf = substr($buf, 4 + $len);
        if ($len === 0) continue;
        $id = ord($payload[0]);
        $body = substr($payload, 1);
        if ($id === 5) {
            $bits = [];
            for ($i = 0; $i < strlen($body); $i++) {
                $b = ord($body[$i]);
                for ($j = 7; $j >= 0; $j--) $bits[] = (bool)(($b >> $j) & 1);
            }
        } elseif ($id === 7) {
            $index = unpack('N', substr($body, 0, 4))[1];
            $begin = unpack('N', substr($body, 4, 4))[1];
            $data = substr($body, 8);
        }
    }
}
echo "ok";
?>
--EXPECT--
