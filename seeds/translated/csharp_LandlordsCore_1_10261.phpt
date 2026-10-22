--TEST--
Translated seed (inferredbugs-csharp: LandlordsCore/1)
--FILE--
<?php
$payload = chr(8) . chr(150) . chr(1);
$len = strlen($payload);
$varint = '';
$n = $len;
while ($n > 127) {
    $varint .= chr(($n & 127) | 128);
    $n >>= 7;
}
$varint .= chr($n);
$stream = fopen('php://memory', 'r+');
fwrite($stream, $varint . $payload . "extra");
rewind($stream);
$size = 0;
$shift = 0;
while (true) {
    $b = ord(fgetc($stream));
    $size |= ($b & 127) << $shift;
    if (($b & 128) === 0) break;
    $shift += 7;
}
$data = fread($stream, $size);
$pos = 0;
$end = strlen($data);
$value = null;
while ($pos < $end) {
    $tag = ord($data[$pos++]);
    $field = $tag >> 3;
    $wire = $tag & 7;
    if ($field === 1 && $wire === 0) {
        $v = 0;
        $shift = 0;
        while ($pos < $end) {
            $b = ord($data[$pos++]);
            $v |= ($b & 127) << $shift;
            if (($b & 128) === 0) break;
            $shift += 7;
        }
        $value = $v;
    } else {
        break;
    }
}
echo $value . PHP_EOL;
?>
--EXPECT--
