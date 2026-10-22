--TEST--
Translated seed (inferredbugs-csharp: Stardust/4)
--FILE--
<?php
$start = hrtime(true);
$uptime = intdiv($start, 1000000000);

$processes = [];
foreach (scandir('.') as $name) {
    if ($name === '.' || $name === '..') continue;
    $base = pathinfo($name, PATHINFO_FILENAME);
    if ($base === '' || in_array(strtolower($base), ['idle','system','explorer','svchost','dllhost','conhost'], true)) continue;
    if (!in_array($base, $processes, true)) $processes[] = $base;
}

$ips = [];
$ifaces = @net_get_interfaces();
if (is_array($ifaces)) {
    foreach ($ifaces as $iface) {
        if (!empty($iface['unicast']) && is_array($iface['unicast'])) {
            foreach ($iface['unicast'] as $addr) {
                if (!empty($addr['address']) && filter_var($addr['address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $oct = explode('.', $addr['address']);
                    if ((int)$oct[0] !== 169 && !in_array($addr['address'], $ips, true)) $ips[] = $addr['address'];
                }
            }
        }
    }
}

$macs = [];
if (function_exists('net_get_interfaces')) {
    foreach ((array)@net_get_interfaces() as $iface) {
        if (!empty($iface['unicast']) && is_array($iface['unicast'])) {
            foreach ($iface['unicast'] as $addr) {
                if (!empty($addr['address']) && preg_match('/^([0-9a-f]{2}:){5}[0-9a-f]{2}$/i', $addr['address'])) $macs[] = strtolower($addr['address']);
            }
        }
    }
}
sort($macs);

$info = [
    'uptime' => $uptime,
    'time' => gmdate('c'),
    'processes' => implode(',', $processes),
    'ips' => implode(',', $ips),
    'macs' => implode(',', $macs),
    'tick' => (int)(microtime(true) * 1000),
];

$json = json_encode($info, JSON_UNESCAPED_SLASHES);
echo $json, PHP_EOL;

$decoded = json_decode($json, true);
if (is_array($decoded) && isset($decoded['tick'])) {
    $delay = (int)(microtime(true) * 1000) - (int)$decoded['tick'];
    echo $delay >= 0 ? "" : "";
}
?>
--EXPECT--
