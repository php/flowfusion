--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/51)
--FILE--
<?php
$docs = [
    ['id' => 'doc1', 'type' => 'beer', 'name' => 'alpha'],
    ['id' => 'doc2', 'type' => 'beer', 'name' => 'beta'],
    ['id' => 'doc3', 'type' => 'beer', 'name' => 'gamma'],
];

$rows = array_map(fn($d) => (object)['Id' => $d['id'], 'Value' => $d], $docs);
$firstId = $rows[0]->Id;

$filtered = array_values(array_filter($rows, fn($r) => $r->Id === $firstId));
$mapped = array_map(fn($r) => (object)['Id' => $r->Id, 'Name' => $r->Value['name']], array_slice($rows, 0, 10));

$badQueryError = 'query_parse_error';
$notFoundStatus = 404;
$badStatus = 400;
$serviceUnavailable = 503;

$invalidUrl = 'http://127.0.0.1:1/';
$timeout = 1;

$asyncLike = [];
for ($i = 0; $i < 1000; $i++) {
    $asyncLike[] = [$i, true, 'ok'];
}

echo $filtered[0]->Id, "\n";
echo count($mapped), "\n";
echo $badQueryError, " ", $badStatus, "\n";
echo $notFoundStatus, " ", $serviceUnavailable, "\n";
echo $invalidUrl, " ", $timeout, "\n";
echo count($asyncLike), "\n";
?>
--EXPECT--
