--TEST--
Translated seed (inferredbugs-csharp: LandlordsCore/2)
--FILE--
<?php
$payload = "\x08\x96\x01";
$len = strlen($payload);
$offset = 0;
$size = 0;
$shift = 0;
for ($i = 0; $i < $len; $i++) {
    $b = ord($payload[$i]);
    $size |= (($b & 0x7f) << $shift);
    if (($b & 0x80) === 0) {
        $offset = $i + 1;
        break;
    }
    $shift += 7;
}
$limited = substr($payload, $offset, $size);
$pos = 0;
$value = 0;
$shift = 0;
$tag = 0;
while ($pos < strlen($limited)) {
    $b = ord($limited[$pos++]);
    $tag |= (($b & 0x7f) << $shift);
    if (($b & 0x80) === 0) break;
    $shift += 7;
}
if ($tag === 8) {
    $value = 0;
    $shift = 0;
    while ($pos < strlen($limited)) {
        $b = ord($limited[$pos++]);
        $value |= (($b & 0x7f) << $shift);
        if (($b & 0x80) === 0) break;
        $shift += 7;
    }
}
$out = '';
$v = $value;
do {
    $byte = $v & 0x7f;
    $v >>= 7;
    if ($v !== 0) $byte |= 0x80;
    $out .= chr($byte);
} while ($v !== 0);
echo $out;
--EXPECT--
