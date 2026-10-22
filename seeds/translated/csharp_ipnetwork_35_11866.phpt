--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/35)
--FILE--
<?php
$ips = ['1.1.1.1', '255.255.255.255', '2.2.2.2', '0.0.0.0'];
$nets = [];
foreach ($ips as $ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP)) $nets[] = $ip;
}
sort($nets, SORT_STRING);
echo implode("\n", $nets), "\n";
?>
--EXPECT--
