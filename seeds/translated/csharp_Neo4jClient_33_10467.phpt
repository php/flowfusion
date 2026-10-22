--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/33)
--FILE--
<?php
$root = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","extensions_info":"http://foo/db/data/ext","extensions":{"GremlinPlugin":{"execute_script":"http://foo/db/data/ext/GremlinPlugin/graphdb/execute_script"}}}', true);
$r1 = json_decode('[{"start":"http://127.0.0.1:5118/db/data/node/123","data":{},"self":"http://127.0.0.1:5118/db/data/relationship/456","property":"http://127.0.0.1:5118/db/data/relationship/456/properties/{key}","properties":"http://127.0.0.1:5118/db/data/relationship/456/properties","type":"KNOWS","extensions":{},"end":"http://127.0.0.1:5118/db/data/node/789"}]', true);
$r2 = json_decode('[{"start":"http://127.0.0.1:5118/db/data/node/123","data":{"Foo":"Foo","Bar":"Bar"},"self":"http://127.0.0.1:5118/db/data/relationship/456","property":"http://127.0.0.1:5118/db/data/relationship/456/properties/{key}","properties":"http://127.0.0.1:5118/db/data/relationship/456/properties","type":"KNOWS","extensions":{},"end":"http://127.0.0.1:5118/db/data/node/789"}]', true);
$r3 = json_decode('[]', true);
foreach ([[$root, $r1], [$root, $r2], [$root, $r3]] as [$meta, $rows]) {
    $out = [];
    foreach ($rows as $row) {
        $id = (int)basename($row['self']);
        $sid = (int)basename($row['start']);
        $eid = (int)basename($row['end']);
        $item = ['Reference' => ['Id' => $id], 'StartNodeReference' => ['Id' => $sid], 'EndNodeReference' => ['Id' => $eid], 'TypeKey' => $row['type']];
        if (isset($row['data']) && $row['data']) $item['Data'] = (object)$row['data'];
        $out[] = $item;
    }
    if (count($rows) === 1 && isset($rows[0]['data']['Foo'])) {
        $out[0]['Data']->Foo;
        $out[0]['Data']->Bar;
    }
}
?>
--EXPECT--
