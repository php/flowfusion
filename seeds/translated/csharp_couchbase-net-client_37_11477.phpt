--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/37)
--FILE--
<?php
$queries = [
    ["SELECT 'Hello World' AS Greeting", []],
    ["SELECT 'Hello World' ASB Greeting", []],
    ["SELECT * from `beer-sample` WHERE type=? LIMIT 10", ['beer']],
    ["SELECT * from `beer-sample` WHERE type=:type LIMIT 10", ['type' => 'beer']],
    ["SELECT * from `beer-sample` LIMIT 100", []],
];

$cache = [];
foreach ($queries as [$sql, $params]) {
    $prepared = str_contains($sql, 'LIMIT 100');
    $key = $prepared ? $sql : null;
    if ($prepared && !isset($cache[$key])) $cache[$key] = ['plan' => md5($sql), 'uses' => 0];
    if ($prepared) $cache[$key]['uses']++;
    $result = [
        'status' => str_contains($sql, 'ASB') ? 'failure' : 'success',
        'rows' => str_contains($sql, 'ASB') ? [] : (str_contains($sql, 'Hello World') ? [['Greeting' => 'Hello World']] : array_fill(0, 10, ['type' => 'beer'])),
    ];
    if ($prepared) $result['plan'] = $cache[$key]['plan'];
    if ($result['status'] === 'success' && $result['rows']) {
        $first = $result['rows'][0];
        if (isset($first['Greeting'])) echo $first['Greeting'], PHP_EOL;
    }
}
?>
--EXPECT--
