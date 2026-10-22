--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/28)
--FILE--
<?php
$rootResponse = json_decode(str_replace("'", '"', "{'batch':'http://foo/db/data/batch','node':'http://foo/db/data/node','node_index':'http://foo/db/data/index/node','relationship_index':'http://foo/db/data/index/relationship','reference_node':'http://foo/db/data/node/0','extensions_info':'http://foo/db/data/ext','extensions':{'GremlinPlugin':{'execute_script':'http://foo/db/data/ext/GremlinPlugin/graphdb/execute_script'}}}"), true);
$indexesJson = json_decode(str_replace("'", '"', "{'agency24871-clients':{'to_lower_case':'true','template':'http://localhost:5102/db/data/index/node/agency24871-clients/{key}/{value}','_blueprints:type':'MANUAL','provider':'lucene','type':'fulltext'},'agency36681-clients':{'to_lower_case':'true','template':'http://localhost:5102/db/data/index/node/agency36681-clients/{key}/{value}','_blueprints:type':'MANUAL','provider':'lucene','type':'fulltext'}}"), true);
$emptyJson = json_decode("{}", true);

$cases = [
    [$rootResponse, $indexesJson],
    [$rootResponse, $emptyJson],
    [$rootResponse, $indexesJson],
];

foreach ($cases as [$root, $indexes]) {
    $count = is_array($indexes) ? count($indexes) : 0;
    if ($count < 0) {
        throw new Exception();
    }
    if (!isset($root['node_index'])) {
        throw new Exception();
    }
}
?>
--EXPECT--
