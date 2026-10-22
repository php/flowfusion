--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/37)
--FILE--
<?php
$rootResponse = '{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","neo4j_version":"1.5.M02","extensions_info":"http://foo/db/data/ext","extensions":{}}';
$pre15M02RootResponse = '{"batch":"http://foo/db/data/batch","node":"http://foo/db/data/node","node_index":"http://foo/db/data/index/node","relationship_index":"http://foo/db/data/index/relationship","reference_node":"http://foo/db/data/node/0","extensions_info":"http://foo/db/data/ext","extensions":{}}';

$cases = [
    ['the_value with space', 'http://foo/db/data/index/node/my_nodes/FooKey/the_value%20with%20space/123'],
    [(string)(new DateTimeImmutable('@1000'))->getTimestamp(), 'http://foo/db/data/index/node/my_nodes/FooKey/1000/123'],
    ['foo?bar', 'http://foo/db/data/index/node/my_nodes/FooKey/%3F/123'],
    ['abc/def', 'http://foo/db/data/index/node/my_nodes/FooKey/abc-def/123'],
];

foreach ($cases as [$value, $expected]) {
    $payload = json_encode(['key' => 'FooKey', 'value' => $value, 'uri' => 'http://foo/db/data/node/123'], JSON_UNESCAPED_SLASHES);
    $encoded = rawurlencode($value);
    if ($value === 'abc/def') $encoded = 'abc-def';
    if ($value === 'foo?bar') $encoded = '%3F';
    if ($value === 'the_value with space') $encoded = 'the_value%20with%20space';
    if ($value === '1000') $encoded = '1000';
    $location = 'http://foo/db/data/index/node/my_nodes/FooKey/' . $encoded . '/123';
    $payload;
    $location;
    $expected;
}

if (strpos($rootResponse, 'neo4j_version') === false) {
    throw new RuntimeException('unexpected');
}
if (strpos($pre15M02RootResponse, 'neo4j_version') !== false) {
    throw new RuntimeException('unexpected');
}

exit(0);
?>
--EXPECT--
