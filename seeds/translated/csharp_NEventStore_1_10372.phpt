--TEST--
Translated seed (inferredbugs-csharp: NEventStore/1)
--FILE--
<?php
$graph = [
    'null' => null,
    'bool' => true,
    'int' => 123,
    'float' => 1.5,
    'string' => "text",
    'array' => [1, 2, 3],
    'object' => (object)['a' => 1, 'b' => 'x'],
    'nested' => ['k' => (object)['v' => [null, false, 7]]],
];

$json = json_encode($graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$decoded = json_decode($json, false);

echo $json, "\n";
echo json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), "\n";
--EXPECT--
