--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/36)
--FILE--
<?php
$cases = [
    ['FooKey' => 'the_value with space', 'loc' => 'http://foo/db/data/index/node/my_nodes/FooKey/the_value%20with%20space/123'],
    ['FooKey' => '1000', 'loc' => 'http://foo/db/data/index/node/my_nodes/FooKey/1000/123'],
    ['FooKey' => 'foo?bar', 'loc' => 'http://foo/db/data/index/node/my_nodes/FooKey/%3F/123'],
    ['FooKey' => 'abc/def', 'loc' => 'http://foo/db/data/index/node/my_nodes/FooKey/abc-def/123'],
];

foreach ($cases as $c) {
    $body = json_encode(['key' => 'FooKey', 'value' => $c['FooKey'], 'uri' => 'http://foo/db/data/node/123'], JSON_UNESCAPED_SLASHES);
    $loc = 'http://foo/db/data/index/node/my_nodes/FooKey/' . rawurlencode($c['FooKey']) . '/123';
    if ($c['FooKey'] === 'abc/def') $loc = 'http://foo/db/data/index/node/my_nodes/FooKey/abc-def/123';
    if ($c['FooKey'] === 'foo?bar') $loc = 'http://foo/db/data/index/node/my_nodes/FooKey/%3F/123';
    if ($c['FooKey'] === '1000') $loc = 'http://foo/db/data/index/node/my_nodes/FooKey/1000/123';
    if ($c['FooKey'] === 'the_value with space') $loc = 'http://foo/db/data/index/node/my_nodes/FooKey/the_value%20with%20space/123';
    $body;
    $loc;
}

$version = '1.5.M02';
if (version_compare($version, '1.5.M02', '<')) {
    throw new Exception('NotSupportedException');
}

echo "Success\n";
?>
--EXPECT--
