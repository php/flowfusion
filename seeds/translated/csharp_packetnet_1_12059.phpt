--TEST--
Translated seed (inferredbugs-csharp: packetnet/1)
--FILE--
<?php
$bytes = hex2bin('080200001a0000223fc1f37800223fc1f37878acc00a5e0e00009ab2dbf2b119');
$fc = unpack('v', substr($bytes, 0, 2))[1];
$duration = unpack('v', substr($bytes, 2, 2))[1];
$addr1 = strtoupper(bin2hex(substr($bytes, 4, 6)));
$addr2 = strtoupper(bin2hex(substr($bytes, 10, 6)));
$addr3 = strtoupper(bin2hex(substr($bytes, 16, 6)));
$seq = unpack('v', substr($bytes, 22, 2))[1];
$qos = unpack('v', substr($bytes, 24, 2))[1];
$fcs = unpack('V', substr($bytes, 26, 4))[1];

$protocolVersion = $fc & 0x3;
$type = ($fc >> 2) & 0x3;
$subtype = ($fc >> 4) & 0xf;
$toDS = (bool)(($fc >> 8) & 1);
$fromDS = (bool)(($fc >> 9) & 1);
$moreFragments = (bool)(($fc >> 10) & 1);
$retry = (bool)(($fc >> 11) & 1);
$powerManagement = (bool)(($fc >> 12) & 1);
$moreData = (bool)(($fc >> 13) & 1);
$wp = (bool)(($fc >> 14) & 1);
$order = (bool)(($fc >> 15) & 1);
$fragmentNumber = $seq & 0xf;
$sequenceNumber = $seq >> 4;

$fc2 = ($protocolVersion)
    | ($type << 2)
    | ($subtype << 4)
    | ((int)$toDS << 8)
    | ((int)$fromDS << 9)
    | ((int)$moreFragments << 10)
    | ((int)$retry << 11)
    | ((int)$powerManagement << 12)
    | ((int)$moreData << 13)
    | ((int)$wp << 14)
    | ((int)$order << 15);

$seq2 = ($sequenceNumber << 4) | $fragmentNumber;
$bytes2 = pack('vvvvvvV', $fc2, $duration, hexdec(substr($addr1, 0, 4)) | (hexdec(substr($addr1, 4, 4)) << 16), hexdec(substr($addr2, 0, 4)) | (hexdec(substr($addr2, 4, 4)) << 16), hexdec(substr($addr3, 0, 4)) | (hexdec(substr($addr3, 4, 4)) << 16), $seq2, $qos) . pack('V', $fcs);

echo strlen($bytes2), "\n";
echo $protocolVersion, " ", $type, " ", $subtype, " ", (int)$fromDS, " ", $duration, " ", $addr1, " ", $addr2, " ", $addr3, " ", $sequenceNumber, " ", $fragmentNumber, " ", $qos, " ", $fcs, "\n";
?>
--EXPECT--
