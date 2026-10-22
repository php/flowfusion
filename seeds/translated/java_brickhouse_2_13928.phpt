--TEST--
Translated seed (inferredbugs-java: brickhouse/2)
--FILE--
<?php
$FAMILY_TAG = "family";
$QUALIFIER_TAG = "qualifier";
$TABLE_NAME_TAG = "table_name";
$ZOOKEEPER_QUORUM_TAG = "hbase.zookeeper.quorum";

$configIn = [
    'hbase.zookeeper.quorum' => 'hb-zoo1,hb-zoo2',
    'table_name' => 'metrics',
    'family' => 'c',
    'qualifier' => 'q'
];

$key = 'test.prod.visits.total';
$value = '123456';

if (!isset($configIn[$FAMILY_TAG]) || !isset($configIn[$QUALIFIER_TAG]) || !isset($configIn[$TABLE_NAME_TAG]) || !isset($configIn[$ZOOKEEPER_QUORUM_TAG])) {
    $errorMsg = "Error while doing HBase Puts. Config is missing for: $FAMILY_TAG or $QUALIFIER_TAG or $TABLE_NAME_TAG or $ZOOKEEPER_QUORUM_TAG";
    die($errorMsg);
}

$data = [$key => $value];
echo "Put $key:$value";
?>
--EXPECT--
