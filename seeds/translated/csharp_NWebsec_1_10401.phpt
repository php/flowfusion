--TEST--
Translated seed (inferredbugs-csharp: NWebsec/1)
--FILE--
<?php
$raw = "\x30\x82\x01\x00\x30\x82\x00\x10\xA0\x03\x02\x01\x02\x02\x01\x01\x30\x00\x30\x00\x30\x00\x30\x00\x30\x00";
$pos = 0;
$len = strlen($raw);

$readTlv = function() use (&$raw, &$pos, $len) {
    if ($pos + 2 > $len) return null;
    $b0 = ord($raw[$pos]);
    $b1 = ord($raw[$pos + 1]);
    $pos += 2;
    if ($b1 < 0x80) return [$b0, $b1, substr($raw, $pos - 2, 2)];
    $n = $b1 - 0x80;
    $l = 0;
    for ($i = 0; $i < $n; $i++) {
        if ($pos >= $len) return null;
        $l = $l * 256 + ord($raw[$pos++]);
    }
    return [$b0, $l, substr($raw, $pos - $n - 2, $n + 2)];
};

$tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
while ($tlv !== null && $tlv[0] === 0xA0) $tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
$tlv = $readTlv();
echo hash('sha256', $raw), "\n";
?>
--EXPECT--
