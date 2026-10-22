--TEST--
Translated seed (inferredbugs-csharp: Library/6)
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

$paths = [
    'benchmark/ok/123',
    'benchmark/throttle/123',
];

for ($i = 0; $i < 10000; $i++) {
    $ip = '000.000.000.' . random_int(100, 199);
    foreach ($paths as $path) {
        $ch = curl_init('http://127.0.0.1/' . $path);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $path === 'benchmark/throttle/123'
                ? ['Content-Type: application/json', 'X-Forwarded-For: ' . $ip]
                : ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>
--EXPECT--
