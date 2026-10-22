--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/3)
--FILE--
<?php
$tests = [
    ['::1', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
    ['::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff::'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
    ['2001:0db8::', null],
    ['::', '0'],
    ['::', '33'],
    ['::', '128'],
    ['::', '129'],
];
foreach ($tests as [$ip, $mask]) {
    if ($mask === null) {
        @inet_pton($ip);
    } elseif ($mask === '0' || $mask === '33' || $mask === '128' || $mask === '129') {
        $cidr = (int)$mask;
        if ($cidr < 0 || $cidr > 128) {
            continue;
        }
        $bits = str_repeat('1', $cidr) . str_repeat('0', 128 - $cidr);
        $hex = str_pad(gmp_strval(gmp_init($bits ?: '0', 2), 16), 32, '0', STR_PAD_LEFT);
        inet_ntop(hex2bin($hex));
    } else {
        $a = inet_pton($ip);
        $b = inet_pton($mask);
        if ($a === false || $b === false) {
            continue;
        }
        $n = $a & $b;
        inet_ntop($n);
    }
}
echo "ok\n";
?>
--EXPECT--
