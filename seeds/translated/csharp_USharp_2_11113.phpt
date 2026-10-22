--TEST--
Translated seed (inferredbugs-csharp: USharp/2)
--FILE--
<?php
$values = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN, 0x7fffffff, 0x80000000, 0xffffffffffffffff];
$out = [];

foreach ($values as $v) {
    $u32 = sprintf("0x%08X", $v & 0xffffffff);
    $u64 = sprintf("0x%016X", $v);
    $s = "A=" . $u32 . ",B=" . $u64;
    $out[] = $s;
    $out[] = strtolower($s);
    $out[] = strtoupper($s);
}

foreach ($out as $s) {
    strlen($s);
    hash('crc32b', $s);
    preg_match('/0x[0-9a-f]+/i', $s, $m);
    if (isset($m[0])) {
        substr($m[0], 2);
    }
}

echo "ok\n";
--EXPECT--
