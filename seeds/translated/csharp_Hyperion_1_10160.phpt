--TEST--
Translated seed (inferredbugs-csharp: Hyperion/1)
--FILE--
<?php
$buf = fopen('php://temp', 'w+b');

foreach ([0, 1, -1, 2147483647, -2147483648, 9223372036854775807, -9223372036854775808] as $v) {
    fwrite($buf, pack('q', $v));
}
foreach ([0, 1, -1, 32767, -32768] as $v) {
    fwrite($buf, pack('s', $v));
}
foreach ([0, 1, 255] as $v) {
    fwrite($buf, pack('C', $v));
}
foreach ([0, 1, -1, 1234567890, -1234567890] as $v) {
    fwrite($buf, pack('l', $v));
}

$strings = ["", null, "a", "héllo", str_repeat("x", 256)];
foreach ($strings as $s) {
    if ($s === null) {
        fwrite($buf, pack('l', -1));
    } else {
        $b = $s;
        fwrite($buf, pack('l', strlen($b)));
        fwrite($buf, $b);
    }
}

$bytes = [[], [0], [1, 2, 3, 4, 255]];
foreach ($bytes as $arr) {
    fwrite($buf, pack('l', count($arr)));
    foreach ($arr as $v) fwrite($buf, pack('C', $v));
}

$dt = new DateTimeImmutable('@0');
fwrite($buf, pack('q', (int)$dt->format('U')));

rewind($buf);

foreach ([8, 8, 8, 8, 8, 8, 8] as $n) fread($buf, $n);
foreach ([2, 2, 2, 2, 2] as $n) fread($buf, $n);
foreach ([1, 1, 1] as $n) fread($buf, $n);
foreach ([4, 4, 4, 4, 4] as $n) fread($buf, $n);

for ($i = 0; $i < 5; $i++) {
    $len = unpack('l', fread($buf, 4))[1];
    if ($len > 0) fread($buf, $len);
}

for ($i = 0; $i < 3; $i++) {
    $len = unpack('l', fread($buf, 4))[1];
    if ($len > 0) fread($buf, $len);
}

fread($buf, 8);
?>
--EXPECT--
