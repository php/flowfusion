--TEST--
Translated seed (inferredbugs-csharp: ReliableNetcode.NET/1)
--FILE--
<?php
function seq_gt(int $s1, int $s2): bool {
    return (($s1 > $s2) && ($s1 - $s2 <= 32768)) || (($s1 < $s2) && ($s2 - $s1 > 32768));
}

function write_packet_header(string &$buf, int $channelID, int $sequence, int $ack, int $ackBits): int {
    $prefix = 0;
    if (($ackBits & 0x000000FF) !== 0x000000FF) $prefix |= 1 << 1;
    if (($ackBits & 0x0000FF00) !== 0x0000FF00) $prefix |= 1 << 2;
    if (($ackBits & 0x00FF0000) !== 0x00FF0000) $prefix |= 1 << 3;
    if (($ackBits & 0xFF000000) !== 0xFF000000) $prefix |= 1 << 4;
    $diff = $sequence - $ack;
    if ($diff < 0) $diff += 65536;
    if ($diff <= 255) $prefix |= 1 << 5;
    $prefix |= ($channelID & 3) << 6;
    $buf .= chr($prefix) . pack('v', $sequence);
    $buf .= ($diff <= 255) ? chr($diff) : pack('v', $ack);
    if (($ackBits & 0x000000FF) !== 0x000000FF) $buf .= chr($ackBits & 0xFF);
    if (($ackBits & 0x0000FF00) !== 0x0000FF00) $buf .= chr(($ackBits >> 8) & 0xFF);
    if (($ackBits & 0x00FF0000) !== 0x00FF0000) $buf .= chr(($ackBits >> 16) & 0xFF);
    if (($ackBits & 0xFF000000) !== 0xFF000000) $buf .= chr(($ackBits >> 24) & 0xFF);
    return strlen($buf);
}

$buf = '';
write_packet_header($buf, 2, 65530, 5, 0xFF00FF00);
write_packet_header($buf, 1, 10, 65530, 0xFFFFFFFF);
seq_gt(65530, 5);
seq_gt(10, 65530);
strlen($buf);
?>
--EXPECT--
