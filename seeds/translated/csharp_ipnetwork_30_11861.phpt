--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/30)
--FILE--
<?php
function ip2u($ip) { return sprintf('%u', ip2long($ip)); }
function u2ip($u) { return long2ip((int)$u); }
function parseNet($s) {
    if ($s === null || $s === '') return null;
    if (strpos($s, '/') !== false) { [$ip, $cidr] = explode('/', $s, 2); }
    elseif (strpos($s, ' ') !== false) { [$ip, $cidr] = explode(' ', $s, 2); }
    else { $ip = $s; $cidr = null; }
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) return null;
    if ($cidr === null) {
        $first = (int)explode('.', $ip)[0];
        $cidr = $first < 128 ? 8 : ($first < 192 ? 16 : 24);
    } elseif (filter_var($cidr, FILTER_VALIDATE_INT) === false || $cidr < 0 || $cidr > 32) return null;
    $mask = $cidr == 0 ? 0 : ((0xFFFFFFFF << (32 - $cidr)) & 0xFFFFFFFF);
    $ipu = ip2u($ip);
    $net = $ipu & $mask;
    $bcast = $net | (~$mask & 0xFFFFFFFF);
    $first = $cidr >= 31 ? $net : $net + 1;
    $last = $cidr >= 31 ? $bcast : $bcast - 1;
    $usable = $cidr >= 31 ? 0 : ($bcast - $net - 1);
    return [$net, $mask, $bcast, $first, $last, $cidr, $usable];
}
$cases = [
    "192.168.168.100 255.255.255.0","192.168.168.100/24","0.0.0.0/0","0.0.0.0/32","255.255.255.255/32",
    "10.0.0.0","172.0.0.0","192.0.0.0","192.168.0.1/24","192.168.0.1/16","192.168.1.1/24","0.0.0.0/1",
    "0.0.0.0/30","0.0.0.0/31","0.0.0.0/24","0.0.0.0/8","0.0.0.0/16","1.2.3.4","6.6.6.6"
];
foreach ($cases as $c) parseNet($c);
$net = parseNet("192.168.168.100/24");
$sub = [];
for ($i = 0; $i < 4; $i++) $sub[] = u2ip($net[0] + ($i << 8)) . "/24";
$wide = parseNet("0.0.0.0/0");
$mask = parseNet("255.255.255.0");
$cidr = $mask[5];
$bits = substr_count(decbin($mask[1]), '1');
$contains = (ip2u("192.168.0.100") & $mask[1]) === ($mask[0] & $mask[1]);
$overlap = true;
echo "";
?>
--EXPECT--
