--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/38)
--FILE--
<?php
$cases = [
    ['192.168.168.100', '255.255.255.0', '192.168.168.0/24'],
    ['0.0.0.0', '0.0.0.0', '0.0.0.0/0'],
    ['0.0.0.0', '255.255.255.255', '0.0.0.0/32'],
    ['255.255.255.255', '255.255.255.255', '255.255.255.255/32'],
    ['10.0.0.0', '255.0.0.0', '10.0.0.0/8'],
    ['172.0.0.0', '255.255.0.0', '172.0.0.0/16'],
    ['192.0.0.0', '255.255.255.0', '192.0.0.0/24'],
];
foreach ($cases as [$ip, $mask, $expect]) {
    $ipn = long2ip((ip2long($ip) & ip2long($mask)) & 0xffffffff) . '/' . substr_count(decbin(ip2long($mask) & 0xffffffff), '1');
    if ($ipn !== $expect) {
        throw new Exception('mismatch');
    }
}
foreach (['255.255.255.255' => 32, '255.255.255.0' => 24, '255.255.0.0' => 16, '255.0.0.0' => 8, '0.0.0.0' => 0] as $mask => $cidr) {
    $n = ip2long($mask) & 0xffffffff;
    $bits = substr_count(decbin($n), '1');
    if ($bits !== $cidr) {
        throw new Exception('mismatch');
    }
}
$ip = ip2long('192.168.0.1') & 0xffffffff;
$net = $ip & (ip2long('255.255.255.0') & 0xffffffff);
if (long2ip($net) !== '192.168.0.0') {
    throw new Exception('mismatch');
}
echo "";
?>
--EXPECT--
