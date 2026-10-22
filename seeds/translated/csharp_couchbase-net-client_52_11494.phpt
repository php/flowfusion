--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/52)
--FILE--
<?php
$rows = [
    ['id' => 'beer-1', 'key' => 'beer-1', 'value' => ['name' => 'A']],
    ['id' => 'beer-2', 'key' => 'beer-2', 'value' => ['name' => 'B']],
    ['id' => 'beer-3', 'key' => 'beer-3', 'value' => ['name' => 'C']],
];

$result = ['rows' => array_slice($rows, 0, 1), 'success' => true, 'status' => 200, 'error' => null, 'message' => null];
$key = $result['rows'][0]['id'];
$result2 = ['rows' => array_values(array_filter($rows, fn($r) => $r['id'] === $key)), 'success' => true, 'status' => 200, 'error' => null, 'message' => null];
echo $result['rows'][0]['id'] . "\n";
echo $result2['rows'][0]['id'] . "\n";

foreach (array_slice($rows, 0, 10) as $row) {
    echo $row['id'] . "\n";
}

$notFound = ['rows' => [], 'success' => false, 'status' => 404, 'error' => null, 'message' => 'not found'];
echo $notFound['message'] . "\n";

$badRequest = ['rows' => [], 'success' => false, 'status' => 400, 'error' => 'query_parse_error', 'message' => 'invalid parameters'];
echo $badRequest['error'] . "\n";

$timeout = ['rows' => [], 'success' => false, 'status' => 503, 'error' => null, 'message' => 'service unavailable', 'exception' => 'RuntimeException'];
echo $timeout['exception'] . "\n";

for ($i = 0; $i < 1000; $i++) {
    $r = ['success' => true, 'message' => 'ok'];
    if (!$r['success']) break;
}
?>
--EXPECT--
