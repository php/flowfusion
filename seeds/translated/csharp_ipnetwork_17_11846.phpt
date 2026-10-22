--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/17)
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
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:0000:0000:0000:0000:0000:0000:0000'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
];
foreach ($tests as [$ip, $mask]) {
    @inet_pton($ip);
    @inet_pton($mask);
}
foreach (['xxxx::', '::', 'garbage', null, '2001:db8::/129', '2001:0db8::/124'] as $s) {
    if ($s !== null && strpos($s, '/') !== false) {
        @inet_pton(explode('/', $s, 2)[0]);
    } else {
        @inet_pton((string)$s);
    }
}
for ($i = 0; $i <= 128; $i++) {
    $m = $i === 0 ? str_repeat("\0", 16) : ($i === 128 ? str_repeat("\xff", 16) : pack('H*', str_pad(str_repeat('f', intdiv($i, 4)) . substr('0123456789abcdef', 0, $i % 4 ? 1 : 0), 32, '0', STR_PAD_RIGHT)));
    @inet_ntop($m);
}
echo "";
?>
--EXPECT--
