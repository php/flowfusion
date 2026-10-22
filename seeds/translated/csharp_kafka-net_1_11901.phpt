--TEST--
Translated seed (inferredbugs-csharp: kafka-net/1)
--FILE--
<?php
$u = parse_url('http://CSDKAFKA01:9092');
$host = $u['host'] ?? 'CSDKAFKA01';
$port = $u['port'] ?? 9092;
$fp = @fsockopen($host, $port, $errno, $errstr, 1);
if ($fp) {
    $payload = json_encode([
        'ClientId' => 'kafka-python',
        'CorrelationId' => 1,
        'Payload' => [[
            'Topic' => 'TestHarness',
            'Messages' => [['Value' => 'Test Message']]
        ]]
    ], JSON_UNESCAPED_SLASHES);
    fwrite($fp, $payload . "\n");
    stream_get_contents($fp);
    fclose($fp);
}
?>
--EXPECT--
