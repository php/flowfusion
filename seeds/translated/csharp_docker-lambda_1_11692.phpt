--TEST--
Translated seed (inferredbugs-csharp: docker-lambda/1)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
$payload = str_repeat("x", 5000);
fwrite($h, $payload);
rewind($h);
$logs = stream_get_contents($h);
$tail = strlen($logs) <= 4096 ? $logs : substr($logs, -4096);
preg_match('/"identity_id":"([^"]+)"/', '{"identity_id":"id-123","identity_pool_id":"pool-456"}', $m1);
preg_match('/"identity_pool_id":"([^"]+)"/', '{"identity_id":"id-123","identity_pool_id":"pool-456"}', $m2);
$json = '{"a":1,"b":{"c":2}}';
try {
    json_decode($json, true, 512, JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
}
echo strlen($tail) . "\n";
echo ($m1[1] ?? '') . "\n";
echo ($m2[1] ?? '') . "\n";
echo $json . "\n";
?>
--EXPECT--
