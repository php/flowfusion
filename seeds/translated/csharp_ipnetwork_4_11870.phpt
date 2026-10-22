--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/4)
--FILE--
<?php
$tests = [
    ['::/128', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 128],
    ['::/127', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe', 127],
    ['::/126', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffc', 126],
    ['::/125', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff8', 125],
    ['::/124', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0', 124],
    ['::/123', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffe0', 123],
    ['::/112', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:0000', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:0', 112],
    ['::/64', 'ffff:ffff:ffff:ffff:1234:1234:1234:1234', 'ffff:ffff:ffff:ffff::', 64],
    ['::/16', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff::', 16],
    ['::/0', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', '::', 0],
];
foreach ($tests as $t) {
    [$net, $ip, $mask, $cidr] = $t;
    inet_pton(explode('/', $net)[0]);
    inet_pton($ip);
    inet_pton($mask);
    inet_pton($net);
    inet_pton('2001:db8::');
    inet_pton('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff');
    inet_pton('::');
    inet_pton('ffff::');
    inet_pton('8000::');
    inet_pton('2fff:ffff:123::');
    inet_pton('2fff:fffe:123::');
    inet_pton('2fff:fffc:123::');
    inet_pton('ffff:fff1:123::');
    inet_pton('2001:db8::1');
    inet_pton('127.0.0.1');
}
foreach (['xxxx::', 'garbage', '0:0:0:0:0:0:1:0:0 0:1:2:3:4:5:6:7:8'] as $bad) {
    try { inet_pton($bad); } catch (Throwable $e) {}
}
foreach (['', null] as $bad) {
    try { inet_pton($bad); } catch (Throwable $e) {}
}
foreach ([0, 1, 32, 33, 60, 64, 65, 127, 128, 129] as $cidr) {
    if ($cidr < 0 || $cidr > 128) continue;
    $mask = $cidr === 0 ? '::' : inet_ntop(str_repeat("\xff", intdiv($cidr, 8)) . ($cidr % 8 ? chr((0xff << (8 - ($cidr % 8))) & 0xff) : '') . str_repeat("\0", 16 - intdiv($cidr + 7, 8)));
    inet_pton($mask);
}
echo "";
?>
--EXPECT--
