--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/46)
--FILE--
<?php
$rows = [
    ['name' => 'alpha', 'type' => 'beer'],
    ['name' => 'beer-sample', 'type' => 'beer'],
    ['name' => 'omega', 'type' => 'wine'],
];

$indexes = [];
foreach ($rows as $row) {
    if ($row['name'] === 'beer-sample') {
        $indexes = [];
    }
}

$prepared = false;
$cache = [];
$query = 'SELECT * FROM `beer-sample` WHERE type=? LIMIT 10';

for ($i = 0; $i < 3; $i++) {
    $usePrepared = $prepared;
    if ($usePrepared && isset($cache[$query])) {
        $result = $cache[$query];
    } else {
        $result = [];
        foreach ($rows as $row) {
            if ($row['type'] === 'beer') {
                $result[] = $row;
            }
        }
        $result = array_slice($result, 0, 10);
        if ($usePrepared) {
            $cache[$query] = $result;
        }
    }
    if ($i === 0) {
        $prepared = true;
    }
}

$normal = [];
foreach ($rows as $row) {
    if ($row['type'] === 'beer') {
        $normal[] = $row;
    }
}
$normal = array_slice($normal, 0, 10);

$preparedResult = [];
if (isset($cache[$query])) {
    $preparedResult = $cache[$query];
} else {
    foreach ($rows as $row) {
        if ($row['type'] === 'beer') {
            $preparedResult[] = $row;
        }
    }
    $preparedResult = array_slice($preparedResult, 0, 10);
}

echo count($normal) . "\n";
echo count($preparedResult) . "\n";
echo ($normal[0]['name'] ?? '') . "\n";
echo ($preparedResult[0]['name'] ?? '') . "\n";
?>
--EXPECT--
