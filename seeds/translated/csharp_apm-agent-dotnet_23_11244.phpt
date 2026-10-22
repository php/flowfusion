--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/23)
--FILE--
<?php
$long = str_repeat('a', 1200);

$transaction = [
    'name' => substr($long, 0, 1024),
    'type' => 'test',
    'duration' => 1,
    'result' => 'fail',
];

$dummy = [
    'intProp' => 42,
    'stringProp' => $long,
];

echo json_encode($transaction, JSON_UNESCAPED_SLASHES), "\n";
echo json_encode($dummy, JSON_UNESCAPED_SLASHES), "\n";
?>
--EXPECT--
