--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/23)
--FILE--
<?php
class TestNode {
    public $Foo;
    public $Bar;
    public $Baz;
    public $DateOffSet;
}
class TestNodeWithEnum {
    public $Foo;
    public $Status;
}
$root = json_decode('{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","extensions_info":"http://foo/db/data/ext","extensions":{}}', true);
$node456 = json_decode('{"self":"http://foo/db/data/node/456","data":{"Foo":"foo","Bar":"bar","Baz":"baz","DateOffSet":"2011-06-30T08:15:46+00:00"},"create_relationship":"http://foo/db/data/node/456/relationships","all_relationships":"http://foo/db/data/node/456/relationships/all","all_typed relationships":"http://foo/db/data/node/456/relationships/all/{-list|&|types}","incoming_relationships":"http://foo/db/data/node/456/relationships/in","incoming_typed relationships":"http://foo/db/data/node/456/relationships/in/{-list|&|types}","outgoing_relationships":"http://foo/db/data/node/456/relationships/out","outgoing_typed relationships":"http://foo/db/data/node/456/relationships/out/{-list|&|types}","properties":"http://foo/db/data/node/456/properties","property":"http://foo/db/data/node/456/property/{key}","traverse":"http://foo/db/data/node/456/traverse/{returnType}"}', true);
$node = new TestNode();
$node->Foo = $node456['data']['Foo'];
$node->Bar = $node456['data']['Bar'];
$node->Baz = $node456['data']['Baz'];
$node->DateOffSet = new DateTimeImmutable($node456['data']['DateOffSet']);
$enumNode = new TestNodeWithEnum();
$enumNode->Foo = 'foo';
$enumNode->Status = 0;
if ($node->Foo !== 'foo' || $node->Bar !== 'bar' || $node->Baz !== 'baz') exit(1);
if ($enumNode->Status !== 0) exit(1);
if ($node->DateOffSet->format('Y-m-d H:i:s\Z') !== '2011-06-30 08:15:46Z') exit(1);
if ($root['node'] !== 'http://foo/db/data/node') exit(1);
echo "";
?>
--EXPECT--
