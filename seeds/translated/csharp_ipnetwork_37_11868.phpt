--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/37)
--FILE--
<?php
$tests = [
    ['192.168.168.100', '255.255.255.0', '192.168.168.0/24'],
    ['0.0.0.0/0', '0.0.0.0', '0.0.0.0/0'],
    ['255.255.255.255/32', '255.255.255.255', '255.255.255.255/32'],
    ['10.0.0.0', '255.0.0.0', '10.0.0.0/8'],
    ['172.0.0.0', '255.255.0.0', '172.0.0.0/16'],
    ['192.0.0.0', '255.255.255.0', '192.0.0.0/24'],
];
foreach ($tests as [$ip, $mask, $expect]) {
    $n = ip2long($ip) & ip2long($mask);
    $cidr = substr_count(decbin(ip2long($mask) & 0xffffffff), '1');
    $out = long2ip($n) . '/' . $cidr;
    if ($out !== $expect) { exit(1); }
}
$cases = [
    ['0.0.0.0', '255.255.255.255', true],
    ['255.255.255.255', '255.255.255.255', true],
    ['0.255.0.0', '255.255.255.255', false],
    ['255.255.0.1', '255.255.255.255', false],
];
foreach ($cases as [$mask, $_, $expect]) {
    $x = ip2long($mask) & 0xffffffff;
    $seen0 = false; $ok = true;
    for ($i = 31; $i >= 0; $i--) {
        $b = ($x >> $i) & 1;
        if ($seen0 && $b) { $ok = false; break; }
        if (!$b) $seen0 = true;
    }
    if ($ok !== $expect) { exit(1); }
}
$ip = ip2long('192.168.0.1') & 0xffffffff;
$net = ip2long('192.168.0.0') & 0xffffffff;
$mask = ip2long('255.255.255.0') & 0xffffffff;
if ((($ip & $mask) !== $net) || ((ip2long('10.10.10.10') & $mask) === $net)) exit(1);
$start = ip2long('192.168.168.0') & 0xffffffff;
$end = ip2long('192.168.168.255') & 0xffffffff;
$xor = $start ^ $end;
$cidr = 32;
while ($xor) { $xor >>= 1; $cidr--; }
$wide = $start & (~((1 << (32 - $cidr)) - 1) & 0xffffffff);
if (long2ip($wide) . '/' . $cidr !== '192.168.168.0/24') exit(1);
echo "ok\n";
?>
--EXPECT--
