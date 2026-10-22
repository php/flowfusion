--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/29)
--FILE--
<?php
$base = 'http://foo/db/data';
$root = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","extensions_info":"http://foo/db/data/ext","extensions":{"GremlinPlugin":{"execute_script":"http://foo/db/data/ext/GremlinPlugin/graphdb/execute_script"}}}', true);
$nodes = json_decode('[{"outgoing_relationships":"http://foo/db/data/node/5/relationships/out","data":{"Bar":"bar","Baz":"baz"},"self":"http://foo/db/data/node/5"},{"outgoing_relationships":"http://foo/db/data/node/6/relationships/out","data":{"Bar":"123","Baz":"456"},"self":"http://foo/db/data/node/6"}]', true);
$empty = json_decode('[]', true);

if (!isset($root['extensions']['GremlinPlugin']['execute_script']) || $root['extensions']['GremlinPlugin']['execute_script'] !== $base . '/ext/GremlinPlugin/graphdb/execute_script') {
    exit(1);
}

$out = [];
foreach ($nodes as $n) {
    $id = (int)preg_replace('~.*/node/(\d+)$~', '$1', $n['self']);
    $out[] = ['id' => $id, 'bar' => (string)$n['data']['Bar'], 'baz' => (string)$n['data']['Baz']];
}

if (count($out) !== 2 || $out[0]['id'] !== 5 || $out[0]['bar'] !== 'bar' || $out[0]['baz'] !== 'baz' || $out[1]['id'] !== 6 || $out[1]['bar'] !== '123' || $out[1]['baz'] !== '456') {
    exit(1);
}

if (count($empty) !== 0) {
    exit(1);
}
?>
--EXPECT--
