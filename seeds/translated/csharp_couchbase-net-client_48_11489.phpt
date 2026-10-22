--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/48)
--FILE--
<?php
$rows = [
    ['name' => 'alpha'],
    ['name' => 'beer-sample'],
    ['name' => 'omega'],
];

$found = false;
foreach ($rows as $row) {
    if ($row['name'] === 'beer-sample') {
        $found = true;
        break;
    }
}

if ($found) {
    $indexes = [];
    foreach ($rows as $row) {
        if ($row['name'] !== 'beer-sample') {
            $indexes[] = $row;
        }
    }
}

$prepared = false;
$statement = "SELECT * FROM `beer-sample` LIMIT 100";
$cache = [];
for ($i = 0; $i < 3; $i++) {
    if ($prepared) {
        $key = $statement;
    } else {
        $key = $statement . "#noprep";
    }
    if (!isset($cache[$key])) {
        $cache[$key] = ['status' => 'success', 'rows' => range(1, 100)];
    }
    $result = $cache[$key];
    if ($i === 0) {
        $prepared = true;
    }
}

$query = "SELECT 'Hello World' AS Greeting";
$result = ['status' => 'success', 'rows' => [['Greeting' => 'Hello World']]];
$greeting = (string)$result['rows'][0]['Greeting'];

$badQuery = "SELECT 'Hello World' ASB Greeting";
$badResult = ['status' => 'failure', 'rows' => []];

$params = ['type' => 'beer'];
$rows = [];
for ($i = 0; $i < 10; $i++) {
    $rows[] = ['type' => $params['type'], 'id' => $i];
}

$positional = ['beer'];
$named = ['type' => 'beer'];

for ($i = 0; $i < 3; $i++) {
    $preparedRequest = ($i > 0);
    $rows = [];
    for ($j = 0; $j < 10; $j++) {
        $rows[] = ['type' => $preparedRequest ? $named['type'] : $positional[0], 'n' => $j];
    }
}

echo $greeting, PHP_EOL;
echo count($indexes), PHP_EOL;
echo count($rows), PHP_EOL;
echo $badResult['status'], PHP_EOL;
?>
--EXPECT--
