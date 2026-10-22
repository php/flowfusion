--TEST--
Translated seed (inferredbugs-csharp: Stardust/6)
--FILE--
<?php
$appId = '';
$secret = 's';
$scope = '';
$version = 1;

$app = (object)[
    'Id' => 'app1',
    'Name' => 'app1',
    'Enable' => true,
    'Version' => 2,
    'UpdateTime' => date('c'),
    'NextVersion' => 3,
    'PublishTime' => date('c'),
    'Secret' => '',
];

if ($appId === '') {
    $appId = 'app1';
}

if ($version >= $app->Version) {
    $result = (object)[
        'Version' => $app->Version,
        'UpdateTime' => $app->UpdateTime,
    ];
} else {
    if ($scope === '') {
        $scope = 'default:' . ($_SERVER['REMOTE_ADDR'] ?? '');
    }

    $configs = [
        'scope' => $scope,
        'k1' => 'v1',
    ];

    $result = (object)[
        'Version' => $app->Version,
        'Scope' => $scope,
        'NextVersion' => $app->NextVersion,
        'NextPublish' => $app->PublishTime,
        'UpdateTime' => $app->UpdateTime,
        'Configs' => $configs,
    ];
}

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
?>
--EXPECT--
