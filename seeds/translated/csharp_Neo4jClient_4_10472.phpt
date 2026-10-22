--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/4)
--FILE--
<?php
$node = ["Foo" => "foo東京", "Bar" => "bar", "Baz" => "baz"];
$payload = ["Foo" => "123", "Bar" => "456", "Baz" => "789"];
$indexEntries = [
    ["Name" => "my_index", "KeyValues" => [["key", "value"], ["key2", ""], ["key3", "value3"]]]
];
$batch = [
    ["method" => "POST", "to" => "/node", "body" => $node, "id" => 0],
    ["method" => "POST", "to" => "/node/789/relationships", "body" => ["to" => "{0}", "data" => $payload, "type" => "TEST_RELATIONSHIP"], "id" => 1],
    ["method" => "POST", "to" => "/index/node/my_index", "body" => ["key" => "key", "value" => "value", "uri" => "{0}", "id" => 2]],
    ["method" => "POST", "to" => "/index/node/my_index", "body" => ["key" => "key3", "value" => "value3", "uri" => "{0}", "id" => 3]],
];
$json = json_encode([$node, $payload, $indexEntries, $batch], JSON_UNESCAPED_UNICODE);
if ($json === false) {
    exit(1);
}
$decoded = json_decode($json, true);
if (!is_array($decoded) || count($decoded) !== 4) {
    exit(1);
}
echo "";
?>
--EXPECT--
