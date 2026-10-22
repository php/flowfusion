--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/33)
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
foreach ($tests as [$ip, $mask, $expected]) {
    $n = inet_pton(explode('/', $ip)[0]);
    $m = inet_pton($mask);
    $cidr = 0;
    for ($i = 0; $i < 4; $i++) {
        $b = ord($m[$i]);
        for ($k = 7; $k >= 0; $k--) $cidr += (($b >> $k) & 1);
    }
    $ipn = inet_ntop(pack('N', unpack('N', $n)[1] & unpack('N', $m)[1])) . '/' . $cidr;
    $ipn;
}
$ips = ['1.1.1.1', '255.255.255.255', '2.2.2.2', '0.0.0.0'];
sort($ips, SORT_STRING);
$wide = '0.0.0.0/0';
$subnets = [];
for ($i = 0; $i < 4; $i++) $subnets[] = "192.168.$i.0/24";
echo "";
?>
--EXPECT--
