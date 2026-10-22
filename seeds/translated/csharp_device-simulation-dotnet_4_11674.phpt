--TEST--
Translated seed (inferredbugs-csharp: device-simulation-dotnet/4)
--FILE--
<?php
$endpoint = 'http://127.0.0.1/';
$events = [
    ['ServiceStart' . 'x', null],
    ['ServiceHeartbeat', null],
    ['ServiceError', ['message' => "m(CallerMemberName = 'main', CallerFilePath = '" . __FILE__ . "', lineNumber = '0')", 'ExceptionMessage' => '', 'data' => ['k' => 'v']]],
    ['ServiceError', ['message' => "m(CallerMemberName = 'main', CallerFilePath = '" . __FILE__ . "', lineNumber = '0')", 'ExceptionMessage' => 'boom']],
];

foreach ($events as [$type, $props]) {
    $payload = ['EventType' => $type, 'EventProperties' => $props];
    $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $req = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\nContent-Length: " . strlen($json) . "\r\n",
            'content' => $json,
            'ignore_errors' => true,
        ],
    ]);
    @file_get_contents($endpoint, false, $req);
}
?>
--EXPECT--
