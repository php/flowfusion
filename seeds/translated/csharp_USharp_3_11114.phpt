--TEST--
Translated seed (inferredbugs-csharp: USharp/3)
--FILE--
<?php
$values = [
    0,
    1,
    -1,
    PHP_INT_MAX,
    PHP_INT_MIN,
    0xFFFFFFFF,
    0x80000000,
    0x7FFFFFFF,
    1234567890123456789,
    -1234567890123456789,
];

foreach ($values as $v) {
    $s = strtoupper(str_pad(dechex($v), 16, '0', STR_PAD_LEFT));
    $a = [];
    if (($v & 1) === 0) $a[] = 'Even';
    if (($v & 2) !== 0) $a[] = 'Bit2';
    if ($v < 0) $a[] = 'Neg';
    if ($v === PHP_INT_MAX) $a[] = 'Max';
    if ($v === PHP_INT_MIN) $a[] = 'Min';
    $a[] = 'Flags=0x' . $s;
    $a[] = 'MetaPath("' . $v . '", "mod", Type.' . (($v & 1) ? 'A' : 'B') . ')';
    echo '[' . implode(', ', $a) . ']' . PHP_EOL;
}
?>
--EXPECT--
