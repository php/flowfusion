--TEST--
Translated seed (inferredbugs-csharp: kafka-net/2)
--FILE--
<?php
$brokers = [0 => [], 1 => []];
$messages = ['1', '2'];
foreach ($messages as $i => $value) {
    $brokers[$i % 2][] = $value;
}
$response = [];
foreach ($brokers as $brokerId => $group) {
    if ($group !== []) {
        $response[$brokerId] = $group;
    }
}
echo count($response) . PHP_EOL;
?>
--EXPECT--
