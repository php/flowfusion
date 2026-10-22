--TEST--
Translated seed (inferredbugs-csharp: mi-home/1)
--FILE--
<?php
$pwd = "pwd";
$thSensor = ["sid" => "158d0001826509", "Temperature" => null, "Humidity" => null];
$socketPlug = ["sid" => "158d00015dc662", "Status" => null];

$devices = [&$thSensor, &$socketPlug];

$packet = json_encode([
    "sid" => $thSensor["sid"],
    "Temperature" => 23.5,
    "Humidity" => 41,
    "Status" => "on"
]);

foreach ($devices as &$device) {
    $data = json_decode($packet, true);
    if ($device["sid"] === $data["sid"]) {
        if (array_key_exists("Temperature", $data)) $device["Temperature"] = $data["Temperature"];
        if (array_key_exists("Humidity", $data)) $device["Humidity"] = $data["Humidity"];
        if (array_key_exists("Status", $data)) $device["Status"] = $data["Status"];
    }
}

usleep(5000000);

echo "Temperature: " . $thSensor["Temperature"] . ", Humidity: " . $thSensor["Humidity"] . PHP_EOL;
echo "Socket status: " . $socketPlug["Status"] . PHP_EOL;
?>
--EXPECT--
