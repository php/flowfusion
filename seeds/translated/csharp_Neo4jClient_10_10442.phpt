--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/10)
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
    public $To;
    public $Data;
    public $Type;
    function __construct($to, $data) { $this->To = $to; $this->Data = $data; $this->Type = "TEST_RELATIONSHIP"; }
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
$batch = [
    ["method" => "POST", "to" => "/node", "body" => $node, "id" => 0],
    ["method" => "POST", "to" => "{0}/relationships", "body" => $relationship, "id" => 1],
];
$json = json_encode($batch, JSON_UNESCAPED_UNICODE);
$decoded = json_decode($json, true);
if (!is_array($decoded) || count($decoded) !== 2) exit(1);
$u = $decoded[0]["body"]["Foo"];
if ($u !== "foo東京") exit(1);
$empty = "";
$indexEntry = ["Name" => "my_index", "KeyValues" => [["key" => "value"], ["key2" => $empty], ["key3" => "value3"]]];
$indexJson = json_encode($indexEntry, JSON_UNESCAPED_UNICODE);
if ($indexJson === false) exit(1);
$incoming = new TestNode2();
$incoming->Foo = "foo";
$incoming->Bar = "bar";
echo $json, "\n", $indexJson, "\n", json_encode($incoming, JSON_UNESCAPED_UNICODE), "\n";
?>
--EXPECT--
