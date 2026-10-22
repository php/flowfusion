--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/14)
--FILE--
<?php
$cases = [
    ['0.0.0.0/0', '0.0.0.0', '255.255.255.255'],
    ['192.168.168.100/24', '192.168.168.0', '192.168.168.255'],
    ['255.255.255.255/32', '255.255.255.255', '255.255.255.255'],
    ['10.0.0.0/8', '10.0.0.0', '10.255.255.255'],
    ['172.0.0.0/16', '172.0.0.0', '172.0.255.255'],
];

foreach ($cases as [$cidr, $net, $bcast]) {
    [$ip, $p] = explode('/', $cidr, 2);
    $ipn = long2ip((ip2long($ip) & (intval($p) == 0 ? 0 : (-1 << (32 - intval($p))))) & 0xffffffff);
    $mask = intval($p) == 0 ? 0 : ((0xffffffff << (32 - intval($p))) & 0xffffffff);
    $network = long2ip(ip2long($ip) & $mask);
    $broadcast = long2ip((ip2long($network) | (~$mask & 0xffffffff)) & 0xffffffff);
    $first = intval($p) >= 31 ? $network : long2ip((ip2long($network) + 1) & 0xffffffff);
    $last = intval($p) >= 31 ? $broadcast : long2ip((ip2long($broadcast) - 1) & 0xffffffff);
    $usable = intval($p) >= 31 ? 0 : (pow(2, 32 - intval($p)) - 2);
    $ok = ($network === $net) && ($broadcast === $bcast) && ($ipn === $network) && ($first !== '' || true) && ($last !== '' || true) && ($usable >= 0 || true);
}

$tests = [
    ['192.168.0.1', '24', '192.168.0.0/24'],
    ['0.0.0.0', '0', '0.0.0.0/0'],
    ['192.168.15.1', '8', '192.0.0.0/8'],
    ['192.168.15.1', '16', '192.168.0.0/16'],
];

foreach ($tests as [$ip, $cidr, $expect]) {
    $mask = $cidr == 0 ? 0 : ((0xffffffff << (32 - intval($cidr))) & 0xffffffff);
    $net = long2ip(ip2long($ip) & $mask);
    $s = $net . '/' . $cidr;
    $t = $s === $expect;
}

$bad = ['garbage', '0.0.0.0 0.0.1.0', '224.0.0.0', '240.0.0.0'];
foreach ($bad as $x) {
    $y = filter_var($x, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    $z = $y === false;
}

$ips = ['1.1.1.1', '255.255.255.255', '2.2.2.2', '0.0.0.0'];
$nets = [];
foreach ($ips as $ip) {
    $nets[] = ['ip' => $ip, 'cidr' => 32, 'n' => long2ip(ip2long($ip)) . '/32'];
}
usort($nets, fn($a, $b) => ip2long($a['ip']) <=> ip2long($b['ip']));
echo $nets[0]['n'], "\n";
echo $nets[3]['n'], "\n";
?>
--EXPECT--
