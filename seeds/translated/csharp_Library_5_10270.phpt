--TEST--
Translated seed (inferredbugs-csharp: Library/5)
--FILE--
<?php
$payload = json_encode([
    'FirstName' => 'xxx',
    'LastName' => 'yyy',
    'Age' => 23,
    'PhoneNumbers' => [
        '1111111111',
        '2222222222',
        '3333333333',
        '4444444444',
        '5555555555',
    ],
], JSON_UNESCAPED_SLASHES);

$paths = [
    'benchmark/ok/123',
    'benchmark/throttle/123',
];

for ($i = 0; $i < 5; $i++) {
    $url = $paths[$i === 1 ? 1 : 0];
    $headers = "Content-Type: application/json\r\n";
    if ($i === 1) {
        $headers .= 'X-Forwarded-For: 000.000.000.' . random_int(100, 199) . "\r\n";
    }
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => $payload,
            'ignore_errors' => true,
        ],
    ];
    @file_get_contents('http://127.0.0.1/' . $url, false, stream_context_create($opts));
}
?>
--EXPECT--
