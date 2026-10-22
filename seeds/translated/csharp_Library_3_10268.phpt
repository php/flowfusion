--TEST--
Translated seed (inferredbugs-csharp: Library/3)
--FILE--
<?php
$payload = json_encode([
    'firstName' => 'xxx',
    'lastName' => 'yyy',
    'age' => 23,
    'phoneNumbers' => [
        '1111111111',
        '2222222222',
        '3333333333',
        '4444444444',
        '5555555555',
    ],
], JSON_UNESCAPED_SLASHES);

$headers = [
    'Content-Type: application/json',
    'X-Forwarded-For: 000.000.000.' . random_int(100, 199),
];

$paths = [
    'benchmark/ok/123',
    'benchmark/throttle/123',
];

foreach ($paths as $path) {
    $ch = curl_init('http://127.0.0.1/' . $path);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    curl_exec($ch);
    curl_close($ch);
}
?>
--EXPECT--
