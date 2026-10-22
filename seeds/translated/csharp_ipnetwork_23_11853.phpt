--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/23)
--FILE--
<?php
$tests = [
    ['::', '::', 0, '::', '::', '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
    ['::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 128, '::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', '::', '::'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0', 124, '2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0', '2001:db8::', '2001:db8::f'],
    ['2FFF:FFFF:123::/60', null, 60, '2fff:ffff:123::', 'ffff:ffff:ffff:fff0::', '2fff:ffff:123::', '2fff:ffff:123:f:ffff:ffff:ffff:ffff'],
];

foreach ($tests as $t) {
    $ip = inet_pton($t[0]);
    $mask = inet_pton($t[1] ?? 'ffff:ffff:ffff:ffff::');
    $cidr = $t[2];
    $net = inet_ntop($ip & $mask);
    $m = inet_ntop($mask);
    $first = $net;
    $last = inet_ntop($ip | (~$mask));
    if ($cidr === 0) $last = 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff';
    echo $net . ' ' . $m . ' ' . $first . ' ' . $last . PHP_EOL;
}
?>
--EXPECT--
