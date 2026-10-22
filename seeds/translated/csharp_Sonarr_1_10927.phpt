--TEST--
Translated seed (inferredbugs-csharp: Sonarr/1)
--FILE--
<?php
$serviceName = 'NzbDrone';
$services = [
    ['ServiceName' => 'nzbdrone', 'Status' => 'Stopped'],
    ['ServiceName' => 'OtherService', 'Status' => 'Running'],
];

$exists = false;
foreach ($services as $s) {
    if (strcasecmp($s['ServiceName'], $serviceName) === 0) {
        $exists = true;
        break;
    }
}

$service = null;
foreach ($services as $s) {
    if (strcasecmp($s['ServiceName'], $serviceName) === 0) {
        $service = $s;
        break;
    }
}

if ($service !== null) {
    if ($service['Status'] !== 'Paused' || $service['Status'] !== 'Stopped') {
        $service['Status'] = 'Running';
    }
    if ($service['Status'] === 'Running') {
        $service['Status'] = 'Stopped';
    }
}

echo ($exists ? "1" : "0") . "\n";
?>
--EXPECT--
