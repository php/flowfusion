--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/1)
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
    if ($n === false || $m === false) continue;
    $a = unpack('n8', $n);
    $b = unpack('n8', $m);
    $cidr = 0;
    for ($i = 1; $i <= 8; $i++) {
        $w = $b[$i];
        if ($w === 0xffff) { $cidr += 16; continue; }
        for ($bit = 15; $bit >= 0; $bit--) {
            if (($w >> $bit) & 1) $cidr++; else break 2;
        }
    }
    $net = '';
    for ($i = 1; $i <= 8; $i++) $net .= pack('n', $a[$i] & $b[$i]);
    inet_ntop($net);
}
foreach (['xxxx::', '::', 'garbage', null, '2001:db8::/129', '2001:db8::/0', '2FFF:FFFF:123::/60', '::/32'] as $s) {
    if ($s === null) continue;
    @inet_pton($s);
}
for ($cidr = 0; $cidr <= 128; $cidr += 17) {
    $mask = $cidr === 0 ? str_repeat("\0", 16) : str_repeat("\xff", intdiv($cidr, 8)) . ($cidr % 8 ? chr((0xff << (8 - ($cidr % 8))) & 0xff) : '') . str_repeat("\0", 15 - intdiv($cidr, 8));
    inet_ntop($mask);
}
echo "";
?>
--EXPECT--
