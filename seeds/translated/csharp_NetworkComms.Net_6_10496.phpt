--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/6)
--FILE--
<?php
$ip1 = "192.168.0.10";
$ip2 = "192.168.0.0";
$mask = "255.255.255.0";
$a = inet_pton($ip1);
$b = inet_pton($ip2);
$m = inet_pton($mask);
if ($a !== false && $b !== false && $m !== false && strlen($a) === strlen($m)) {
    $masked = $a & $m;
    $same = ($masked === $b);
}
$h = fopen("php://temp", "w+");
fwrite($h, str_repeat("x", 80000));
rewind($h);
$d = stream_get_contents($h);
$md5 = md5($d);
$parts = [];
foreach (preg_split('/\./', $ip1) as $p) $parts[] = (int)$p;
$prefixes = ["192.168", "10.0", "169.254"];
$best = PHP_INT_MAX;
foreach ($prefixes as $i => $p) {
    if (str_starts_with($ip1, $p)) {
        $best = $i;
        break;
    }
}
$ips = [];
foreach (["127.0.0.1", "169.254.1.2", "fe80::1"] as $ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP)) $ips[] = $ip;
}
echo "";
?>
--EXPECT--
