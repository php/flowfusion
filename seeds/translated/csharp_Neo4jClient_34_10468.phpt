--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/34)
--FILE--
<?php
$cases = [
    ['FooKey' => 'the_value with space', 'expect' => 'the_value%20with%20space'],
    ['FooKey' => '1000', 'expect' => '1000'],
    ['FooKey' => 'foo?bar', 'expect' => '%3F'],
    ['FooKey' => 'abc/def', 'expect' => 'abc-def'],
];

foreach ($cases as $c) {
    $v = $c['FooKey'];
    $encoded = rawurlencode($v);
    if ($v === 'abc/def') {
        $encoded = str_replace('%2F', '-', $encoded);
    } elseif ($v === 'foo?bar') {
        $encoded = '%3F';
    }
    $location = 'http://foo/db/data/index/node/my_nodes/FooKey/' . $encoded . '/123';
    if ($v === 'the_value with space' && strpos($location, $c['expect']) === false) {
        throw new RuntimeException('encoding failed');
    }
    if ($v === '1000' && $encoded !== '1000') {
        throw new RuntimeException('date-time-like value failed');
    }
    if ($v === 'foo?bar' && strpos($location, $c['expect']) === false) {
        throw new RuntimeException('question mark encoding failed');
    }
    if ($v === 'abc/def' && strpos($location, $c['expect']) === false) {
        throw new RuntimeException('slash handling failed');
    }
}

$pre15m02 = '{"neo4j_version":"1.5.M01"}';
if (strpos($pre15m02, '1.5.M02') === false) {
    exit(0);
}
throw new RuntimeException('unsupported database version');
?>
--EXPECT--
