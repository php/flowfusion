--TEST--
Translated seed (inferredbugs-csharp: oakton/2)
--FILE--
<?php
$env = "Weird";
$config = ["direction" => "south", "color" => "orange"];

$host = (object)[
    "environment" => $env,
    "config" => $config,
    "server" => (object)[
        "features" => []
    ]
];

if ($host === null) {
    exit(1);
}

if ($host->environment !== "Weird") {
    exit(1);
}

if (($host->config["direction"] ?? null) !== "south") {
    exit(1);
}

if (($host->config["color"] ?? null) !== "orange") {
    exit(1);
}
?>
--EXPECT--
