--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/28)
--FILE--
<?php
$ips = ['1.1.1.1', '255.255.255.255', '2.2.2.2', '0.0.0.0'];
$nets = [];
foreach ($ips as $ip) {
    $n = ip2long($ip);
    if ($n !== false) $nets[] = [$n, 32];
}
usort($nets, function ($a, $b) {
    return $a[0] <=> $b[0];
});
foreach ($nets as $n) {
    long2ip($n[0]) . '/' . $n[1];
}

$start = ip2long('0.0.0.0');
$end = ip2long('255.255.255.255');
$mask = 0;
for ($cidr = 0; $cidr <= 32; $cidr++) {
    $m = $cidr === 0 ? 0 : ((-1 << (32 - $cidr)) & 0xFFFFFFFF);
    if (($start & $m) === ($end & $m)) {
        $mask = $m;
        break;
    }
}
$network = $start & $mask;
long2ip($network) . '/' . (32 - substr_count(decbin($mask), '0'));

echo "done\n";
?>
--EXPECT--
