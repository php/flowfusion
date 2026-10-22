--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/8)
--FILE--
<?php
$stores = [
    'kv-store' => ['name' => 'kv-store', 'open' => true, 'flushed' => 0, 'closed' => 0],
    'other-store' => ['name' => 'other-store', 'open' => true, 'flushed' => 0, 'closed' => 0],
];
$topics = [
    'kv-store' => 'kv-store-topic',
    'other-store' => 'other-store-topic',
];
$registered = [];
$offsets = [];

foreach ($stores as $name => &$store) {
    $registered[$name] = &$store;
}
unset($store);

foreach ($registered as $name => $store) {
    $topic = $topics[$name];
    $offsets[$topic] = -2;
}

$store = $registered['kv-store'];
$store['flushed']++;
$store['closed']++;

$store = $registered['other-store'];
$store['flushed']++;
$store['closed']++;

$duplicate = $registered['kv-store'];
$registered['kv-store-1'] = $duplicate;

$missing = array_key_exists('some-store-name', $registered) ? $registered['some-store-name'] : null;

foreach ($registered as $name => &$store) {
    if ($name === 'kv-store') {
        $store['closed']++;
        throw new Exception('boom!');
    }
    $store['closed']++;
}
unset($store);
?>
--EXPECT--
