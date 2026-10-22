--TEST--
Translated seed (inferredbugs-csharp: Quic.NET/2)
--FILE--
<?php
$map = [
    0xFF => 'InitialPacket',
    0xFE => 'RetryPacket',
    0x8E => 'ShortHeaderPacket',
    0x77 => 'LongHeaderPacket',
    0x80 => 'VersionNegotiationPacket',
    0x7D => 'HandshakePacket',
];

foreach ([[], [0xFF, 1, 2], [0x00], [0x80, 3, 4]] as $data) {
    $type = (!isset($data[0]) || !array_key_exists($data[0], $map)) ? 'BrokenPacket' : $map[$data[0]];
    $result = null;
    if ($type === 'InitialPacket') {
        $result = new stdClass();
    }
    if ($result !== null) {
        $result->decoded = $data;
    }
}
?>
--EXPECT--
