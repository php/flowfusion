--TEST--
Translated seed (inferredbugs-csharp: mi-home/2)
--FILE--
<?php
$transport = ['pwd' => '7c4mx86hn658f0f3', 'sid' => '34ce0088db36'];
$devices = [
    ['type' => 'th', 'sid' => '158d0001826509', 'temperature' => 0.0, 'humidity' => 0.0, 'voltage' => 0.0],
    ['type' => 'socket', 'sid' => '158d00015dc662', 'status' => 'off', 'inuse' => 0, 'load_power' => 0.0, 'power_consumed' => 0.0, 'voltage' => 0.0],
    ['type' => 'gateway', 'sid' => '34ce0088db36', 'rgb' => 0, 'illumination' => 0, 'proto' => '1.0'],
];

foreach ($devices as &$d) {
    $d['watch'] = true;
}
unset($d);

$connected = true;
usleep(5000000);
$connected = false;

foreach ($devices as $d) {
    if ($d['type'] === 'th') {
        echo "TH sensor temperature: {$d['temperature']}C, humidity: {$d['humidity']}%, voltage: {$d['voltage']} V\n";
    } elseif ($d['type'] === 'socket') {
        echo "Socket plug status: {$d['status']}, inuse: " . ($d['inuse'] == 1 ? "yes" : "no") . ", load power: {$d['load_power']} W, power consumed: {$d['power_consumed']} Wh, voltage: {$d['voltage']} V\n";
    } else {
        echo "Gateway rgb: {$d['rgb']}, illumination: {$d['illumination']}, proto: {$d['proto']}\n";
    }
}
?>
--EXPECT--
