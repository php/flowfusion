--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/38)
--FILE--
<?php
$rows = [
    ['name' => 'alpha'],
    ['name' => 'beer-sample'],
    ['name' => 'omega'],
];

$cache = [];
for ($i = 0; $i < 3; $i++) {
    $query = "SELECT * FROM `beer-sample` LIMIT 100";
    $prepared = $cache[$query] ?? null;
    if ($prepared === null) {
        $prepared = ['sql' => $query, 'plan' => md5($query, true)];
        $cache[$query] = $prepared;
    }
    $result = [];
    foreach ($rows as $row) {
        if ($row['name'] === 'beer-sample') {
            $result[] = $row;
        }
    }
}

$named = ['type' => 'beer'];
$positional = ['beer'];
$queries = [
    "SELECT 'Hello World' AS Greeting",
    "SELECT 'Hello World' ASB Greeting",
    "SELECT * FROM `beer-sample` WHERE type=\$1 LIMIT 10",
    "SELECT * FROM `beer-sample` WHERE type=\$type LIMIT 10",
];

foreach ($queries as $q) {
    $ok = strpos($q, 'ASB') === false;
    $rowsOut = $ok ? [['Greeting' => 'Hello World'], ['type' => 'beer']] : [];
    if ($ok && isset($named['type']) && $named['type'] === 'beer' && $positional[0] === 'beer') {
        $rowsOut = array_slice($rowsOut, 0, 1);
    }
    foreach ($rowsOut as $r) {
        echo json_encode($r, JSON_UNESCAPED_SLASHES), PHP_EOL;
    }
}
?>
--EXPECT--
