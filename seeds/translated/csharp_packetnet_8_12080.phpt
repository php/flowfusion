--TEST--
Translated seed (inferredbugs-csharp: packetnet/8)
--FILE--
<?php
$version = 0;
$flags = 1;
$linkType = 105;
$fields = [];
$fields[] = ['type' => 2, 'bytes' => str_repeat("A", 3)];
$fields[] = ['type' => 2, 'bytes' => str_repeat("B", 5)];
$fields[] = ['type' => 7, 'bytes' => str_repeat("C", 1)];

$length = 8;
foreach ($fields as $f) {
    $length += 4 + strlen($f['bytes']);
}

$header = pack('C C v V', $version, $flags, $length, $linkType);
foreach ($fields as $f) {
    $header .= pack('v v', $f['type'], strlen($f['bytes'])) . $f['bytes'];
}

$pos = 0;
$v = ord($header[$pos]);
$f = ord($header[$pos + 1]);
$len = unpack('v', substr($header, $pos + 2, 2))[1];
$lt = unpack('V', substr($header, $pos + 4, 4))[1];

$parsed = [];
$pos = 8;
while ($pos < $len) {
    $t = unpack('v', substr($header, $pos, 2))[1];
    $fl = unpack('v', substr($header, $pos + 2, 2))[1];
    $data = substr($header, $pos + 4, $fl);
    $parsed[] = ['type' => $t, 'bytes' => $data];
    $pos += 4 + $fl;
}

$out = [];
$out[] = $v;
$out[] = $f;
$out[] = $len;
$out[] = $lt;
$out[] = count($parsed);
foreach ($parsed as $p) {
    $out[] = $p['type'];
    $out[] = strlen($p['bytes']);
}
echo implode(" ", $out), "\n";
?>
--EXPECT--
