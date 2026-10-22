--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/15)
--FILE--
<?php
$baseUri = 'http://foo/db/data';
$rootJson = '{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/123","neo4j_version":"1.5.M02","extensions_info":"http://foo/db/data/ext","extensions":{}}';

$client = new stdClass();
$client->baseUri = $baseUri;
$client->connected = true;

$rootNode = new stdClass();
$rootNode->client = $client;
$rootNode->queryText = 'g.v(p0)';
$rootNode->queryParameters = ['p0' => 123];
$rootNode->data = json_decode($rootJson, true);

$ref = $rootNode->client;
$q = $rootNode->queryText;
$p = $rootNode->queryParameters['p0'];
$node = $rootNode->data['reference_node'];

echo '';
?>
--EXPECT--
