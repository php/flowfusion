--TEST--
Translated seed (inferredbugs-csharp: verisol/1)
--FILE--
<?php
$xs = [];
for ($i = 0; $i < 8; $i++) {
    $xs[] = str_repeat(chr(65 + $i), $i + 1);
}
$h = [];
foreach ($xs as $s) {
    $h[$s] = crc32($s);
}
$y = [];
foreach ($h as $k => $v) {
    $y[substr($k, 0, 1)] = ($v & 1) ? $k : (string)$v;
}
foreach ($y as $k => $v) {
    $z = ($k === 'A') ? $v : ($k . $v);
    if (strlen($z) > 0) {
        $w = $z[0];
    }
}
echo "ok\n";
?>
--EXPECT--
