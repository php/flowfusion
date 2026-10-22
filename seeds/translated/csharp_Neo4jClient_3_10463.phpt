--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/3)
--FILE--
<?php
$root = 'http://foo/db/data';
$rootTrailing = 'http://foo/db/data/';

$u = parse_url('http://username:password@foo/db/data');
$authenticator = isset($u['user']) ? 'basic' : null;
$rootUri = $u['scheme'] . '://' . $u['host'] . (isset($u['path']) ? $u['path'] : '');

$resp500 = ['status' => 500, 'reason' => 'Internal Server Error'];
if ($resp500['status'] !== 200) {
    $msg = "Received an unexpected HTTP status when executing the request.\r\n\r\nThe response status was: {$resp500['status']} {$resp500['reason']}";
}

$json = json_decode('{
  "batch" : "http://foo/db/data/batch",
  "node" : "http://foo/db/data/node",
  "node_index" : "http://foo/db/data/index/node",
  "relationship_index" : "http://foo/db/data/index/relationship",
  "reference_node" : "http://foo/db/data/node/0",
  "neo4j_version" : "1.5.M02",
  "extensions_info" : "http://foo/db/data/ext",
  "extensions" : {}
}', true);

$node = preg_replace('#^http://foo/db/data#', '', $json['node']);
$nodeIndex = preg_replace('#^http://foo/db/data#', '', $json['node_index']);
$relationshipIndex = preg_replace('#^http://foo/db/data#', '', $json['relationship_index']);
$referenceNode = $json['reference_node'];
$extensionsInfo = preg_replace('#^http://foo/db/data#', '', $json['extensions_info']);

$version = preg_replace('/([A-Za-z])/', '.$1', $json['neo4j_version']);
$version = str_replace('M', '.', $version);

if ($authenticator !== 'basic' || $rootUri !== 'http://foo/db/data' || $node !== '/node' || $nodeIndex !== '/index/node' || $relationshipIndex !== '/index/relationship' || $referenceNode !== 'http://foo/db/data/node/0' || $extensionsInfo !== '/ext' || $version !== '1.5..0.2') {
    exit(1);
}

echo $msg, PHP_EOL;
echo $root, PHP_EOL;
echo $rootTrailing, PHP_EOL;
echo $authenticator, PHP_EOL;
echo $rootUri, PHP_EOL;
echo $node, PHP_EOL;
echo $nodeIndex, PHP_EOL;
echo $relationshipIndex, PHP_EOL;
echo $referenceNode, PHP_EOL;
echo $extensionsInfo, PHP_EOL;
echo $version, PHP_EOL;
?>
--EXPECT--
