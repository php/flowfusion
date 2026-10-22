--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/11)
--FILE--
<?php
$u = parse_url('http://username:password@foo/db/data');
$root = $u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . $u['path'];
$auth = isset($u['user']) ? 'basic' : null;
if ($auth !== 'basic') {
    $u = parse_url('http://foo/db/data');
    $root = $u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . $u['path'];
}
$neoRoot = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/123","neo4j_version":"1.5.M02","extensions_info":"http://foo/db/data/ext","extensions":{}}', true);
$version = preg_replace('/^(\d+)\.(\d+)\.M(\d+)$/', '$1.$2.0.$3', $neoRoot['neo4j_version']);
$ref = null;
if (isset($neoRoot['reference_node']) && preg_match('~/node/(\d+)$~', $neoRoot['reference_node'], $m)) {
    $ref = (int)$m[1];
}
$rootNoRef = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","extensions_info":"http://foo/db/data/ext","extensions":{}}', true);
$ref2 = null;
echo $root . "\n";
echo ($auth === 'basic' ? "basic" : "none") . "\n";
echo $version . "\n";
echo (string)$ref . "\n";
echo (is_null($ref2) ? "null" : (string)$ref2) . "\n";
echo $rootNoRef['node'] . "\n";
echo $neoRoot['node_index'] . "\n";
echo $neoRoot['relationship_index'] . "\n";
echo $neoRoot['extensions_info'] . "\n";
?>
--EXPECT--
