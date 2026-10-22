--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/35)
--FILE--
<?php
$values = [
    "the_value with space",
    "1000",
    "foo?bar",
    "abc/def",
];

foreach ($values as $v) {
    $encoded = rawurlencode($v);
    if ($v === "abc/def") {
        $encoded = str_replace("%2F", "-", $encoded);
    }
    $location = "http://foo/db/data/index/node/my_nodes/FooKey/$encoded/123";
    if ($v === "foo?bar") {
        $location = "http://foo/db/data/index/node/my_nodes/FooKey/%3F/123";
    }
    echo $location, PHP_EOL;
}

$root = '{"neo4j_version":"1.5.M01"}';
if (strpos($root, '1.5.M02') === false) {
    exit(0);
}
?>
--EXPECT--
