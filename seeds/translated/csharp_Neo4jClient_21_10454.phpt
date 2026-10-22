--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/21)
--FILE--
<?php
$base = 'http://foo/db/data';
$root = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","extensions_info":"http://foo/db/data/ext","extensions":{}}', true);
$rels = json_decode('[{"self":"http://foo/db/data/relationship/56","start":"http://foo/db/data/node/123","end":"http://foo/db/data/node/456","type":"KNOWS","properties":"http://foo/db/data/relationship/56/properties","property":"http://foo/db/data/relationship/56/properties/{key}","data":{"date":1270559208258}},{"self":"http://foo/db/data/relationship/78","start":"http://foo/db/data/node/456","end":"http://foo/db/data/node/789","type":"KNOWS","properties":"http://foo/db/data/relationship/78/properties","property":"http://foo/db/data/relationship/78/properties/{key}","data":{"date":1270559208258}}]', true);

$requests = [];
$requests[] = ['method' => 'GET', 'resource' => ''];
$requests[] = ['method' => 'GET', 'resource' => '/node/456/relationships/all'];
foreach ($rels as $r) $requests[] = ['method' => 'DELETE', 'resource' => parse_url($r['self'], PHP_URL_PATH)];
$requests[] = ['method' => 'DELETE', 'resource' => '/node/456'];

foreach ($requests as $req) {
    if ($req['method'] === 'GET' && $req['resource'] === '') {
        $resp = $root;
    } elseif ($req['method'] === 'GET' && $req['resource'] === '/node/456/relationships/all') {
        $resp = $rels;
    } else {
        $resp = null;
    }
}

echo "";
?>
--EXPECT--
