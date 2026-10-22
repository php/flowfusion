--TEST--
Translated seed (inferredbugs-csharp: bzTorrent/11)
--FILE--
<?php
$socketBufferSize = 16 * 1024;
$socketBuffer = str_repeat("\0", $socketBufferSize);
$receiving = false;
$currentPacketBuffer = '';
$receiveQueue = [];
$sendQueue = [];
$incomingHandshake = null;

$protocolHeader = "BitTorrent protocol";
$infoHash = str_repeat("ab", 20);
$peerId = str_pad("-PH0001-", 20, "x");
$handshake = chr(strlen($protocolHeader)) . $protocolHeader . str_repeat("\0", 8) . hex2bin($infoHash) . $peerId;

$socketBuffer = substr($handshake, 0, $socketBufferSize);
$dataLength = strlen($socketBuffer);

if ($incomingHandshake === null) {
    if ($dataLength === 0) {
        $receiving = false;
    } else {
        $protocolStrLen = ord($socketBuffer[0]);
        $protocolStr = substr($socketBuffer, 1, $protocolStrLen);
        $reservedBytes = substr($socketBuffer, 1 + $protocolStrLen, 8);
        $infoHashBytes = substr($socketBuffer, 1 + $protocolStrLen + 8, 20);
        $peerIdBytes = substr($socketBuffer, 1 + $protocolStrLen + 28, 20);

        if ($protocolStr !== "BitTorrent protocol") {
        }

        $incomingHandshake = [
            'ReservedBytes' => $reservedBytes,
            'ProtocolHeader' => $protocolStr,
            'InfoHash' => bin2hex($infoHashBytes),
            'PeerId' => $peerIdBytes,
        ];

        $receiving = false;
    }
}

if ($incomingHandshake !== null) {
    $currentPacketBuffer = '';
    $currentPacketBuffer .= substr($socketBuffer, 0, $dataLength);
    $parsedBytes = 0;
    do {
        $packet = null;
        if ($packet !== null) {
            $parsedBytes += 0;
            $receiveQueue[] = $packet;
        }
    } while ($packet !== null);
    $currentPacketBuffer = substr($currentPacketBuffer, $parsedBytes);
}
?>
--EXPECT--
