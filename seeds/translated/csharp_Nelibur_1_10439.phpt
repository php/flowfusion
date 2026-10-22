--TEST--
Translated seed (inferredbugs-csharp: Nelibur/1)
--FILE--
<?php
$method = $argv[1] ?? 'GET';
$payload = $argv[2] ?? '{"x":1}';

if ($method === 'GET' || $method === 'DELETE') {
    $requestData = rawurldecode($payload);
    $request = json_decode($requestData, true);
} else {
    $request = json_decode($payload, true);
}

$response = ['ok' => true, 'request' => $request, 'method' => $method];
echo json_encode($response, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
