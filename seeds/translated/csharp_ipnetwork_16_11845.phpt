--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/16)
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
foreach (['xxxx::', '::', 'garbage', '0:0:0:0:0:0:1:0:0 0:1:2:3:4:5:6:7:8', null, ''] as $s) {
    @inet_pton($s);
}
foreach (['::/0', '::/32', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/128', '2FFF:FFFF:123::/60', '2FFF:FFFE:123::/60', '2FFF:FFFC:123::/60', '2FFF:FFFA:123::/60', 'FFFF:FFF1:123::/60'] as $s) {
    @inet_pton(strtok($s, '/'));
}
echo "ok\n";
?>
--EXPECT--
