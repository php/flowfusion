--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/40)
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
        $indexes = array_values(array_filter($indexes, fn($x) => $x !== 'primary'));
    }
}

$prepared = [];
$query = 'SELECT * FROM `beer-sample` WHERE type=$1 LIMIT 10';
for ($i = 0; $i < 3; $i++) {
    $key = $query . '|positional|beer|prepared=' . (($i === 0) ? '1' : '0');
    if (!isset($prepared[$key])) {
        $prepared[$key] = ['status' => 'success', 'rows' => array_slice(array_values(array_filter($rows, fn($r) => $r['type'] === 'beer')), 0, 10)];
    }
    $result = $prepared[$key];
    foreach ($result['rows'] as $row) {
        echo json_encode($row, JSON_UNESCAPED_SLASHES) . PHP_EOL;
    }
}

$result = ['status' => 'success', 'rows' => [['Greeting' => 'Hello World']]];
echo $result['rows'][0]['Greeting'] . PHP_EOL;

$bad = ['status' => 'failure', 'rows' => []];
if ($bad['status'] !== 'success') {
    $bad['rows'] = [];
}

$named = array_values(array_filter($rows, fn($r) => $r['type'] === 'beer'));
for ($i = 0; $i < 2; $i++) {
    foreach (array_slice($named, 0, 10) as $row) {
        echo json_encode($row, JSON_UNESCAPED_SLASHES) . PHP_EOL;
    }
}
?>
--EXPECT--
