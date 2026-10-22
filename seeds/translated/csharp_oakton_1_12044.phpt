--TEST--
Translated seed (inferredbugs-csharp: oakton/1)
--FILE--
<?php
$env = "Weird";
$config = ["direction" => "south", "color" => "orange"];

$host = [
    "server" => new stdClass(),
    "startup" => new stdClass(),
    "environment" => $env,
    "config" => $config,
];

if ($host["environment"] !== "Weird") {
    throw new Exception("environment mismatch");
}
if (($host["config"]["direction"] ?? null) !== "south") {
    throw new Exception("config mismatch");
}
if (($host["config"]["color"] ?? null) !== "orange") {
    throw new Exception("config mismatch");
}
?>
--EXPECT--
