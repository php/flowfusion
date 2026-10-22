--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/27)
--FILE--
<?php
$cases = [
    ['192.168.168.100', '255.255.255.0'],
    ['192.168.168.100/24', null],
    ['0.0.0.0/0', null],
    ['0.0.0.0/32', null],
    ['255.255.255.255/32', null],
    ['10.0.0.0', null],
    ['172.0.0.0', null],
    ['192.0.0.0', null],
    ['224.0.0.0', null],
    ['240.0.0.0', null],
];

foreach ($cases as [$ip, $mask]) {
    if ($mask === null) {
        $n = ip2long($ip);
        if ($n === false) continue;
        if (strpos($ip, '/') !== false) {
            [$addr, $cidr] = explode('/', $ip, 2);
            $cidr = (int)$cidr;
        } else {
            $addr = $ip;
            $cidr = 32;
        }
        $addrLong = ip2long($addr);
        if ($addrLong === false) continue;
        $maskLong = $cidr === 0 ? 0 : ((-1 << (32 - $cidr)) & 0xFFFFFFFF);
        $network = long2ip($addrLong & $maskLong);
        $broadcast = long2ip(($addrLong & $maskLong) | (~$maskLong & 0xFFFFFFFF));
        $first = $cidr >= 31 ? $network : long2ip((ip2long($network) + 1) & 0xFFFFFFFF);
        $last = $cidr >= 31 ? $network : long2ip((ip2long($broadcast) - 1) & 0xFFFFFFFF);
        $usable = $cidr >= 31 ? 0 : (int)((1 << (32 - $cidr)) - 2);
        echo $network, ' ', $broadcast, ' ', $first, ' ', $last, ' ', $usable, PHP_EOL;
    } else {
        $a = ip2long($ip);
        $m = ip2long($mask);
        if ($a === false || $m === false) continue;
        $cidr = 0;
        for ($bit = 0x80000000; $bit; $bit >>= 1) if ($m & $bit) $cidr++; else break;
        $network = long2ip($a & $m);
        $broadcast = long2ip(($a & $m) | (~$m & 0xFFFFFFFF));
        $first = $cidr >= 31 ? $network : long2ip((ip2long($network) + 1) & 0xFFFFFFFF);
        $last = $cidr >= 31 ? $network : long2ip((ip2long($broadcast) - 1) & 0xFFFFFFFF);
        $usable = $cidr >= 31 ? 0 : (int)((1 << (32 - $cidr)) - 2);
        echo $network, ' ', $mask, ' ', $broadcast, ' ', $cidr, ' ', $usable, ' ', $first, ' ', $last, PHP_EOL;
    }
}
?>
--EXPECT--
