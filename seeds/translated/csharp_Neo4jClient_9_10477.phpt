--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/9)
--FILE--
<?php
$node = ['Foo' => 'foo東京', 'Bar' => 'bar', 'Baz' => 'baz'];
$payload = ['Foo' => '123', 'Bar' => '456', 'Baz' => '789'];
$indexEntries = [
    ['Name' => 'my_index', 'KeyValues' => [['key' => 'key', 'value' => 'value'], ['key' => 'key2', 'value' => ''], ['key' => 'key3', 'value' => 'value3']]]
];
$batch = [
    ['method' => 'POST', 'to' => '/node', 'body' => $node, 'id' => 0],
    ['method' => 'POST', 'to' => '{0}/relationships', 'body' => ['to' => '/node/789', 'data' => $payload, 'type' => 'TEST_RELATIONSHIP'], 'id' => 1],
    ['method' => 'POST', 'to' => '/index/node/my_index', 'body' => ['key' => 'key', 'value' => 'value', 'uri' => '{0}'], 'id' => 2],
    ['method' => 'POST', 'to' => '/index/node/my_index', 'body' => ['key' => 'key3', 'value' => 'value3', 'uri' => '{0}'], 'id' => 3],
];
$json = json_encode($batch, JSON_UNESCAPED_UNICODE);
$decoded = json_decode($json, true);
echo $decoded[0]['body']['Foo'], "\n";
echo $decoded[1]['body']['data']['Baz'], "\n";
echo count($indexEntries), "\n";
echo "ok\n";
?>
--EXPECT--
