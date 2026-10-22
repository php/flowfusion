--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/11)
--FILE--
<?php
$ips = ['1.1.1.1', '255.255.255.255', '2.2.2.2', '0.0.0.0'];
$nets = [];
foreach ($ips as $ip) {
    $n = ip2long($ip);
    if ($n !== false) $nets[] = [$n & 0xffffffff, 32];
}
usort($nets, fn($a, $b) => $a[0] <=> $b[0] ?: $a[1] <=> $b[1]);
foreach ($nets as [$n, $c]) {
    long2ip($n);
}
$start = ip2long('0.0.0.0');
$end = ip2long('255.255.255.255');
$mask = 0;
for ($i = 0; $i < 32; $i++) {
    if ((($start ^ $end) >> (31 - $i)) & 1) break;
    $mask = ($mask << 1) | 1;
}
$net = $start & (($mask << (32 - 32)) & 0xffffffff);
long2ip($net);
?>
--EXPECT--
