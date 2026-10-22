--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/5)
--FILE--
<?php
class TestNode {
    public $Foo;
    public $Bar;
    public $Baz;
}
class TestNode2 {
    public $Foo;
    public $Bar;
}
class TestPayload {
    public $Foo;
    public $Bar;
    public $Baz;
}
class TestRelationship {
    public $TargetNode;
    public $Data;
    public function __construct($targetNode, $data) {
        $this->TargetNode = $targetNode;
        $this->Data = $data;
    }
}
$node = new TestNode();
$node->Foo = "foo東京";
$node->Bar = "bar";
$node->Baz = "baz";
$payload = new TestPayload();
$payload->Foo = "123";
$payload->Bar = "456";
$payload->Baz = "789";
$relationship = new TestRelationship(789, $payload);
$graph = [
    "node" => $node,
    "relationship" => $relationship,
    "indexEntries" => [
        ["name" => "my_index", "key" => "key", "value" => "value"],
        ["name" => "my_index", "key" => "key2", "value" => ""],
        ["name" => "my_index", "key" => "key3", "value" => "value3"],
    ],
];
$json = json_encode($graph, JSON_UNESCAPED_UNICODE);
$decoded = json_decode($json, true);
if (!is_array($decoded) || $decoded["node"]["Foo"] !== "foo東京" || $decoded["relationship"]["TargetNode"] !== 789) {
    exit(1);
}
echo $json, PHP_EOL;
?>
--EXPECT--
