--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/8)
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
    public $targetNode;
    public $data;
    public function __construct($targetNode, $data) {
        $this->targetNode = $targetNode;
        $this->data = $data;
    }
}
function validateNode($n) {
    if ($n === null) return;
    if (isset($n->Foo) && strlen($n->Foo) > 4) return;
    if (!isset($n->Bar) || $n->Bar === null) return;
    if (isset($n->Baz) && !preg_match('/^\w*$/u', $n->Baz)) return;
}
$node = new TestNode();
$node->Foo = "foo東京";
$node->Bar = "bar";
$node->Baz = "baz";
$payload = new TestPayload();
$payload->Foo = "123";
$payload->Bar = "456";
$payload->Baz = "789";
$rel = new TestRelationship(789, $payload);
$json = json_encode([
    "node" => $node,
    "relationship" => $rel,
    "indexEntries" => [
        ["name" => "my_index", "keyValues" => [["key" => "value"], ["key2" => ""], ["key3" => "value3"]]]
    ]
], JSON_UNESCAPED_UNICODE);
$data = json_decode($json, true);
validateNode($node);
echo $data["node"]["Foo"], "\n";
echo $data["relationship"]["data"]["Baz"], "\n";
echo count($data["indexEntries"][0]["keyValues"]), "\n";
?>
--EXPECT--
