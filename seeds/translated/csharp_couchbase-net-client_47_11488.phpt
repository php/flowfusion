--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/47)
--FILE--
<?php
$rows = [
    ['name' => 'alpha'],
    ['name' => 'beer-sample'],
    ['name' => 'omega']
];

$has = false;
foreach ($rows as $row) {
    if ($row['name'] === 'beer-sample') {
        $has = true;
        break;
    }
}

if ($has) {
    $indexes = [];
    foreach ($rows as $row) {
        if ($row['name'] !== 'beer-sample') {
            $indexes[] = $row;
        }
    }
}

$result = [
    'success' => true,
    'rows' => array_values($indexes ?? $rows)
];

if (!$result['success'] || !is_array($result['rows'])) {
    exit(1);
}

$greeting = ['Greeting' => 'Hello World'];
echo $greeting['Greeting'], PHP_EOL;

$params = ['type' => 'beer'];
$out = [];
for ($i = 0; $i < 10; $i++) {
    $out[] = ['type' => $params['type'], 'n' => $i];
}

$prepared = false;
$request = ['statement' => 'SELECT * from `beer-sample` LIMIT 100', 'prepared' => $prepared];
$plan = ['plan' => sha1($request['statement'])];
$request['prepared'] = true;
$exec1 = $plan['plan'];
$exec2 = $plan['plan'];

echo count($out), PHP_EOL;
echo $exec1 === $exec2 ? "ok\n" : "fail\n";
?>
--EXPECT--
