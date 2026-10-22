--TEST--
Translated seed (inferredbugs-csharp: Ruffles/3)
--FILE--
<?php
declare(strict_types=1);

$mtu = 512;
$smooth = 0;
$roundtrip = 500;
$var = 0;
$low = 500;
$high = 0;
$samples = [0, 1, 2, 500, 65535, 3, 70000, 1, 0, 42, 999999999];
foreach ($samples as $sample) {
    if ($sample === 0) $sample = 1;
    $smooth = $smooth === 0 ? (int)((1 - 0.125) + 0.125 * $sample) : (int)((1 - 0.125) * $smooth + 0.125 * $sample);
    $var -= intdiv($var, 4);
    if ($smooth >= $roundtrip) {
        $roundtrip += intdiv($smooth - $roundtrip, 8);
        $var += intdiv($smooth - $roundtrip, 4);
    } else {
        $roundtrip -= intdiv($roundtrip - $smooth, 8);
        $var += intdiv($roundtrip - $smooth, 4);
    }
    if ($roundtrip < $low) $low = $roundtrip;
    if ($var > $high) $high = $var;
}
$challenge = random_int(0, PHP_INT_MAX);
$difficulty = 0;
$answer = 0;
$working = $challenge;
while ($difficulty > 0 && ((($working << (PHP_INT_SIZE * 8 - $difficulty)) >> (PHP_INT_SIZE * 8 - $difficulty)) != 0)) {
    $answer++;
    $working = crc32((string)($challenge + $answer));
}
$payload = pack('C', 1) . pack('V', $answer) . pack('J', $challenge);
$buf = str_repeat("\0", max(1 + 8 + 1, 64));
$buf[0] = chr(2);
for ($i = 0; $i < 8; $i++) $buf[1 + $i] = chr(($challenge >> ($i * 8)) & 0xFF);
$buf[9] = chr($difficulty);
$merged = '';
for ($i = 0; $i < strlen($payload); $i++) {
    $merged .= $payload[$i];
}
echo "";
?>
--EXPECT--
