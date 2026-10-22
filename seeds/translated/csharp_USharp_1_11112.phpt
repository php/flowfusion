--TEST--
Translated seed (inferredbugs-csharp: USharp/1)
--FILE--
<?php
$vals = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN, 0x7fffffff, 0xffffffff, 0x100000000];
foreach ($vals as $v) {
    $u32 = $v & 0xffffffff;
    $u64 = $v < 0 ? ($v & 0xffffffffffffffff) : $v;
    $s = [];
    if ($v === 0) $s[] = 'Zero';
    if (($v & 1) === 0) $s[] = 'Even';
    $s[] = sprintf('F0x%08X', $u32);
    $s[] = sprintf('G0x%016X', $u64);
    $name = (string)$v;
    if ($name !== '' && strcasecmp($name, 'inherit') !== 0) {
        $s[] = 'N=' . $name;
    }
    echo '[' . implode(', ', $s) . ']' . PHP_EOL;
}
?>
--EXPECT--
