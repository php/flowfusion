--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/29)
--FILE--
<?php
$cases = [
    ["192.168.168.100", "255.255.255.0", "192.168.168.0/24", "192.168.168.255", "192.168.168.1", "192.168.168.254", 24, 254],
    ["0.0.0.0/0", null, "0.0.0.0/0", "255.255.255.255", "0.0.0.1", "255.255.255.254", 0, 4294967294],
    ["0.0.0.0/32", null, "0.0.0.0/32", "0.0.0.0", "0.0.0.0", "0.0.0.0", 32, 0],
    ["255.255.255.255/32", null, "255.255.255.255/32", "255.255.255.255", "255.255.255.255", "255.255.255.255", 32, 0],
];
foreach ($cases as $c) {
    $n = ip2long($c[0]);
    $cidr = $c[1] === null ? (strpos($c[0], '/') !== false ? (int)substr(strrchr($c[0], '/'), 1) : (($n & 0xFF000000) === 0x0A000000 ? 8 : (($n & 0xFFF00000) === 0xAC100000 ? 16 : (($n & 0xFFFF0000) === 0xC0A80000 ? 24 : 32)))) : 32 - substr_count(decbin(ip2long($c[1]) & 0xFFFFFFFF), '0');
    $mask = $cidr === 0 ? 0 : (0xFFFFFFFF << (32 - $cidr)) & 0xFFFFFFFF;
    $network = $n & $mask;
    $broadcast = $network | (~$mask & 0xFFFFFFFF);
    echo long2ip($network), '/', $cidr, "\n";
    echo long2ip($broadcast), "\n";
}
$subnets = [];
$base = ip2long('192.168.0.0') & 0xFFFFFFFF;
for ($i = 0; $i < 4; $i++) $subnets[] = long2ip(($base + ($i << 8)) & 0xFFFFFFFF) . '/24';
echo implode("\n", $subnets), "\n";
$ips = ['1.1.1.1', '255.255.255.255', '2.2.2.2', '0.0.0.0'];
sort($ips, SORT_STRING);
echo implode(',', $ips), "\n";
?>
--EXPECT--
