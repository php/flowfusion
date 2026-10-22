--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/25)
--FILE--
<?php
$tests = [
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffc'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff8'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0'],
    ['2001:0db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffe0'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:0000'],
    ['ffff:ffff:ffff:ffff:1234:1234:1234:1234', 'ffff:ffff:ffff:ffff:0000:0000:0000:0000'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
];
foreach ($tests as [$ip, $mask]) {
    $n = inet_pton($ip);
    $m = inet_pton($mask);
    $net = $n & $m;
    $cidr = 0;
    for ($i = 0; $i < 16; $i++) {
        $b = ord($m[$i]);
        for ($j = 7; $j >= 0; $j--) $cidr += (($b >> $j) & 1);
    }
    $host = 128 - $cidr;
    $total = gmp_pow(2, $host);
    $first = inet_ntop($net);
    $last = inet_ntop($net | str_repeat("\xff", 16 - intdiv($cidr, 8)) . ($cidr % 8 ? chr((1 << (8 - ($cidr % 8))) - 1) : ''));
    if ($cidr === 128) $last = $first;
    if ($cidr === 0) $first = '::';
}
foreach (['garbage', '::/129', 'xxxx::', '::/0', '2FFF:FFFF:123::/60', '2001:0db8:: ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0'] as $s) {
    @inet_pton(explode('/', $s)[0]);
}
for ($c = 0; $c <= 128; $c += 16) {
    $mask = $c === 0 ? str_repeat("\0", 16) : inet_pton(str_repeat('ffff:', intdiv($c, 16)) . ($c % 16 ? sprintf('%04x', (0xffff << (16 - $c % 16)) & 0xffff) : ''));
    if ($mask !== false) inet_ntop($mask);
}
echo "";
?>
--EXPECT--
