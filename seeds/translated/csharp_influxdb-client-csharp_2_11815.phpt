--TEST--
Translated seed (inferredbugs-csharp: influxdb-client-csharp/2)
--FILE--
<?php
echo "Start\n";

$fluxQuery = "from(bucket: \"telegraf\")\n"
    . " |> filter(fn: (r) => (r[\"_measurement\"] == \"cpu\" AND r[\"_field\"] == \"usage_system\"))"
    . " |> range(start: -1d)"
    . " |> sample(n: 5, pos: 1)";

$parts = explode("\n", $fluxQuery);
$recombined = implode(" ", $parts);
$len = strlen($recombined);

echo $len . "\n";
?>
--EXPECT--
