--TEST--
Translated seed (inferredbugs-csharp: yopngs/1)
--FILE--
<?php
$cfg = [
    'S3Stores' => [
        ['active' => true, 'secretID' => 'a', 'secretKey' => 'b', 'region' => 'us-east-1', 'Domain' => 'd', 'index' => 1, 'name' => 'n1', 'type' => 't1'],
        ['active' => false, 'secretID' => 'c', 'secretKey' => 'd', 'region' => 'us-west-2', 'Domain' => 'e', 'index' => 2, 'name' => 'n2', 'type' => 't2'],
        ['active' => true, 'secretID' => 'e', 'secretKey' => 'f', 'region' => 'eu-central-1', 'Domain' => 'f', 'index' => 3, 'name' => 'n3', 'type' => 't3'],
    ],
];

$stores = [];
foreach ($cfg['S3Stores'] as $section) {
    if (!empty($section['active'])) {
        $stores[] = [
            'Name' => $section['name'] ?? null,
            'Type' => $section['type'] ?? null,
            'Index' => (int)($section['index'] ?? 0),
            'Domain' => $section['Domain'] ?? null,
            'region' => $section['region'] ?? null,
            'key' => $section['secretID'] ?? null,
            'secret' => $section['secretKey'] ?? null,
            'payload' => str_repeat("A", 3) . "\0" . "B",
            'tags' => ['Test' => 'S3Test'],
        ];
    }
}

foreach ($stores as $store) {
    $cdnUrl = '';
    $cdnUrl = $store['Domain'] . '/' . rawurlencode($store['Name']) . '?i=' . $store['Index'];
    $blob = $store['payload'];
    $blob .= substr($store['region'], 0, 2);
    $blob .= $store['key'] . $store['secret'];
    $cdnUrl .= '&len=' . strlen($blob);
}

echo count($stores), "\n";
?>
--EXPECT--
