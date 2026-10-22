--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/13)
--FILE--
<?php
$u = parse_url('http://username:password@foo/db/data');
$root = $u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . ($u['path'] ?? '');
if ($root !== 'http://foo/db/data') {
    throw new Exception('root uri mismatch');
}
$auth = isset($u['user']) ? 'basic' : null;
if ($auth !== 'basic') {
    throw new Exception('auth missing');
}
$json = '{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/123","neo4j_version":"1.5.M02","extensions_info":"http://foo/db/data/ext","extensions":{}}';
$r = json_decode($json, true);
if (($r['reference_node'] ?? null) !== 'http://foo/db/data/node/123') {
    throw new Exception('reference node missing');
}
if (preg_match('~/node/(\d+)$~', $r['reference_node'], $m) !== 1 || (int)$m[1] !== 123) {
    throw new Exception('id parse failed');
}
$v = preg_replace('/^(\d+)\.(\d+)\.M(\d+)$/', '$1.$2.0.$3', '1.5.M02');
if ($v !== '1.5.0.02') {
    throw new Exception('version parse failed');
}
$noRef = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","extensions_info":"http://foo/db/data/ext","extensions":{}}', true);
if (isset($noRef['reference_node'])) {
    throw new Exception('unexpected reference node');
}
http_response_code(200);
echo "";
?>
--EXPECT--
