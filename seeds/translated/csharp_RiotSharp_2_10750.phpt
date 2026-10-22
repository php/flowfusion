--TEST--
Translated seed (inferredbugs-csharp: RiotSharp/2)
--FILE--
<?php
$rateLimits = [1 => 2, 60 => 10];
$rateLimiters = [];

foreach ([1, 2, 1, 3, 2, 1] as $region) {
    if (!isset($rateLimiters[$region])) {
        $rateLimiters[$region] = $rateLimits;
    }
    $rateLimiters[$region]['count'] = ($rateLimiters[$region]['count'] ?? 0) + 1;
    $ok = $rateLimiters[$region]['count'] <= $rateLimiters[$region][1];
    echo ($ok ? "200" : "429") . "\n";
}

foreach ([1, 2, 1] as $region) {
    $body = json_encode(['region' => $region, 'ts' => microtime(true)]);
    $payload = ['method' => 'POST', 'body' => $body, 'headers' => ['Content-Type' => 'application/json']];
    $encoded = json_encode($payload);
    echo strlen($encoded) . "\n";
}
?>
--EXPECT--
