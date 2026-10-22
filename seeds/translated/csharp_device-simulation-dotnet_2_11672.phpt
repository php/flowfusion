--TEST--
Translated seed (inferredbugs-csharp: device-simulation-dotnet/2)
--FILE--
<?php
$endpoint = 'http://127.0.0.1/';
$cases = [
    ['ServiceStart', ''],
    ['ServiceHeartbeat', ''],
    ['ServiceError', ['message' => "m\n", 'ExceptionMessage' => 'e', 'data' => ['x' => 1]]],
    ['ServiceError', ['message' => "m", 'data' => null]],
];

foreach ($cases as [$type, $payload]) {
    $obj = ['EventType' => $type, 'EventProperties' => null];
    if ($type === 'ServiceStart') {
        $obj['EventType'] .= $payload;
    } elseif ($type === 'ServiceError') {
        $obj['EventProperties'] = $payload;
    }
    $req = [
        'uri' => $endpoint,
        'content' => $obj,
    ];
    json_encode($req, JSON_THROW_ON_ERROR);
}

echo "ok\n";
?>
--EXPECT--
