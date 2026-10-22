--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/26)
--FILE--
<?php
$tests = [
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', '2001:db8::/128'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe', '2001:db8::/127'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffc', '2001:db8::/126'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff8', '2001:db8::/125'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0', '2001:db8::/124'],
    ['2FFF:FFFF:123::/60', null, '2fff:ffff:123::/60'],
    ['::/0', null, '::/0'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/128'],
];
foreach ($tests as $t) {
    if ($t[1] === null) {
        $n = inet_pton($t[0]);
        $s = strtolower(inet_ntop($n));
        if (strpos($t[0], '/') === false && strpos($t[0], ':') !== false && $s !== strtolower($t[2])) { echo $s; }
    } else {
        $ip = inet_pton($t[0]);
        $mask = inet_pton($t[1]);
        $net = $ip & $mask;
        $cidr = 0;
        foreach (unpack('n*', $mask) as $w) { $cidr += substr_count(decbin($w), '1'); }
        $prefix = strtolower(inet_ntop($net));
        $out = $prefix . '/' . $cidr;
        if ($out !== strtolower($t[2])) { echo $out; }
    }
}
$bad = ['xxxx::', 'garbage', '0:0:0:0:0:0:1:0:0 0:1:2:3:4:5:6:7:8'];
foreach ($bad as $x) {
    $ok = @inet_pton($x);
    if ($ok !== false) { echo $x; }
}
$cidrs = [0, 1, 32, 33, 60, 64, 112, 124, 127, 128];
foreach ($cidrs as $c) {
    $mask = $c === 0 ? str_repeat("\0", 16) : pack('J2', $c >= 64 ? -1 : ((1 << $c) - 1) << (64 - $c), 0);
}
?>
--EXPECT--
