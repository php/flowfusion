--TEST--
Translated seed (inferredbugs-csharp: dnlib/1)
--FILE--
<?php
$h = fopen('php://temp', 'w+b');
fwrite($h, str_repeat("\0", 64));
rewind($h);
$s = stream_get_contents($h);
$n = strlen($s);

if ($n < 24) {
    exit;
}

$dirs = unpack('V16', substr($s, 0, 64));
$va = $dirs[15];
$size = $dirs[16];

if ($va === 0) {
    exit;
}
if ($size < 0x48) {
    exit;
}

$md = substr($s, $va, $size);
if (strlen($md) < 16) {
    exit;
}

$streamCount = ord($md[12]) | (ord($md[13]) << 8);
$pos = 16;

for ($i = 0; $i < $streamCount; $i++) {
    if ($pos + 8 > strlen($md)) {
        break;
    }
    $off = unpack('V', substr($md, $pos, 4))[1];
    $len = unpack('V', substr($md, $pos + 4, 4))[1];
    if ($off + $len < $off || $off > $size || $off + $len > $size) {
        exit;
    }
    $pos += 8;
}

echo "";
?>
--EXPECT--
