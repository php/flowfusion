--TEST--
Translated seed (inferredbugs-csharp: packetnet/12)
--FILE--
<?php
if (PHP_SAPI !== 'cli') {
    exit(1);
}

$frames = [
    "\x00\x00\x10\x00\x02\x00\x00\x80\x00\x00\x01\x00\x10\x00\x34\x12",
    "\x00\x00\x10\x00\x02\x00\x00\x80\x00\x00\x01\x00\x10\x00\x34\x12\xaa",
];

foreach ($frames as $frame) {
    $len = unpack('v', substr($frame, 2, 2))[1];
    $present1 = unpack('V', substr($frame, 4, 4))[1];
    $present2 = unpack('V', substr($frame, 8, 4))[1];
    $flags = unpack('v', substr($frame, 12, 2))[1];
    $unknown = unpack('v', substr($frame, 14, 2))[1];
    $payload = substr($frame, $len);
    $repacked = pack('CCvVVvv', 0, 0, 16, $present1, $present2, $flags, $unknown) . $payload;
    $newLen = unpack('v', substr($repacked, 2, 2))[1];
    $tail = unpack('v', substr($repacked, $newLen - 2, 2))[1];
    if ($newLen !== 16 || $tail !== 0x1234) {
        exit(1);
    }
}
?>
--EXPECT--
