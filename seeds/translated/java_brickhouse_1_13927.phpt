--TEST--
Translated seed (inferredbugs-java: brickhouse/1)
--FILE--
<?php

$configIn = [
    'family' => 'c',
    'qualifier' => 'q',
    'table_name' => 'metrics',
    'hbase.zookeeper.quorum' => 'hb-zoo1,hb-zoo2'
];

$key = 'test.prod.visits.total';
$value = '123456';

if (!isset($configIn['family']) || !isset($configIn['qualifier']) || !isset($configIn['table_name']) || !isset($configIn['hbase.zookeeper.quorum'])) {
    die("Error: Config is missing for family, qualifier, table_name, or hbase.zookeeper.quorum");
}

try {
    // Simulate a put operation, as direct HBase interaction is not available.
    $table = $configIn['table_name'];
    $family = $configIn['family'];
    $qualifier = $configIn['qualifier'];

    // This is a simulated put operation
    echo "Put $key:$value into $table, family: $family, qualifier: $qualifier\n";
} catch(Exception $exc) {
    die("Error while doing Puts");
}

?>
--EXPECT--
