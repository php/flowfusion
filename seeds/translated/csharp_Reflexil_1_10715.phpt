--TEST--
Translated seed (inferredbugs-csharp: Reflexil/1)
--FILE--
<?php
$buf = '';
for ($i = 0; $i < 5; $i++) {
    $s = str_repeat(chr(65 + $i), $i + 1);
    $buf .= pack('V', strlen($s)) . $s;
}
$pos = 0;
$read = function() use (&$buf, &$pos) {
    $len = unpack('V', substr($buf, $pos, 4))[1];
    $pos += 4;
    $s = substr($buf, $pos, $len);
    $pos += $len;
    return $s;
};
$old = $pos;
$first = $read();
$pos = 0;
$second = $read();
$pos = $old;
$data = [$first, $second, $read()];
$map = [];
foreach ($data as $k => $v) {
    $map[$v] = $k;
}
ksort($map);
echo count($map) . "\n";
echo array_sum(array_map('strlen', $data)) . "\n";
echo $map[$first] . ":" . $map[$second] . "\n";
?>
--EXPECT--
