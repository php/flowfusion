--TEST--
Translated seed (inferredbugs-csharp: Industrial-IoT/1)
--FILE--
<?php
$items = [];
$root = ["id" => "OpcPublisher", "children" => []];
$root["children"]["Data"] = ["ConnectionString" => ""];
$root["children"]["Methods"] = ["PublishNode" => [], "UnpublishNode" => [], "GetPublishedNodes" => []];
$items[] = $root;

$cases = [
    ["ns=2;s=Sensor1", "opc.tcp://localhost:4840"],
    ["nsu=http://example.com/UA/;s=Sensor2", "opc.tcp://LOCALHOST:4840/"],
    ["ns=2;i=42", "opc.tcp://localhost:4840"],
];

$sessions = [];
foreach ($cases as $c) {
    $id = $c[0];
    $endpoint = $c[1];
    $key = strtolower($endpoint);
    if (!isset($sessions[$key])) {
        $sessions[$key] = ["endpoint" => $endpoint, "nodes" => []];
    }
    $sessions[$key]["nodes"][] = $id;
}

$result = [];
foreach ($sessions as $s) {
    $result[] = $s["endpoint"] . ":" . implode(",", $s["nodes"]);
}

echo implode("\n", $result), "\n";
?>
--EXPECT--
