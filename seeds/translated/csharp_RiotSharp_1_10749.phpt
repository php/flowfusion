--TEST--
Translated seed (inferredbugs-csharp: RiotSharp/1)
--FILE--
<?php
$rateLimiters = [];
$rateLimitPer10S = 1;
$rateLimitPer10M = 2;
$regions = ['na', 'euw', 'na', 'kr', 'euw'];

foreach ($regions as $region) {
    if (!isset($rateLimiters[$region])) {
        $rateLimiters[$region] = ['per10s' => $rateLimitPer10S, 'per10m' => $rateLimitPer10M, 'count' => 0];
    }
    $rateLimiters[$region]['count']++;
    $rootDomain = $region . '.api.pvp.net';
    $url = 'https://' . $rootDomain . '/api/test?region=' . rawurlencode($region);
    $payload = json_encode(['region' => $region, 'count' => $rateLimiters[$region]['count']], JSON_UNESCAPED_SLASHES);
    $method = ($rateLimiters[$region]['count'] % 2 === 0) ? 'PUT' : 'POST';
    $headers = "Content-Type: application/json\r\nX-Method: $method\r\n";
    $context = stream_context_create([
        'http' => [
            'method' => $method,
            'header' => $headers,
            'content' => $payload,
            'ignore_errors' => true,
            'timeout' => 1,
        ],
    ]);
    @file_get_contents($url, false, $context);
}

echo count($rateLimiters) . PHP_EOL;
?>
--EXPECT--
