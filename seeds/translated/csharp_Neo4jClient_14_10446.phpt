--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/14)
--FILE--
<?php
$root = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/123","neo4j_version":"1.5.M02","extensions_info":"http://foo/db/data/ext","extensions":{}}', true);

$client = (object)['baseUri' => 'http://foo/db/data', 'root' => $root];
$rootNode = (object)[
    'client' => $client,
    'queryText' => 'g.v(p0)',
    'queryParameters' => ['p0' => 123],
];

if ($rootNode->client !== $client) {
    throw new Exception('client mismatch');
}
if ($rootNode->queryText !== 'g.v(p0)') {
    throw new Exception('query text mismatch');
}
if (($rootNode->queryParameters['p0'] ?? null) !== 123) {
    throw new Exception('query parameter mismatch');
}
?>
--EXPECT--
