--TEST--
Translated seed (inferredbugs-csharp: oakton/3)
--FILE--
<?php
$env = "Weird";
$config = ["direction" => "south", "color" => "orange"];

$host = (object)[
    "services" => (object)[
        "environment" => (object)["EnvironmentName" => $env],
        "config" => $config
    ]
];

if ($host === null) {
    exit(1);
}

if ($host->services->environment->EnvironmentName !== "Weird") {
    exit(1);
}

if (($host->services->config["direction"] ?? null) !== "south") {
    exit(1);
}

if (($host->services->config["color"] ?? null) !== "orange") {
    exit(1);
}
?>
--EXPECT--
