--TEST--
Translated seed (inferredbugs-csharp: Library/1)
--FILE--
<?php
$payload = [
    'firstName' => 'xxx',
    'lastName' => 'yyy',
    'age' => 23,
    'phoneNumbers' => ['1111111111', '2222222222', '3333333333', '4444444444', '5555555555'],
];

$json = json_encode($payload, JSON_UNESCAPED_SLASHES);
$path = '/benchmark/ok/123';
$ip = '000.000.000.' . random_int(100, 199);

$requests = [
    ['POST', $path, $json, ['Content-Type: application/json']],
    ['POST', $path, $json, ['Content-Type: application/json']],
    ['POST', '/benchmark/throttle/123', $json, ['Content-Type: application/json', 'X-Forwarded-For: ' . $ip]],
    ['POST', $path, $json, ['Content-Type: application/json']],
    ['POST', $path, $json, ['Content-Type: application/json']],
];

foreach ($requests as [$method, $uri, $body, $headers]) {
    $opts = [
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'content' => $body,
            'ignore_errors' => true,
        ],
    ];
    @file_get_contents('http://127.0.0.1' . $uri, false, stream_context_create($opts));
}
?>
--EXPECT--
