--TEST--
Translated seed (inferredbugs-csharp: packetnet/14)
--FILE--
<?php
$bytes = fn(array $a) => implode('', array_map(fn($b) => chr($b), $a));

$radio = $bytes([
    0x00, 0x00, 0x10, 0x00,
    0x02, 0x00, 0x00, 0x80,
    0x00, 0x00, 0x01, 0x00,
    0x10, 0x00, 0x34, 0x12
]);

$payload = $bytes([0xFF]);
$radio .= $payload;

if (strlen($radio) !== 17) {
    exit(1);
}

$raw = substr($radio, 0, 16);
$tail = substr($radio, 14, 2);
if ($tail !== $bytes([0x34, 0x12])) {
    exit(1);
}

$buf = $bytes([0x00, 0x00, 0x10, 0x00, 0x02, 0x00, 0x00, 0x80, 0x00, 0x00, 0x01, 0x00, 0x10, 0x00, 0x34, 0x12]) . $payload;
if (strlen($buf) !== 17 || substr($buf, -1) !== "\xFF") {
    exit(1);
}
?>
--EXPECT--
