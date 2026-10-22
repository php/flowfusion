--TEST--
Translated seed (inferredbugs-csharp: mi-home/3)
--FILE--
<?php
declare(strict_types=1);

$platform = "7c4mx86hn658f0f3";
$devices = [
    ["sid" => "158d0001826509", "type" => "thSensor"],
    ["sid" => "158d00015dc662", "type" => "socketPlug"],
    ["sid" => "34ce0088db36", "type" => "gateway"],
];

foreach ($devices as $device) {
    $state = [
        "sid" => $device["sid"],
        "watched" => true,
        "connected" => false,
        "status" => null,
        "metrics" => [],
    ];

    $state["connected"] = true;
    usleep(1000);

    if ($device["type"] === "socketPlug") {
        $state["status"] = "off";
        usleep(1000);
        $state["status"] = "on";
    } elseif ($device["type"] === "gateway") {
        $state["metrics"]["light"] = "enabled";
        usleep(1000);
        $state["metrics"]["light"] = "disabled";
    } else {
        $state["metrics"]["temperature"] = 0.0;
        $state["metrics"]["humidity"] = 0.0;
        $state["metrics"]["voltage"] = 0.0;
    }

    $state["connected"] = false;
}

echo "";
?>
--EXPECT--
