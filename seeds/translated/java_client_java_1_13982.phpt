--TEST--
Translated seed (inferredbugs-java: client_java/1)
--FILE--
<?php

$mockGcBean1 = [
    'name' => 'MyGC1',
    'collectionCount' => 100,
    'collectionTime' => 10000
];

$mockGcBean2 = [
    'name' => 'MyGC2',
    'collectionCount' => 200,
    'collectionTime' => 20000
];

$mockList = [$mockGcBean1, $mockGcBean2];

$registry = [];
$registry['collections_count'] = [
    'MyGC1' => $mockGcBean1['collectionCount'],
    'MyGC2' => $mockGcBean2['collectionCount']
];

$registry['collections_time'] = [
    'MyGC1' => $mockGcBean1['collectionTime'] / 1000,
    'MyGC2' => $mockGcBean2['collectionTime'] / 1000
];

$gc1Count = $registry['collections_count']['MyGC1'];
$gc1Time = $registry['collections_time']['MyGC1'];
$gc2Count = $registry['collections_count']['MyGC2'];
$gc2Time = $registry['collections_time']['MyGC2'];

echo "$gc1Count\n";
echo "$gc1Time\n";
echo "$gc2Count\n";
echo "$gc2Time\n";
--EXPECT--
