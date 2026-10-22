--TEST--
Translated seed (inferredbugs-csharp: Quic.NET/1)
--FILE--
<?php
$data = [0xFF];
$typeMap = [
    0xFF => 'InitialPacket',
    0xFE => 'RetryPacket',
    0x8E => 'ShortHeaderPacket',
    0x77 => 'LongHeaderPacket',
    0x80 => 'VersionNegotiationPacket',
    0x7D => 'HandshakePacket',
];

$type = 'BrokenPacket';
if ($data !== null && count($data) > 0) {
    $b = $data[0] & 0xFF;
    if (array_key_exists($b, $typeMap)) {
        $type = $typeMap[$b];
    }
}

$result = null;
if ($type === 'InitialPacket') {
    $result = new stdClass();
}
if ($result !== null) {
    $result->decoded = $data;
}
echo $type;
?>
--EXPECT--
