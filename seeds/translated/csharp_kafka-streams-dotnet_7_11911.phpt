--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/7)
--FILE--
<?php
$stores = [
    'kv-store' => ['topic' => 'kv-store-topic', 'closed' => false, 'flushed' => false, 'offset' => -2],
    'other-store' => ['topic' => 'other-store-topic', 'closed' => false, 'flushed' => false, 'offset' => -2],
];

$initialized = [];
$registered = [];
$topics = ['kv-store-topic' => [0], 'other-store-topic' => [0]];

foreach ($stores as $name => &$store) {
    if (!isset($topics[$store['topic']]) || !$topics[$store['topic']]) {
        exit(0);
    }
    $initialized[$name] = true;
}
unset($store);

if (isset($stores['kv-store']) && isset($stores['other-store'])) {
    $registered['kv-store'] = true;
    $registered['other-store'] = true;
}

if (($stores['kv-store']['offset'] ?? null) !== -2) {
    exit(0);
}

$got = $initialized['kv-store'] ?? false ? 'kv-store' : null;
if ($got !== 'kv-store') {
    exit(0);
}

$missing = $initialized['some-store-name'] ?? null;
if ($missing !== null) {
    exit(0);
}

foreach ($stores as $name => &$store) {
    $store['flushed'] = true;
}
unset($store);

$closeError = null;
foreach ($stores as $name => &$store) {
    try {
        if ($name === 'kv-store') {
            throw new Exception('boom!');
        }
        $store['closed'] = true;
    } catch (Throwable $e) {
        $closeError = $e;
    }
}
unset($store);

if (!$closeError) {
    exit(0);
}

echo "ok";
?>
--EXPECT--
