--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/10)
--FILE--
<?php
$protocolHeader = "BitTorrent protocol";
$peerId = str_pad("-PH0001-", 20, "X");
$infoHash = str_repeat("ab", 20);
$reserved = str_repeat("\0", 8);

$handshake = chr(strlen($protocolHeader)) . $protocolHeader . $reserved . hex2bin($infoHash) . $peerId;
$len = ord($handshake[0]);
$protocolStr = substr($handshake, 1, $len);
$reservedBytes = substr($handshake, 1 + $len, 8);
$infoHashBytes = substr($handshake, 1 + $len + 8, 20);
$peerIdBytes = substr($handshake, 1 + $len + 28, 20);

if ($protocolStr === "BitTorrent protocol" && strlen($reservedBytes) === 8 && strlen($infoHashBytes) === 20 && strlen($peerIdBytes) === 20) {
    $packet = pack('N', 1) . "A";
    $buf = $packet . $packet;
    $queue = [];
    while ($buf !== '') {
        if (strlen($buf) < 4) break;
        $n = unpack('N', substr($buf, 0, 4))[1];
        if (strlen($buf) < 4 + $n) break;
        $queue[] = substr($buf, 4, $n);
        $buf = substr($buf, 4 + $n);
    }
}

?>
--EXPECT--
