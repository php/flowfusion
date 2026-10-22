--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/16)
--FILE--
<?php
$base = 'http://foo/db/data';
$root = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","extensions_info":"http://foo/db/data/ext","extensions":{"GremlinPlugin":{"execute_script":"http://foo/db/data/ext/GremlinPlugin/graphdb/execute_script"}}}', true);
$script = 'foo bar query';

if (strpos($base, 'db/data') === false) {
    throw new RuntimeException('not connected');
}

if (!isset($root['extensions']['GremlinPlugin']['execute_script'])) {
    throw new RuntimeException('not connected');
}

$payload = ['script' => $script, 'params' => null];
$json = json_encode($payload);
if ($json === false) {
    throw new RuntimeException('json encode failed');
}

$result = json_decode('1', true);
echo (string)(int)$result;
?>
--EXPECT--
