--TEST--
Translated seed (inferredbugs-csharp: StackExchange.Metrics/1)
--FILE--
<?php
$metrics = [];
$counter = $metrics['my_counter'] ??= ['count' => 0];
$counter['count']++;
$counter['count']++;
$metrics['my_counter'] = $counter;

$gauge = $metrics['my_gauge'] ??= ['host' => 'bret-host', 'values' => []];
if ($gauge !== ($metrics['my_gauge'] ?? null)) {
    throw new Exception('WAT?');
}

for ($i = 0; $i < 1000; $i++) {
    $gauge['values'][] = mt_rand() / mt_getrandmax();
    if (count($gauge['values']) > 256) {
        array_shift($gauge['values']);
    }
    usleep(5000);
}
?>
--EXPECT--
