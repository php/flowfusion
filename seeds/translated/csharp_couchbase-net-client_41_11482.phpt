--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/41)
--FILE--
<?php
$rows = [];
for ($i = 0; $i < 100; $i++) {
    $rows[] = ['type' => ($i % 3 === 0 ? 'beer' : 'ale'), 'name' => 'beer-sample-' . $i];
}

$cache = [];
$query = 'SELECT * FROM `beer-sample` WHERE type=$type LIMIT 10';
$prepared = true;

for ($t = 0; $t < 3; $t++) {
    $key = ($prepared ? 'p:' : 'u:') . $query;
    if (!isset($cache[$key])) {
        $cache[$key] = ['plan' => md5($key)];
    }
    $result = [];
    foreach ($rows as $row) {
        if ($row['type'] === 'beer') {
            $result[] = $row;
            if (count($result) === 10) {
                break;
            }
        }
    }
    echo count($result) . "\n";
}

$query = 'SELECT * FROM `beer-sample` WHERE type=$1 LIMIT 10';
for ($t = 0; $t < 3; $t++) {
    $key = 'u:' . $query;
    if (!isset($cache[$key])) {
        $cache[$key] = ['plan' => md5($key)];
    }
    $result = [];
    foreach ($rows as $row) {
        if ($row['type'] === 'beer') {
            $result[] = $row;
            if (count($result) === 10) {
                break;
            }
        }
    }
    echo count($result) . "\n";
}
?>
--EXPECT--
