--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/50)
--FILE--
<?php
$rows = [];
for ($i = 0; $i < 10; $i++) {
    $rows[] = ['id' => "doc_$i", 'value' => ['id' => "doc_$i", 'type' => 'beer']];
}

$firstId = $rows[0]['id'];
$filtered = array_values(array_filter($rows, fn($r) => $r['id'] === $firstId));
$queriedId = $filtered[0]['id'] ?? null;
if ($queriedId !== $firstId) {
    throw new Exception('mismatch');
}

foreach ($rows as $row) {
    if (!isset($row['id']) || $row['id'] === '') {
        throw new Exception('missing id');
    }
}

$notFound = ['success' => false, 'status' => 404, 'message' => 'not found', 'error' => null, 'rows' => [], 'exception' => null];
$badRequest = ['success' => false, 'status' => 400, 'message' => 'query_parse_error', 'error' => 'query_parse_error', 'rows' => [], 'exception' => null];
$serviceUnavailable = ['success' => false, 'status' => 503, 'message' => 'service unavailable', 'error' => null, 'rows' => [], 'exception' => new RuntimeException('connection failed')];

if ($notFound['status'] !== 404 || $badRequest['error'] !== 'query_parse_error' || !($serviceUnavailable['exception'] instanceof RuntimeException)) {
    throw new Exception('status mismatch');
}

$asyncLike = [];
for ($i = 0; $i < 1000; $i++) {
    $asyncLike[] = ['i' => $i, 'success' => true, 'message' => 'ok'];
}
foreach ($asyncLike as $item) {
    if (!$item['success']) {
        throw new Exception('unexpected failure');
    }
}

echo "ok\n";
?>
--EXPECT--
